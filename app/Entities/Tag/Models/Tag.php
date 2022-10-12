<?php

namespace App\Entities\Tag\Models;

use App\Base\BaseModel;
use App\Common\Traits\HasThumbnail;
use App\Entities\Category\Models\Category;
use App\Entities\Event\Models\Event;
use App\Entities\News\Models\News;
use App\Entities\Promotion\Models\Promotion;
use App\Entities\Shop\Models\Shop;
use App\Entities\Subcategory\Models\Subcategory;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;

class Tag extends BaseModel
{
    protected $fillable = [
        'name'
    ];

    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class);
    }
}
