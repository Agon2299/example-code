<?php

namespace App\Entities\MobileUser\ActionsNova;

use App\User;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExportFile extends DownloadExcel implements WithMapping
{
    public function name()
    {
        return __('Экспорт данных');
    }


}
