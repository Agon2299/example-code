<?php


namespace App\Entities\Category\FormRequests;


use App\Base\BaseFormRequest;
use App\Entities\Category\DTO\CategorySubcategoryDTO;
use App\Entities\News\DTO\SingleNewsDTO;

class CategorySubcategoryRequest extends BaseFormRequest
{

    public function requestToDto(): CategorySubcategoryDTO
    {
        return new CategorySubcategoryDTO([
            'categoryId' => $this->route('categoryId'),
        ]);
    }
}
