<?php


namespace App\Entities\Promotion\Controllers;


use App\Base\BaseController;
use App\Entities\Promotion\FormRequests\GetPromotionsListByShopIdRequest;
use App\Entities\Promotion\FormRequests\GetPromotionsListRequest;
use App\Entities\Promotion\FormRequests\GetSinglePromotionsRequest;
use App\Entities\Promotion\Resources\PromotionResource;
use App\Entities\Promotion\Services\PromotionsService;
use App\Entities\Shop\Models\Shop;

class PromotionsController extends BaseController
{

    private PromotionsService $promotionService;

    public function __construct(PromotionsService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    public function getSingle(GetSinglePromotionsRequest $request): PromotionResource
    {
        return $this->promotionService->getSinglePromotion($request->requestToDto());
    }

    public function getPromotionsListByShopId(GetPromotionsListByShopIdRequest $request)
    {

        $requestToDto = $request->requestToDto();
        if (!Shop::find($requestToDto->shopId)) {
            return $this->dataError(
                [
                    'error_message' => 'Устройство не зарегестрировано',
                    'error_code' => 10
                ],
                400
            );
        }

        return $this->promotionService->getPromotionsListByShopId($requestToDto);
    }

    public function getList(GetPromotionsListRequest $request)
    {
        return $this->promotionService->getList($request->requestToDto());
    }
}
