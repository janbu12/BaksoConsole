<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Rental;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected ?string $serverKey;
    protected ?string $clientKey;
    protected bool $isProduction;

    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key');
        $this->clientKey = config('services.midtrans.client_key');
        $this->isProduction = (bool) config('services.midtrans.is_production', false);
    }

    public function isEnabled(): bool
    {
        return !empty($this->serverKey);
    }

    public function getClientKey(): ?string
    {
        return $this->clientKey;
    }

    public function getSnapUrl(): string
    {
        return $this->isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    /**
     * Create Snap Transaction and get Redirect URL from Midtrans
     */
    public function createSnapRedirect(Rental $rental): string
    {
        if (!$this->isEnabled()) {
            throw new Exception('Midtrans Server Key belum dikonfigurasi di file .env.');
        }

        $transaction = $rental->transaction;
        if (!$transaction) {
            throw new Exception('Data transaksi untuk rental ini tidak ditemukan.');
        }

        $user = $rental->user;
        $orderId = $transaction->invoice_number . '-' . time();
        $grossAmount = (int) round($transaction->total_amount);

        // Build item details
        $items = [
            [
                'id' => 'UNIT-' . ($rental->unit->code ?? $rental->unit_id),
                'price' => (int) round($rental->subtotal),
                'quantity' => 1,
                'name' => mb_substr('Sewa ' . $rental->unit->name . ' (' . $rental->duration_days . ' Hari)', 0, 50),
            ]
        ];

        if ((float) $transaction->delivery_fee > 0) {
            $items[] = [
                'id' => 'DELIVERY-FEE',
                'price' => (int) round($transaction->delivery_fee),
                'quantity' => 1,
                'name' => 'Ongkos Kirim Kurir',
            ];
        }

        if ((float) $transaction->fine_amount > 0) {
            $items[] = [
                'id' => 'FINE-FEE',
                'price' => (int) round($transaction->fine_amount),
                'quantity' => 1,
                'name' => 'Denda / Biaya Tambahan',
            ];
        }

        if ((float) $transaction->discount_amount > 0) {
            $items[] = [
                'id' => 'DISCOUNT',
                'price' => -(int) round($transaction->discount_amount),
                'quantity' => 1,
                'name' => 'Diskon Promo',
            ];
        }

        // Validate total items match gross amount to prevent Midtrans validation error
        $totalItems = array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $items));
        if ($totalItems !== $grossAmount) {
            // Fallback to single item if any rounding discrepancy occurs
            $items = [
                [
                    'id' => $transaction->invoice_number,
                    'price' => $grossAmount,
                    'quantity' => 1,
                    'name' => 'Rental PS Bakso Console',
                ]
            ];
        }

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->profile?->phone ?? '081234567890',
                'billing_address' => [
                    'first_name' => $user->name,
                    'phone' => $user->profile?->phone ?? '081234567890',
                    'address' => $user->profile?->address ?? 'Indonesia',
                ],
            ],
            'callbacks' => [
                'finish' => url('/midtrans/finish'),
                'unfinish' => url('/midtrans/unfinish'),
                'error' => url('/midtrans/error'),
            ],
        ];

        $response = Http::withBasicAuth($this->serverKey, '')
            ->withoutVerifying()
            ->acceptJson()
            ->asJson()
            ->timeout(15)
            ->post($this->getSnapUrl(), $payload);

        if (!$response->successful() || empty($response->json('redirect_url'))) {
            Log::error('Midtrans Snap Error: ' . $response->body());
            $errorMsg = $response->json('error_messages.0') ?? 'Gagal membuat transaksi Midtrans. Periksa konfigurasi kredensial Anda.';
            throw new Exception($errorMsg);
        }

        return $response->json('redirect_url');
    }

    /**
     * Handle HTTP Notification from Midtrans Webhook
     */
    public function handleNotification(array $payload): bool
    {
        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? 'midtrans';

        if (!$orderId) {
            return false;
        }

        // Verify SHA512 signature if server key is available
        if ($this->serverKey && $signatureKey) {
            $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);
            if ($signatureKey !== $expectedSignature) {
                Log::warning('Midtrans Invalid Signature: ' . json_encode($payload));
                return false;
            }
        }

        // Extract raw invoice number (e.g. from INV-20260810-0001-172345678 to INV-20260810-0001)
        $invoiceNumber = preg_replace('/-\d{10}$/', '', $orderId);

        $transaction = Transaction::where('invoice_number', $invoiceNumber)
            ->orWhere('invoice_number', $orderId)
            ->first();

        if (!$transaction) {
            Log::warning("Midtrans transaction not found for Order ID: {$orderId}");
            return false;
        }

        $isPaid = ($transactionStatus === 'settlement') ||
                  ($transactionStatus === 'capture' && $fraudStatus === 'accept');

        if ($isPaid) {
            $transaction->update([
                'status' => PaymentStatus::Paid,
                'paid_at' => now(),
                'notes' => ($transaction->notes ? $transaction->notes . ' | ' : '') . "Dibayar via Midtrans ({$paymentType}) pada " . now()->format('d/m/Y H:i'),
            ]);
            Log::info("Transaction {$transaction->invoice_number} successfully marked as PAID via Midtrans.");
            return true;
        }

        if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            Log::info("Transaction {$transaction->invoice_number} Midtrans status: {$transactionStatus}");
        }

        return true;
    }
}
