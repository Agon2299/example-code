<?php


namespace App\Entities\Category\FormRequests;


use App\Base\BaseFormRequest;
use App\Entities\Category\DTO\CategoryBySlugDTO;

class CategoryBySlugRequest extends BaseFormRequest
{

    public function requestToDto(): CategoryBySlugDTO
    {
        return new CategoryBySlugDTO([
            'slug' => $this->route('slug'),
        ]);
    }
}
