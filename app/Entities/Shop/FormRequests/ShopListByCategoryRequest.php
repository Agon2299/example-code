<?php


namespace App\Entities\Shop\FormRequests;


use App\Base\BaseFormRequest;
use App\Entities\Shop\DTO\ShopListByCategoryDTO;
use App\Entities\Shop\DTO\ShopListDTO;

class ShopListByCategoryRequest extends BaseFormRequest
{

    public function requestToDto(): ShopListByCategoryDTO
    {
        return new ShopListByCategoryDTO([
            'start' => (int)$this->query('start', 0),
            'offset' => (int)$this->query('offset', 20),
            'categoryId' => $this->route('categoryId'),
        ]);
    }
}
