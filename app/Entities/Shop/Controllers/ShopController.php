<?php

namespace App\Entities\Shop\Controllers;

use App\Base\BaseController;
use App\Entities\Category\Models\Category;
use App\Entities\Shop\FormRequests\ShopListByCategoryRequest;
use App\Entities\Shop\FormRequests\ShopListByCategorySlugRequest;
use App\Entities\Shop\FormRequests\ShopListBySubcategoryRequest;
use App\Entities\Shop\FormRequests\ShopListRequest;
use App\Entities\Shop\FormRequests\SingleShopRequest;
use App\Entities\Shop\Resources\SingleShopResource;
use App\Entities\Shop\Services\ShopService;

class ShopController extends BaseController
{
    private ShopService $shopService;

    public function __construct(ShopService $shopService)
    {
        $this->shopService = $shopService;
    }

    public function getList(ShopListRequest $request)
    {
        return $this->shopService->getList($request->requestToDto());
    }

    public function getSingle(SingleShopRequest $request): SingleShopResource
    {
        return $this->shopService->getSingleShop($request->requestToDto());
    }

    public function getByCategory(ShopListByCategoryRequest $request)
    {
        return $this->shopService->getByCategory($request->requestToDto());
    }

    public function getBySubcategory(ShopListBySubcategoryRequest $request)
    {
        return $this->shopService->getBySubcategory($request->requestToDto());
    }

    public function getByCategorySlug(ShopListByCategorySlugRequest $request)
    {
        $requestToDto = $request->requestToDto();
        if (!Category::where('slug', $requestToDto->slug)->first()) {
            return $this->dataError([
                'error_message' => 'Категория не найдена'
            ], 400);
        }

        return $this->shopService->getByCategorySlug($request->requestToDto());
    }
}
