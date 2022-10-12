<?php

namespace App\Entities\MobileUser\FormRequests;

use App\Base\BaseFormRequest;
use App\Entities\MobileUser\DTO\AddOfferMobileUserDTO;

class AddOfferMobileUserRequest extends BaseFormRequest
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
        return new AddOfferMobileUserDTO([
            'mobileUserId' => $this->route('mobileUserID'),
            'offerId' => $this->route('offerId'),
            'token' => $this->header('token'),
        ]);
    }
}
