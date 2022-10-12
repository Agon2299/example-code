<?php


namespace App\Entities\News\Controllers;


use App\Base\BaseController;
use App\Entities\News\FormRequests\NewsWithPromotionsListRequest;
use App\Entities\News\FormRequests\SingleNewsRequest;
use App\Entities\News\Resources\NewsListResource;
use App\Entities\News\Resources\SingleNewsResource;
use App\Entities\News\Services\NewsService;

class NewsController extends BaseController
{

    private NewsService $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function getWithPromotions(NewsWithPromotionsListRequest $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return $this->newsService->getListWithPromotions($request->requestToDto());
    }

    public function getSingle(SingleNewsRequest $request): SingleNewsResource
    {
        return $this->newsService->getSingleNews($request->requestToDto());
    }

    public function getWithPromotionsAndEvent(NewsWithPromotionsListRequest $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return $this->newsService->getWithPromotionsAndEvent($request->requestToDto());
    }
}
