<?php

namespace App\Entities\MobileUser\FormRequests;

use App\Base\BaseFormRequest;
use App\Entities\MobileUser\DTO\AddReferralMobileUserDTO;
use App\Entities\MobileUser\DTO\LogoutMobileUserDTO;

class AddReferralMobileUser extends BaseFormRequest
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
        return new AddReferralMobileUserDTO([
            'mobileUserId' => $this->route('mobileUserID'),
            'token' => $this->header('token'),
            'referralCode' => $this->referral_code,
        ]);
    }
}
