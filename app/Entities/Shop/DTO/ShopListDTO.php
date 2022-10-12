<?php


namespace App\Entities\Shop\DTO;


use App\Base\BaseDataTransferObject;

class ShopListDTO extends BaseDataTransferObject
{
    public string $name = '';
    public string $categoryId = '';
    public array $subcategoryIds = [];
    public int $start;
    public int $offset;
    public $tokenMobileUser;
}
