<?php

namespace App\Application\Transactions;

use App\Domain\Rentals\TransactionCalculator;
use App\Models\Rental;
use App\Models\Transaction;

class RecalculateTransaction
{
    public function handle(Rental $rental): Transaction
    {
        $transaction = $rental->transaction()->firstOrFail();
        $fine = (float) $rental->fines()->sum('amount');
        $delivery = (float) $rental->deliveries()->sum('delivery_fee');
        $transaction->update(['rental_amount' => $rental->subtotal, 'fine_amount' => $fine, 'delivery_fee' => $delivery, 'total_amount' => TransactionCalculator::total((float) $rental->subtotal, $fine, $delivery, (float) $transaction->discount_amount)]);

        return $transaction->fresh();
    }
}
