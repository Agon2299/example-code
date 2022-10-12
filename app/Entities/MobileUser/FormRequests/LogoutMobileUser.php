<?php

namespace App\Entities\MobileUser\FormRequests;

use App\Base\BaseFormRequest;
use App\Entities\MobileUser\DTO\LogoutMobileUserDTO;

class LogoutMobileUser extends BaseFormRequest
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
            'token' => 'required',
        ];
    }

    public function requestToDto()
    {
        return new LogoutMobileUserDTO([
            'mobileUserId' => $this->route('mobileUserID'),
            'mobileDeviceId' => $this->mobileDeviceId,
            'token' => $this->token
        ]);
    }
}
