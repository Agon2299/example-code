<?php


namespace App\Common\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait Uuid
{
    protected static function bootUuid() : void
    {
        static::creating(static function (Model $model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }
    public function getIncrementing() : bool
    {
        return false;
    }
    public function getKeyType() : string
    {
        return 'string';
    }
}
