<?php

namespace App\Entities\Category\Controllers;

use App\Base\BaseController;
use App\Entities\Category\FormRequests\CategoryBySlugRequest;
use App\Entities\Category\FormRequests\CategorySubcategoryRequest;
use App\Entities\Category\Resources\CategorySubcategoryListResource;
use App\Entities\Category\Services\CategoryService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends BaseController
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getList(): AnonymousResourceCollection
    {
        return $this->categoryService->getList();
    }

    public function getListSubcategories(CategorySubcategoryRequest $request): AnonymousResourceCollection
    {
        return $this->categoryService->getListSubcategories($request->requestToDto());
    }

    public function getCategoryBySlug(CategoryBySlugRequest $request)
    {
        return $this->categoryService->getCategoryBySlug($request->requestToDto());
    }
}
