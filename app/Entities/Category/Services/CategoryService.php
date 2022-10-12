<?php


namespace App\Entities\Category\Services;


use App\Base\BaseService;
use App\Entities\Category\DTO\CategoryBySlugDTO;
use App\Entities\Category\DTO\CategorySubcategoryDTO;
use App\Entities\Category\Repositories\CategoryRepository;
use App\Entities\Category\Resources\CategoryBySlugResource;
use App\Entities\Category\Resources\CategoryListResource;
use App\Entities\Category\Resources\CategorySubcategoryListResource;
use App\Entities\News\Resources\NewsCollectionResource;
use App\Entities\News\Resources\NewsResource;
use App\Entities\Subcategory\Resources\SubcategoryListResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection as AnonymousResourceCollectionAlias;

class CategoryService extends BaseService
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return AnonymousResourceCollectionAlias
     */
    public function getList(): AnonymousResourceCollectionAlias
    {
        return CategoryListResource::collection($this->categoryRepository->getList());
    }

    public function getListSubcategories(CategorySubcategoryDTO $categorySubcategoryDTO): AnonymousResourceCollectionAlias
    {
        return SubcategoryListResource::collection($this->categoryRepository->getListSubcategory($categorySubcategoryDTO->categoryId));
    }

    public function getCategoryBySlug(CategoryBySlugDTO $categoryBySlugDTO)
    {
        $categoryBySlugResource = $this->categoryRepository->getCategoryBySlug($categoryBySlugDTO->slug);
        if ($categoryBySlugResource) {
            return new CategoryBySlugResource($this->categoryRepository->getCategoryBySlug($categoryBySlugDTO->slug));
        }

        return [];
    }
}
