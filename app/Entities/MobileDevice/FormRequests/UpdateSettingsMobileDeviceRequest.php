<?php

namespace App\Entities\MobileDevice\FormRequests;

use App\Base\BaseFormRequest;
use App\Entities\MobileDevice\DTO\UpdateSettingsMobileDeviceDTO;

class UpdateSettingsMobileDeviceRequest extends BaseFormRequest
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
        return new UpdateSettingsMobileDeviceDTO([
            'mobileDeviceId' => $this->route('mobileDeviceId'),
            'enablePushPromotion' => $this->enable_push_promotion,
            'enablePushEvent' => $this->enable_push_event,
            'enablePushChildren' => $this->enable_push_children,
        ]);
    }
}
