<?php

namespace App\Entities\MobileUser\FormRequests;

use App\Base\BaseFormRequest;
use App\Entities\MobileUser\DTO\AuthMobileUserDTO;

class AuthMobileUser extends BaseFormRequest
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
            'smsCode' => 'required',
            'phone' => 'required',
        ];
    }

    public function requestToDto()
    {
        return new AuthMobileUserDTO([
            'smsCode' => $this->smsCode,
            'phone' => $this->phone,
            'mobileDeviceId' => $this->mobileDeviceId,
        ]);
    }
}
