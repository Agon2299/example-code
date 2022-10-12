<?php


namespace App\Entities\Shop\FormRequests;


use App\Base\BaseFormRequest;
use App\Entities\Shop\DTO\ShopListByCategorySlugDTO;

class ShopListByCategorySlugRequest extends BaseFormRequest
{

    public function requestToDto(): ShopListByCategorySlugDTO
    {
        return new ShopListByCategorySlugDTO([
            'start' => (int)$this->query('start', 0),
            'offset' => (int)$this->query('offset', 15),
            'slug' => $this->route('slug'),
            'onHome' => $this->query('on_home')
        ]);
    }
}
