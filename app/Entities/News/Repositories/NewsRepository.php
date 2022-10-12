<?php


namespace App\Entities\News\Repositories;


use App\Base\BaseRepository;
use App\Entities\News\Models\News;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class NewsRepository extends BaseRepository
{
    public function getListWithPromotions($onMain): Collection
    {
        $now = now();
        $isNotNull = !is_null($onMain);
        $promotionQuery = DB::table('promotions')
            ->select(['id', 'created_at', 'priority'])
            ->selectRaw('\'promotion\' as type')
            ->where(
                [
                    ['publish_start_at', '<=', $now],
                    ['end_at', '>=', $now],
                ]
            )
            ->when($isNotNull, static function ($query) use ($onMain) {
                $isMain = $onMain == 'true' ? true : false;
                $query->where('on_main', $isMain);
            })
            ->orderBy('priority');

        return DB::table('news')
            ->select(['id', 'created_at', 'priority'])
            ->selectRaw('\'news\' as type')
            ->where(
                [
                    ['publish_start_at', '<=', $now],
                    ['publish_end_at', '>=', $now],
                ]
            )
            ->when($isNotNull, static function ($query) use ($onMain) {
                $isMain = $onMain == 'true' ? true : false;
                $query->where('on_main', $isMain);
            })
            ->union($promotionQuery)
            ->orderBy('priority')
            ->get();
    }

    public function getById(string $newsId)
    {
        return News::whereId($newsId)->firstOrFail();
    }

    public function getByIds(array $ids = [])
    {
        return News::find($ids);
    }

    public function getWithPromotionsAndEvent($onMain): Collection
    {
        $now = now();
        $isNotNull = !is_null($onMain);
        $promotionQuery = DB::table('promotions')
            ->select(['id', 'created_at', 'title', 'priority'])
            ->selectRaw('\'promotion\' as type')
            ->where(
                [
                    ['publish_start_at', '<=', $now],
                    ['end_at', '>=', $now],
                ]
            )
            ->when($isNotNull, static function ($query) use ($onMain) {
                $isMain = $onMain == 'true' ? true : false;
                $query->where('on_main', $isMain);
            })
            ->orderBy('priority');

        $eventsQuery = DB::table('events')
            ->select(['id', 'created_at', 'title', 'priority'])
            ->selectRaw('\'event\' as type')
            ->where(
                [
                    ['start_at', '<=', $now],
                    ['end_at', '>=', $now],
                ]
            )->when($isNotNull, static function ($query) use ($onMain) {
                $isMain = $onMain == 'true' ? true : false;
                $query->where('on_main', $isMain);
            })
            ->orderBy('priority');

        return DB::table('news')
            ->select(['id', 'created_at', 'title', 'priority'])
            ->selectRaw('\'news\' as type')
            ->where(
                [
                    ['publish_start_at', '<=', $now],
                    ['publish_end_at', '>=', $now],
                ]
            )
            ->when($isNotNull, static function ($query) use ($onMain) {
                $isMain = $onMain == 'true' ? true : false;
                $query->where('on_main', $isMain);
            })
            ->unionAll($promotionQuery)
            ->unionAll($eventsQuery)
            ->orderBy('priority')
            ->get();
    }
}
