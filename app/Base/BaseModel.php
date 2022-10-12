<?php


namespace App\Base;


use App\Common\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use Uuid;
}
