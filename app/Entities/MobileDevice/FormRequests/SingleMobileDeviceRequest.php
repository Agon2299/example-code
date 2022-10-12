<?php

namespace App\Entities\MobileDevice\FormRequests;

use App\Base\BaseFormRequest;
use App\Entities\MobileDevice\DTO\SingleMobileDeviceDTO;

class SingleMobileDeviceRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

        ];
    }

    public function requestToDto()
    {
        return new SingleMobileDeviceDTO([
            'mobileDeviceId' => $this->route('mobileDeviceId')
        ]);
    }
}
