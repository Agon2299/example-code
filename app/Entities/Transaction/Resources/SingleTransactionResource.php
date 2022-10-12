<?php


namespace App\Entities\Transaction\Resources;


use App\Base\BaseResource;
use App\Entities\Promotion\Resources\PromotionShopResource;
use App\Entities\Shop\Models\Shop;

/**
 * Class NewsResource
 * @package App\Entities\News\Resources
 * @mixin Shop
 */
class SingleTransactionResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'change_balance' => (int)$this->change_balance,
            'type_transaction' => $this->type,
            'object' => $this->transactionstable,
            'type_object' => $this->transactionstable->typeModel ?? null,
            'created_at' => $this->created_at,
        ];
    }
}
