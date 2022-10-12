<?php

namespace App\Entities\MobileUser\FormRequests;

use App\Base\BaseFormRequest;
use App\Entities\MobileUser\DTO\UpdateMobileUserDTO;

class UpdateMobileUser extends BaseFormRequest
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
        return new UpdateMobileUserDTO([
            'mobileUserId' => $this->route('mobileUserID'),
            'data' => $this->all(),
            'token' => $this->token
        ]);
    }
}
