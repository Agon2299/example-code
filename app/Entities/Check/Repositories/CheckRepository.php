<?php


namespace App\Entities\Check\Repositories;


use App\Base\BaseRepository;
use App\Entities\Check\Models\Check;
use App\Entities\Event\Models\Event;
use App\Entities\MobileUser\Models\MobileUser;
use Illuminate\Database\Eloquent\Collection;

class CheckRepository extends BaseRepository
{
    public function getById(string $mobileUserId, string $token, string $checkId)
    {
        $mobileUser = MobileUser::find($mobileUserId);
        if (!$mobileUser) {
            throw new \RuntimeException('User not found');
        }

        if ($mobileUser->remember_token !== $token) {
            throw new \RuntimeException('Token error');
        }

        $check = Check::find($checkId);

        if (!$check) {
            throw new \RuntimeException('Check not found');
        }

        if ($check->mobile_user_id !== $mobileUserId) {
            throw new \RuntimeException('This check is not this user');
        }

        return $check;
    }

    public function getList(string $mobileUserId, string $token): Collection
    {
        $mobileUser = MobileUser::find($mobileUserId);
        if (!$mobileUser) {
            throw new \RuntimeException('Offer not active');
        }

        if ($mobileUser->remember_token !== $token) {
            throw new \RuntimeException('Offer not active');
        }

        return $mobileUser->checks;
    }
}
