<?php


namespace App\Entities\SmsCode\Services;


use App\Base\BaseService;
use App\Entities\MobileUser\DTO\UpdateMobileUserDTO;
use App\Entities\MobileUser\Repositories\MobileUserRepository;
use App\Entities\SmsCode\Repositories\SmsCodeRepository;
use http\Exception;
use Illuminate\Support\Facades\DB;


class SmsCodeService extends BaseService
{
    private SmsCodeRepository $smsCodeRepository;

    public function __construct(SmsCodeRepository $smsCodeRepository)
    {
        $this->smsCodeRepository = $smsCodeRepository;
    }

    public function createOrUpdateSmsCode($userId, $smsCode) {
        return $this->smsCodeRepository->createOrUpdate($userId, $smsCode);
    }
}
