<?php


namespace App\Entities\News\FormRequests;


use App\Base\BaseFormRequest;
use App\Entities\News\DTO\NewsListDTO;

class NewsWithPromotionsListRequest extends BaseFormRequest
{

    public function requestToDto(): NewsListDTO
    {
        return new NewsListDTO([
            'start' => (int)$this->query('start', 0),
            'offset' => (int)$this->query('offset', 15),
            'onMain' => $this->query('on_main'),
            'mobileUserId' => $this->query('mobileUserId'),
        ]);
    }
}
