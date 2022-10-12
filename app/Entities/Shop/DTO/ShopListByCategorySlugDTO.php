<?php


namespace App\Entities\Shop\DTO;


use App\Base\BaseDataTransferObject;

class ShopListByCategorySlugDTO extends BaseDataTransferObject
{
    public int $start;
    public int $offset;
    public string $slug;
    public $onHome;
}
