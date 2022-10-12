<?php


namespace App\Entities\Shop\FormRequests;


use App\Base\BaseFormRequest;
use App\Entities\Shop\DTO\ShopListBySubcategoryDTO;
use App\Entities\Shop\DTO\ShopListDTO;

class ShopListBySubcategoryRequest extends BaseFormRequest
{

    public function requestToDto(): ShopListBySubcategoryDTO
    {
        return new ShopListBySubcategoryDTO([
            'start' => (int)$this->query('start', 0),
            'offset' => (int)$this->query('offset', 20),
            'subcategoryId' => $this->route('subcategoryId'),
        ]);
    }
}
