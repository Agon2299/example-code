<?php


namespace App\Base;


use App\Common\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

abstract class BaseUserModel extends Authenticatable
{
    use Uuid;
}
