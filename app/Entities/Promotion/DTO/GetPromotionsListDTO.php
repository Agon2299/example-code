<?php


namespace App\Entities\Promotion\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class GetPromotionsListDTO extends DataTransferObject
{
    public int $start;
    public int $offset;
    public $onHome;
}
