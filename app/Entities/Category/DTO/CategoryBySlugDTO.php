<?php


namespace App\Entities\Category\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class CategoryBySlugDTO extends DataTransferObject
{
    public string $slug;
}
