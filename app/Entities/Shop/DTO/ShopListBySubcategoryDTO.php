<?php


namespace App\Entities\Shop\DTO;


use App\Base\BaseDataTransferObject;

class ShopListBySubcategoryDTO extends BaseDataTransferObject
{
    public int $start;
    public int $offset;
    public string $subcategoryId;
}
