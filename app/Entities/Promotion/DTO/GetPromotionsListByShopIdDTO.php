<?php


namespace App\Entities\Promotion\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class GetPromotionsListByShopIdDTO extends DataTransferObject
{
    public string $shopId;
    public int $start;
    public int $offset;
}
