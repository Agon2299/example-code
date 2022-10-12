<?php

namespace App\Entities\Shop\Repositories;

use App\Base\BaseRepository;
use App\Entities\Category\Models\Category;
use App\Entities\Shop\Models\Shop;
use App\Entities\Subcategory\Models\Subcategory;

class ShopRepository extends BaseRepository
{
    public function getById(string $shopId)
    {
        return Shop::findOrFail($shopId);
    }

    public function getList(string $name, string $categoryId, array $subcategoryIds, $offset, $limit)
    {
        $shop = Shop::query();

        $shop->when($name, static function ($q) use ($name) {
            return $q->where(static function ($query) use ($name) {
                $query->where('name', 'ilike', '%' . $name . '%')
                    ->orWhere('name_translate', 'ilike', '%' . $name . '%')
                    ->orWhereHas('tags', static function ($q) use ($name) {
                        $q->where('tags.name', 'ilike', '%' . $name . '%');
                    });
            });
        });

        $shop->when($categoryId, static function ($q) use ($categoryId) {
            return $q->whereHas('categories', static function ($q) use ($categoryId) {
                return $q->where('categories.id', $categoryId);
            });
        });

        $shop->when($subcategoryIds, static function ($q) use ($subcategoryIds) {
            return $q->whereHas('subcategories', static function ($q) use ($subcategoryIds) {
                return $q->whereIn('subcategories.id', $subcategoryIds);
            });
        });

        return $shop->offset($offset)->limit($limit)->orderBy('name')->get();
    }

    public function getByCategory($categoryId)
    {
        return Category::where('id', $categoryId)->first()->shops()->orderBy('name')->get() ?? [];
    }

    public function getBySubcategory($subcategoryId)
    {
        return Subcategory::where('id', $subcategoryId)->first()->shops()->get();
    }

    public function getByCategorySlug($slug, $onHome)
    {
        return Category::where('slug', $slug)
            ->first()
            ->shops()
            ->when(!is_null($onHome), static function ($query) use ($onHome) {
                $query->where('show_in_home_display', $onHome);
            })
            ->orderBy('name')
            ->get();
    }
}
