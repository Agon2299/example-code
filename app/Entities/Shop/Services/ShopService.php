<?php


namespace App\Entities\Shop\Services;


use App\Base\BaseService;
use App\Entities\News\DTO\SingleNewsDTO;
use App\Entities\News\Repositories\NewsRepository;
use App\Entities\SearchQuery\Repositories\SearchQueryRepository;
use App\Entities\Shop\DTO\ShopListByCategoryDTO;
use App\Entities\Shop\DTO\ShopListByCategorySlugDTO;
use App\Entities\Shop\DTO\ShopListBySubcategoryDTO;
use App\Entities\Shop\DTO\ShopListDTO;
use App\Entities\Shop\DTO\SingleShopDTO;
use App\Entities\Shop\Repositories\ShopRepository;
use App\Entities\Shop\Resources\ListShopByCategorySlugResource;
use App\Entities\Shop\Resources\ListShopResource;
use App\Entities\Shop\Resources\SingleShopResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ShopService extends BaseService
{
    private ShopRepository $shopRepository;
    private SearchQueryRepository $searchQueryRepository;

    /** @noinspection UnusedConstructorDependenciesInspection */
    public function __construct(ShopRepository $shopRepository, SearchQueryRepository $searchQueryRepository)
    {
        $this->shopRepository = $shopRepository;
        $this->searchQueryRepository = $searchQueryRepository;
    }

    public function getSingleShop(SingleShopDTO $singleShopDTO): SingleShopResource
    {
        return new SingleShopResource($this->shopRepository->getById($singleShopDTO->shopId));
    }

    public function getList(ShopListDTO $shopSearchDTO)
    {
        if (!empty($shopSearchDTO->name)) {
            $this->searchQueryRepository->save(
                $shopSearchDTO->name,
                $shopSearchDTO->tokenMobileUser,
                $shopSearchDTO->categoryId,
                $shopSearchDTO->subcategoryIds
            );
        }

        return ListShopResource::collection(
            $this->shopRepository->getList(
                $shopSearchDTO->name,
                $shopSearchDTO->categoryId,
                $shopSearchDTO->subcategoryIds,
                $shopSearchDTO->start,
                $shopSearchDTO->offset
            )
        );
    }

    public function getByCategory(ShopListByCategoryDTO $shopListByCategoryDTO)
    {
        return ListShopResource::collection(
            $this->shopRepository->getByCategory($shopListByCategoryDTO->categoryId)
        )->slice($shopListByCategoryDTO->start, $shopListByCategoryDTO->offset);
    }

    public function getBySubcategory(ShopListBySubcategoryDTO $shopListBySubcategoryDTO): AnonymousResourceCollection
    {
        return ListShopResource::collection(
            $this->shopRepository->getBySubcategory($shopListBySubcategoryDTO->subcategoryId)
                ->slice($shopListBySubcategoryDTO->start, $shopListBySubcategoryDTO->offset)
        );
    }

    public function getByCategorySlug(ShopListByCategorySlugDTO $shopListByCategorySlugDTO): AnonymousResourceCollection
    {
        return ListShopByCategorySlugResource::collection(
            $this->shopRepository->getByCategorySlug($shopListByCategorySlugDTO->slug, $shopListByCategorySlugDTO->onHome)
                ->slice($shopListByCategorySlugDTO->start, $shopListByCategorySlugDTO->offset)
        );
    }
}
