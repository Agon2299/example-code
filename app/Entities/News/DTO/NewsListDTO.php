<?php


namespace App\Entities\News\DTO;


use App\Base\BaseDataTransferObject;

class NewsListDTO extends BaseDataTransferObject
{
    public int $start;
    public int $offset;
    public $onMain;
    public $mobileUserId;
}
