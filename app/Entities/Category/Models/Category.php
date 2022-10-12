<?php

namespace App\Entities\Category\Models;

use App\Base\BaseModel;
use App\Entities\SearchQuery\Models\SearchQuery;
use App\Entities\Subcategory\Models\Subcategory;
use Illuminate\Database\Eloquent\Model;
use App\Entities\Shop\Models\Shop;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends BaseModel
{
    protected $fillable = [
        'name',
        'is_search',
        'slug',
        'has_filter',
    ];

    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class);
    }

    public function subcategories(): BelongsToMany
    {
        return $this->belongsToMany(Subcategory::class);
    }

    public function searchQueries(): HasMany
    {
        return $this->hasMany(SearchQuery::class);
    }
}
