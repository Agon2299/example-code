<?php


namespace App\Entities\Offer\Repositories;


use App\Base\BaseRepository;
use App\Entities\Offer\Models\Offer;
use Illuminate\Support\Facades\DB;


class OfferRepository extends BaseRepository
{
    public function getList($mobileUserId, $onHome, $start, $limit)
    {
        $now = now();
        $offer = Offer::query();
        $offer->when($onHome, static function ($q) use ($onHome) {
            return $q->where('on_home', $onHome);
        });

        $offer->where(
            [
                ['start_at', '<=', $now],
                ['end_at', '>=', $now],
            ]
        );

        $offer->where('count', '>', function ($query) {
            $query->select(DB::raw('count(*)'))
                ->from('transactions')
                ->whereRaw('transactions.transactionstable_id = offers.id and transactions.deleted_at is NULL');
        });

        $offer->when($mobileUserId, static function ($query) use ($mobileUserId) {
            return $query->where(static function ($query) use ($mobileUserId) {
                return $query->where(static function ($query) use ($mobileUserId) {
                    return $query->whereNotExists(static function ($query) use ($mobileUserId) {
                        $query->select(DB::raw(1))
                            ->from('activated_offers')
                            ->whereRaw('activated_offers.offer_id = offers.id')
                            ->where('activated_offers.mobile_user_id', $mobileUserId);
                    })->where('type', 'welcome_offer');
                })->orWhere('type', '<>','welcome_offer');
            });
        });

        return $offer->orderBy('priority')->offset($start)->limit($limit)->get();
    }

    public function getSingle($offerId)
    {
        return Offer::find($offerId);
    }
}
