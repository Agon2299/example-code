<?php


namespace App\Common\Traits;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Trait HasThumbnail
 * @package App\Common\Traits
 */
trait HasThumbnail
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnail')->singleFile();
    }

    public function getThumbnailUrl(): string
    {
        return $this->getFirstMediaUrl('thumbnail');
    }
}
