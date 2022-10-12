<?php

namespace App\Entities\MobileUser\FormRequests;

use App\Base\BaseFormRequest;
use App\Entities\MobileUser\DTO\AddCheckMobileUserDTO;
use App\Entities\MobileUser\DTO\AddReferralMobileUserDTO;
use App\Entities\MobileUser\DTO\LogoutMobileUserDTO;

class AddCheckMobileUser extends BaseFormRequest
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
        return new AddCheckMobileUserDTO([
            'mobileUserId' => $this->route('mobileUserID'),
            'token' => $this->header('token'),
            'amount' => $this->amount,
            'fss' => $this->fss,
            'number' => $this->number,
            'fiscalsign' => $this->fiscalsign,
            'purchaseDate' => $this->purchase_date,
            'cashback' => $this->cashback ?: 0,
        ]);
    }
}
