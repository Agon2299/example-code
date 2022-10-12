<?php


namespace App\Entities\MobileUser\Resources;


use App\Base\BaseResource;

class GetBalanceMobileUserResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'phone' => $this->phone,
            'name' => $this->name,
            'email' => $this->email,
            'points_balance' => $this->points_balance,
            'remember_token' => $this->remember_token,
        ];
    }
}
