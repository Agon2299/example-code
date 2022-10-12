<?php


namespace App\Entities\Event\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class SingleEventDTO extends DataTransferObject
{
    public string $eventId;
    public $mobileUserId;
}
