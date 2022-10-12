<?php

namespace App\Entities\Shop\Models;

use App\Base\BaseModel;
use App\Common\Traits\HasThumbnail;
use App\Entities\Cashbox\Models\Cashbox;
use App\Entities\Category\Models\Category;
use App\Entities\Event\Models\Event;
use App\Entities\Media\Resources\MediaListUrlImageResource;
use App\Entities\News\Models\News;
use App\Entities\Offer\Models\Offer;
use App\Entities\Promotion\Models\Promotion;
use App\Entities\Subcategory\Models\Subcategory;
use App\Entities\Tag\Models\Tag;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\MediaLibrary\HasMedia;

class Shop extends BaseModel implements HasMedia
{
    use Filterable;
    use HasThumbnail;
    use SoftDeletes;

    protected $fillable = [
        'id',
        'category_id',
        'cashbox_id',
        'name',
        'name_translate',
        'site_url',
        'description',
        'inn',
        'show_in_home_display',
        'cashback',
        'floor',
        'crm_id',
        'search_tags',
        'map',
    ];

    public function getDescriptionCustomAttribute()
    {
        return html_entity_decode($this->description);
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class, 'shop_id', 'id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function subcategories(): BelongsToMany
    {
        return $this->belongsToMany(Subcategory::class, 'shop_subcategory', 'shop_id', 'subcategory_id');
    }

    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function cashbox(): HasMany
    {
        return $this->hasMany(Cashbox::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnail')->singleFile();

        $this->addMediaCollection('images');
    }

    public function getImagesUrl(): AnonymousResourceCollection
    {
        return MediaListUrlImageResource::collection($this->getMedia('images'));
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function getCustomSubcategoryAttribute()
    {
        $names = $this->subcategories()->pluck('name', 'name');
        $nameArr = [];
        foreach ($names as $name) {
            $nameArr[] = $name;
        }

        return implode(', ', $nameArr);
    }

    public function getCustomCategoryAttribute()
    {
        $names = $this->categories()->pluck('name', 'name');
        $nameArr = [];
        foreach ($names as $name) {
            $nameArr[] = $name;
        }

        return implode(', ', $nameArr);
    }

    public function getTypeModelCampaignAttribute()
    {
        return 'shops';
    }
}
