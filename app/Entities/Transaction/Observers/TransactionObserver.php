<?php

namespace App\Entities\Transaction\Observers;

use App\Entities\Transaction\Models\Transaction;

class TransactionObserver
{
    /**
     * Handle the transaction "created" event.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function created(Transaction $transaction)
    {
        $mobileUser = $transaction->mobileUser;
        if (in_array($transaction->type, [ 'accrual_admin', 'refund_offer' ])) {
            $mobileUser->update([
                'points_balance' => $mobileUser->points_balance + $transaction->change_balance,
                'total_points_accumulated' => $mobileUser->total_points_accumulated + $transaction->change_balance
            ]);
        } elseif ($transaction->type === 'deductions_admin') {
            $mobileUser->update([
                'points_balance' => $mobileUser->points_balance - $transaction->change_balance,
                'total_points_spent' => $mobileUser->total_points_spent + $transaction->change_balance
            ]);

            $transaction->update([
                'change_balance' => $transaction->change_balance * -1
            ]);
        }
    }
}
