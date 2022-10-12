<?php


namespace App\Entities\Event\Services;


use App\Base\BaseService;
use App\Entities\Event\DTO\EventsListDTO;
use App\Entities\Event\DTO\SingleEventDTO;
use App\Entities\Event\Repositories\EventsRepository;
use App\Entities\Event\Resources\EventResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection as AnonymousResourceCollectionAlias;

class EventsService extends BaseService
{

    protected EventsRepository $eventsRepository;

    public function __construct(EventsRepository $eventsRepository)
    {
        $this->eventsRepository = $eventsRepository;
    }

    public function getSingleEvent(SingleEventDTO $eventDTO): EventResource
    {
        return new EventResource($this->eventsRepository->getById($eventDTO->eventId));
    }

    /**
     * @param EventsListDTO $eventsListDTO
     * @return AnonymousResourceCollectionAlias
     */
    public function getEventsList(EventsListDTO $eventsListDTO): AnonymousResourceCollectionAlias
    {
        return EventResource::collection(
            $this->eventsRepository
                ->getList($eventsListDTO->onHome)
                ->splice($eventsListDTO->start, $eventsListDTO->offset)
        );
    }
}
