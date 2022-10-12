<?php


namespace App\Entities\Check\Controllers;


use App\Base\BaseController;
use App\Entities\Check\FormRequests\GetCheckListRequest;
use App\Entities\Check\FormRequests\GetSingleCheckRequest;
use App\Entities\Check\Resources\CheckResource;
use App\Entities\Check\Services\CheckService;
use App\Entities\Event\FormRequests\GetEventsListRequest;
use App\Entities\Event\FormRequests\GetSingleEventRequest;
use App\Entities\Event\Resources\EventResource;
use App\Entities\Event\Services\EventsService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CheckController extends BaseController
{

    private CheckService $checkService;

    public function __construct(CheckService $checkService)
    {
        $this->checkService = $checkService;
    }

    public function getSingle(GetSingleCheckRequest $request): Response
    {
        try {
            return $this->success($this->checkService->getSingle($request->requestToDto()));
        } catch (\Exception $exception) {
            return $this->dataError(['error' => $exception->getMessage()], 403);
        }
    }

    public function getList(GetCheckListRequest $request): Response
    {
        try {
            return $this->success($this->checkService->getCheckList($request->requestToDto()));
        } catch (\Exception $exception) {
            return $this->dataError(['error' => $exception->getMessage()], 403);
        }
    }
}
