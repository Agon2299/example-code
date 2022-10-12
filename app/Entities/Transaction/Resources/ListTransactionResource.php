<?php


namespace App\Entities\Transaction\Resources;

use App\Base\BaseResource;


class ListTransactionResource extends BaseResource
{
    public function toArray($request)
    {
        $object = $this->transactionstable;
        $shop = $object && $object->shop ? $object->shop : false;

        return [
            'id' => $this->id,
            'change_balance' => (int)$this->change_balance,
            'type_transaction' => $this->type,
            'object' => $object,
            'type_object' => $object->typeModel ?? null,
            'created_at' => $this->created_at,
        ];
    }
}
