<?php

namespace App\Entities\Subcategory\Models;

use App\Base\BaseModel;
use App\Entities\SearchQuery\Models\SearchQuery;
use Illuminate\Database\Eloquent\Model;
use App\Entities\Category\Models\Category;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Entities\Shop\Models\Shop;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subcategory extends BaseModel
{
    protected $fillable = [
        'name',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class, 'shop_subcategory', 'subcategory_id', 'shop_id');
    }

    public function searchQueries(): BelongsToMany
    {
        return $this->belongsToMany(SearchQuery::class);
    }
}
