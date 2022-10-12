<?php

namespace App\Entities\Transaction\Repositories;

use App\Base\BaseRepository;
use App\Entities\Category\Models\Category;
use App\Entities\Shop\Models\Shop;
use App\Entities\Subcategory\Models\Subcategory;
use App\Entities\Transaction\Models\Transaction;

class TransactionRepository extends BaseRepository
{
    public function getListTransactionsUsers($mobileUserId)
    {
        return Transaction::where('mobile_user_id', $mobileUserId)->get() ?: [];
    }

    public function addTransaction($mobileUserFrom, $mobileUserTo, $referralType, $changeBalance): void
    {
        $transaction = Transaction::create([
            'mobile_user_id' => $mobileUserFrom->id,
            'change_balance' => $changeBalance,
            'type' => $referralType,
        ]);

        $transaction->transactionstable()->associate($mobileUserTo)->save();
    }
}
