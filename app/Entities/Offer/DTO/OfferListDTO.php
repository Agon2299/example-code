<?php


namespace App\Entities\Offer\DTO;


use App\Base\BaseDataTransferObject;

class OfferListDTO extends BaseDataTransferObject
{
    public int $start;
    public int $offset;
    public $onHome;
    public $mobileUserId;
}
