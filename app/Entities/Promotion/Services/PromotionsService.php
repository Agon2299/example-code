<?php


namespace App\Entities\Promotion\Services;


use App\Base\BaseService;
use App\Entities\Promotion\DTO\GetPromotionsListByShopIdDTO;
use App\Entities\Promotion\DTO\GetPromotionsListDTO;
use App\Entities\Promotion\DTO\SinglePromotionDTO;
use App\Entities\Promotion\Repositories\PromotionsRepository;
use App\Entities\Promotion\Resources\PromotionResource;

class PromotionsService extends BaseService
{

    protected PromotionsRepository $promotionsRepository;

    public function __construct(PromotionsRepository $promotionsRepository)
    {
        $this->promotionsRepository = $promotionsRepository;
    }

    public function getSinglePromotion(SinglePromotionDTO $promotionDTO): PromotionResource
    {
        return new PromotionResource($this->promotionsRepository->getById($promotionDTO->promotionId));
    }

    public function getPromotionsListByShopId(GetPromotionsListByShopIdDTO $getPromotionsListByShopIdDTO)
    {
        return PromotionResource::collection(
            $this->promotionsRepository->getPromotionsListByShopId(
                $getPromotionsListByShopIdDTO->shopId,
                $getPromotionsListByShopIdDTO->start,
                $getPromotionsListByShopIdDTO->offset
            )
        );
    }

    public function getList(GetPromotionsListDTO $getPromotionsListDTO)
    {
        return PromotionResource::collection(
            $this->promotionsRepository->getList(
                $getPromotionsListDTO->onHome,
                $getPromotionsListDTO->start,
                $getPromotionsListDTO->offset
            )
        );
    }
}
