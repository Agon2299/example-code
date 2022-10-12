<?php

namespace App\Entities\Check\Observers;

use App\Entities\Check\Models\Check;
use App\Entities\Transaction\Events\AddTransactionEvent;

class CheckObserver
{
    /**
     * Handle the check "created" event.
     *
     * @param  \App\Entities\Check\Models\Check  $check
     * @return void
     */
    public function created(Check $check)
    {
        $scores = ($check->amount / 100) * $check->shop->cashback;
        $scores = $scores > 1 ? round($scores) : 1;
        $mobileUser = $check->mobileUser;

        $mobileUser->update([
            'points_balance' => $mobileUser->points_balance + $scores,
            'total_points_accumulated' => $mobileUser->total_points_accumulated + $scores,
            'total_amount_of_all_checks' => $mobileUser->total_amount_of_all_checks + $check->amount,
        ]);

        event(new AddTransactionEvent($mobileUser, $check, 'check', $scores));
    }
}
