<?php


namespace App\Entities\Shop\FormRequests;


use App\Base\BaseFormRequest;
use App\Entities\Shop\DTO\ShopListDTO;

class ShopListRequest extends BaseFormRequest
{

    public function requestToDto(): ShopListDTO
    {
        return new ShopListDTO([
            'name' => $this->query('name'),
            'categoryId' => $this->query('categoryId'),
            'subcategoryIds' => $this->query('subcategoryIds', []),
            'start' => (int)$this->query('start', 0),
            'offset' => (int)$this->query('offset', 15),
            'tokenMobileUser' => $this->header('token'),
        ]);
    }
}
