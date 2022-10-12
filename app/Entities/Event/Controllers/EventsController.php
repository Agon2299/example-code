<?php


namespace App\Entities\Event\Controllers;


use App\Base\BaseController;
use App\Entities\Event\FormRequests\GetEventsListRequest;
use App\Entities\Event\FormRequests\GetSingleEventRequest;
use App\Entities\Event\Resources\EventResource;
use App\Entities\Event\Services\EventsService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventsController extends BaseController
{

    private EventsService $eventsService;

    public function __construct(EventsService $eventsService)
    {
        $this->eventsService = $eventsService;
    }

    public function getSingle(GetSingleEventRequest $request): EventResource
    {
        return $this->eventsService->getSingleEvent($request->requestToDto());
    }

    public function getList(GetEventsListRequest $request): AnonymousResourceCollection
    {
        return $this->eventsService->getEventsList($request->requestToDto());
    }
}
