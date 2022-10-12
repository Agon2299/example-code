<?php


namespace App\Entities\Event\DTO;


use App\Base\BaseDataTransferObject;

class EventsListDTO extends BaseDataTransferObject
{
    public int $start;
    public int $offset;
    public $onHome;
    public $mobileUserId;
}
