<?php


namespace App\Entities\Media\Models;

use App\Common\Traits\Uuid;


/**
 * App\Entities\Media\Models\Media
 *
 * @property string $id
 * @property string $model_type
 * @property string $model_id
 * @property string|null $uuid
 * @property string $collection_name
 * @property string $name
 * @property string $file_name
 * @property string|null $mime_type
 * @property string $disk
 * @property string|null $conversions_disk
 * @property int $size
 * @property array $manipulations
 * @property array $custom_properties
 * @property array $responsive_images
 * @property int|null $order_column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $extension
 * @property-read mixed $human_readable_size
 * @property-read mixed $type
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $model
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Media\Models\Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Media\Models\Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\MediaLibrary\MediaCollections\Models\Media ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Media\Models\Media query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Media\Models\Media whereCollectionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Media\Models\Media whereConversionsDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Media\Models\Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Media\Models\Media whereCustomProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Media\Models\Media whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Media\Models\Media whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Media\Models\Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Media\Models\Media whereManipulations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Media\Models\Media whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Media\Models\Media whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Media\Models\Media whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Media\Models\Media whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Media\Models\Media whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Media\Models\Media whereResponsiveImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Media\Models\Media whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Media\Models\Media whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entities\Media\Models\Media whereUuid($value)
 * @mixin \Eloquent
 */
class Media extends \Spatie\MediaLibrary\MediaCollections\Models\Media
{
    use Uuid;
}
