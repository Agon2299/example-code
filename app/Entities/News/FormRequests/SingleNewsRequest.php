<?php


namespace App\Entities\News\FormRequests;


use App\Base\BaseFormRequest;
use App\Entities\News\DTO\SingleNewsDTO;

class SingleNewsRequest extends BaseFormRequest
{

    public function requestToDto(): SingleNewsDTO
    {
        return new SingleNewsDTO([
            'newsId' => $this->route('newsId'),
        ]);
    }
}
