<?php


namespace App\Entities\Check\FormRequests;


use App\Base\BaseFormRequest;
use App\Entities\Check\DTO\SingleCheckDTO;
use App\Entities\Event\DTO\SingleEventDTO;

/**
 * Class GetSingleEventsRequest
 * @package App\Entities\Event\FormRequests
 */
class GetSingleCheckRequest extends BaseFormRequest
{
    public function requestToDto(): SingleCheckDTO
    {
        return new SingleCheckDTO([
            'mobileUserId' => $this->route('mobileUserID'),
            'token' => $this->header('token'),
            'checkId' => $this->route('checkId'),
        ]);
    }
}
