<?php


namespace App\Entities\Category\Repositories;


use App\Base\BaseRepository;
use App\Entities\Category\Models\Category;
use App\Entities\News\DTO\NewsListDTO;
use App\Entities\News\Models\News;
use App\Entities\Promotion\Models\Promotion;
use App\Entities\Promotion\Repositories\PromotionsRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CategoryRepository extends BaseRepository
{
    public function getList()
    {
        return Category::all();
    }

    public function getListSubcategory($categoryId)
    {
        $category = Category::find($categoryId);
        return $category ? $category->subcategories()->orderby('name')->get() : [];
    }

    public function getCategoryBySlug($slug)
    {
        return Category::where('slug', $slug)->first();
    }
}
