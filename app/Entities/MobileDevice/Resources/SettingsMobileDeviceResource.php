<?php


namespace App\Entities\MobileDevice\Resources;


use App\Base\BaseResource;

class SettingsMobileDeviceResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'install_app' => $this->install_app,
            'os' => $this->os,
            'version_os' => $this->version_os,
            'model' => $this->model,
            'mac_address' => $this->mac_address,
            'idfa' => $this->idfa,
            'enable_push_promotion' => $this->enable_push_promotion,
            'enable_push_event' => $this->enable_push_event,
            'enable_push_children' => $this->enable_push_children
        ];
    }
}
