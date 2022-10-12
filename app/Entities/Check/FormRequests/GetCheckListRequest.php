<?php


namespace App\Entities\Check\FormRequests;


use App\Base\BaseFormRequest;
use App\Entities\Check\DTO\CheckListDTO;
use App\Entities\Event\DTO\EventsListDTO;
use App\Entities\Event\DTO\SingleEventDTO;
use App\Entities\News\DTO\NewsListDTO;

/**
 * Class GetSingleEventsRequest
 * @package App\Entities\Event\FormRequests
 */
class GetCheckListRequest extends BaseFormRequest
{
    public function requestToDto(): CheckListDTO
    {
        return new CheckListDTO([
            'start' => (int)$this->query('start', 0),
            'offset' => (int)$this->query('offset', 15),
            'mobileUserId' => $this->route('mobileUserID'),
            'token' => $this->header('token'),
        ]);
    }
}
