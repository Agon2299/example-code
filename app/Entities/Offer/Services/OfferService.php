<?php


namespace App\Entities\Offer\Services;


use App\Base\BaseService;
use App\Entities\Offer\DTO\OfferListDTO;
use App\Entities\Offer\DTO\OfferSingleDTO;
use App\Entities\Offer\Repositories\OfferRepository;
use App\Entities\Offer\Resources\ListOfferResource;
use App\Entities\Offer\Resources\SingleOfferResource;


class OfferService extends BaseService
{
    private OfferRepository $offerRepository;

    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    public function getList(OfferListDTO $offerListDTO)
    {
        return ListOfferResource::collection(
            $this->offerRepository->getList(
                $offerListDTO->mobileUserId,
                $offerListDTO->onHome,
                $offerListDTO->start,
                $offerListDTO->offset
            )
        );
    }

    public function getSingle(OfferSingleDTO $offerSingleDTO)
    {
        $offer = $this->offerRepository->getSingle($offerSingleDTO->offerId);
        return $offer ? new SingleOfferResource($offer) : [];
    }
}
