<?php


namespace App\Entities\Event\Repositories;


use App\Base\BaseRepository;
use App\Entities\Event\Models\Event;
use Illuminate\Database\Eloquent\Collection;

class EventsRepository extends BaseRepository
{
    public function getById(string $eventId): Event
    {
        return Event::find($eventId);
    }

    public function getByIds($eventId)
    {
        return Event::find($eventId);
    }

    public function getList($onHome): Collection
    {
        $now = now();
        return Event::query()
            ->when(!is_null($onHome), static function ($q) use ($onHome) {
                return $q->where('on_home', $onHome);
            })
            ->where([
                ['publish_at', '<=', $now],
                ['end_at', '>=', $now],
            ])
            ->orderBy('priority')
            ->get();
    }
}
