<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\Rental;
use App\Services\MidtransService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Process payment: Redirect to Midtrans if enabled, otherwise fallback to simulation
     */
    public function pay(Request $request, Rental $rental, MidtransService $midtrans): RedirectResponse
    {
        abort_unless($rental->user_id === $request->user()->id, 403);
        abort_unless($rental->transaction && $rental->transaction->status === PaymentStatus::Pending, 422, 'Tagihan sudah lunas atau tidak ditemukan.');

        // If Midtrans is enabled in .env, redirect to Midtrans Hosted Payment Page
        if ($midtrans->isEnabled()) {
            try {
                $redirectUrl = $midtrans->createSnapRedirect($rental);
                return redirect()->away($redirectUrl);
            } catch (Exception $e) {
                return back()->withErrors(['payment' => 'Gagal menghubungkan ke Midtrans: ' . $e->getMessage() . ' Silakan gunakan simulasi pembayaran di bawah.']);
            }
        }

        // Fallback to Simulation Mode if Midtrans is not configured
        return $this->simulate($request, $rental);
    }

    /**
     * Instant Payment Simulation (always available for demo/offline testing)
     */
    public function simulate(Request $request, Rental $rental): RedirectResponse
    {
        abort_unless($rental->user_id === $request->user()->id, 403);
        abort_unless($rental->transaction && $rental->transaction->status === PaymentStatus::Pending, 422, 'Tagihan sudah lunas atau tidak ditemukan.');

        $rental->transaction->update([
            'status' => PaymentStatus::Paid,
            'paid_at' => now(),
            'notes' => ($rental->transaction->notes ? $rental->transaction->notes . ' | ' : '') . 'Dibayar via Simulasi pada ' . now()->format('d/m/Y H:i'),
        ]);

        return redirect('/rentals')->with('success', 'Pembayaran berhasil disimulasikan lunas! Pesanan kini masuk ke antrean serah terima admin.');
    }

    /**
     * Midtrans Webhook / HTTP Notification Callback
     */
    public function notification(Request $request, MidtransService $midtrans): JsonResponse
    {
        $handled = $midtrans->handleNotification($request->all());

        return response()->json([
            'status' => $handled ? 'success' : 'ignored',
            'message' => 'Notification processed successfully',
        ]);
    }

    /**
     * Return URL after user completes payment on Midtrans
     */
    public function finish(Request $request): RedirectResponse
    {
        return redirect('/rentals')->with('success', 'Terima kasih! Pembayaran online via Midtrans berhasil diproses. Menunggu konfirmasi serah terima admin.');
    }

    /**
     * Return URL when user closes Midtrans without completing payment
     */
    public function unfinish(Request $request): RedirectResponse
    {
        return redirect('/rentals')->with('info', 'Pembayaran Midtrans belum selesai. Anda dapat melanjutkannya kapan saja.');
    }

    /**
     * Return URL when Midtrans transaction encounters an error
     */
    public function error(Request $request): RedirectResponse
    {
        return redirect('/rentals')->withErrors(['payment' => 'Pembayaran via Midtrans gagal atau dibatalkan. Silakan coba kembali.']);
    }
}
