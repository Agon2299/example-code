<?php


namespace App\Entities\Offer\Controllers;


use App\Base\BaseController;
use App\Entities\MobileDevice\Models\MobileDevice;
use App\Entities\MobileDevice\Services\MobileDeviceService;
use App\Entities\MobileUser\FormRequests\AddEventMobileUserRequest;
use App\Entities\MobileUser\FormRequests\AddReferralMobileUser;
use App\Entities\MobileUser\FormRequests\AuthMobileUser;
use App\Entities\MobileUser\FormRequests\LoginMobileUser;
use App\Entities\MobileUser\FormRequests\LogoutMobileUser;
use App\Entities\MobileUser\FormRequests\UpdateMobileUser;
use App\Entities\MobileUser\Models\MobileUser;
use App\Entities\MobileUser\Services\MobileUserService;
use App\Entities\Offer\FormRequests\OfferListRequest;
use App\Entities\Offer\FormRequests\OfferSingleRequest;
use App\Entities\Offer\Services\OfferService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;


class OfferController extends BaseController
{

    private OfferService $offerService;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    public function getList(OfferListRequest $offerListRequest) {
        return $this->success($this->offerService->getList($offerListRequest->requestToDto()));
    }

    public function getSingle(OfferSingleRequest $offerSingleRequest)
    {
        return $this->success($this->offerService->getSingle($offerSingleRequest->requestToDto()));
    }
}
