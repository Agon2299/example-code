<?php


namespace App\Entities\Promotion\Repositories;


use App\Base\BaseRepository;
use App\Entities\Promotion\Models\Promotion;
use App\Entities\Shop\Models\Shop;

class PromotionsRepository extends BaseRepository
{
    public function getById(string $promotionId): Promotion
    {
        return Promotion::whereId($promotionId)->firstOrFail();
    }

    public function getByIds(array $ids = [])
    {
        return Promotion::find($ids);
    }

    public function getPromotionsListByShopId($shopId, $start, $offset)
    {
        $now = now();
        $shop = Shop::find($shopId);
        return $shop->promotions
            ->where('publish_start_at', '<', $now)
            ->where('publish_end_at', '>', $now)
            ->orderBy('priority')
            ->splice($start, $offset);
    }

    public function getList($onHome, $start, $offset)
    {
        $now = now();

        return Promotion::query()
            ->where(
                [
                    ['publish_start_at', '<=', $now],
                    ['end_at', '>=', $now],
                ]
            )
            ->when(!is_null($onHome), static function ($q) use ($onHome) {
                $isHome = $onHome == 'true' ? true : false;
                return $q->where('on_home', $isHome);
            })
            ->orderBy('priority')
            ->offset($start)
            ->limit($offset)
            ->get();
    }
}
