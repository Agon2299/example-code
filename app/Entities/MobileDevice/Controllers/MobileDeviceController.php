<?php


namespace App\Entities\MobileDevice\Controllers;


use App\Base\BaseController;
use App\Entities\Campaign\Models\Campaign;
use App\Entities\MobileDevice\FormRequests\AddTokenMobileDeviceRequest;
use App\Entities\MobileDevice\FormRequests\GetSettingsMobileDeviceRequest;
use App\Entities\MobileDevice\FormRequests\SingleMobileDeviceRequest;
use App\Entities\MobileDevice\FormRequests\StoreMobileDeviceRequest;
use App\Entities\MobileDevice\FormRequests\UpdateSettingsMobileDeviceRequest;
use App\Entities\MobileDevice\Models\MobileDevice;
use App\Entities\MobileDevice\Notifications\Android;
use App\Entities\MobileDevice\Notifications\Ios;
use App\Entities\MobileDevice\Services\MobileDeviceService;
use Illuminate\Http\Response;


class MobileDeviceController extends BaseController
{

    private MobileDeviceService $mobileDeviceService;

    public function __construct(MobileDeviceService $mobileDeviceService)
    {
        $this->mobileDeviceService = $mobileDeviceService;
    }

    public function store(StoreMobileDeviceRequest $request)
    {
        if (empty($request->idfa) || $request->idfa === '00000000-0000-0000-0000-000000000000') {
            return $this->created($this->mobileDeviceService->store($request->requestToDto()));
        }

        $mobileDevice = MobileDevice::where('idfa', $request->idfa)->first();
        if ($mobileDevice) {
            return $this->success(['id' => $mobileDevice->id]);
        }

        return $this->created($this->mobileDeviceService->store($request->requestToDto()));
    }

    public function addToken(AddTokenMobileDeviceRequest $request)
    {
        $requestToDto = $request->requestToDto();
        if (!MobileDevice::find($requestToDto->mobileDeviceId)) {
            return $this->dataError(
                [
                    'error_message' => 'Устройство не зарегестрировано',
	                'error_code' => 10
                ],
                400
            );
        }

        return $this->created($this->mobileDeviceService->addToken($requestToDto));
    }

    public function updateSettings(UpdateSettingsMobileDeviceRequest $request): Response
    {
        $requestToDto = $request->requestToDto();
        if (!MobileDevice::find($requestToDto->mobileDeviceId)) {
            return $this->dataError(
                [
                    'error_message' => 'Устройство не зарегестрировано',
                    'error_code' => 10
                ],
                400
            );
        }

        return $this->success($this->mobileDeviceService->updateSettings($requestToDto));
    }

    public function getSingle(SingleMobileDeviceRequest $request)
    {
        return $this->success($this->mobileDeviceService->getSingle($request->requestToDto()));
    }
}
