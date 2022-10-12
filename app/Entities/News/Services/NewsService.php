<?php


namespace App\Entities\News\Services;


use App\Base\BaseService;
use App\Entities\Event\Repositories\EventsRepository;
use App\Entities\News\DTO\NewsListDTO;
use App\Entities\News\DTO\SingleNewsDTO;
use App\Entities\News\Repositories\NewsRepository;
use App\Entities\News\Resources\EventsPromotionsNewsListResource;
use App\Entities\News\Resources\NewsListResource;
use App\Entities\News\Resources\SingleNewsResource;
use App\Entities\Promotion\Repositories\PromotionsRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection as AnonymousResourceCollectionAlias;
use Illuminate\Support\Collection;

class NewsService extends BaseService
{

    const TYPE_MAPPING = [
        'news' => 'newsRepository',
        'promotion' => 'promotionsRepository',
        'event' => 'eventsRepository',
    ];

    private NewsRepository $newsRepository;
    private PromotionsRepository $promotionsRepository;
    private EventsRepository $eventsRepository;

    /** @noinspection UnusedConstructorDependenciesInspection */
    public function __construct(NewsRepository $newsRepository, PromotionsRepository $promotionsRepository, EventsRepository $eventsRepository)
    {
        $this->newsRepository = $newsRepository;
        $this->promotionsRepository = $promotionsRepository;
        $this->eventsRepository = $eventsRepository;
    }

    /**
     * @param NewsListDTO $listDTO
     * @return AnonymousResourceCollectionAlias
     */
    public function getListWithPromotions(NewsListDTO $listDTO): AnonymousResourceCollectionAlias
    {
        $newsPromotionsList = $this->newsRepository->getListWithPromotions($listDTO->onMain)->groupBy('type')->map(function (Collection $collection, string $key) {
            $repository = self::TYPE_MAPPING[$key];
            return $this->{$repository}->getByIds($collection->pluck('id')->toArray());
        })->flatten()->sortByDesc('created_at')->sortBy('priority')->splice($listDTO->start, $listDTO->offset);

        return NewsListResource::collection($newsPromotionsList);
    }

    public function getSingleNews(SingleNewsDTO $singleNewsDTO): SingleNewsResource
    {
        return new SingleNewsResource($this->newsRepository->getById($singleNewsDTO->newsId));
    }

    public function getWithPromotionsAndEvent(NewsListDTO $listDTO): AnonymousResourceCollectionAlias
    {
        return EventsPromotionsNewsListResource::collection($this->newsRepository
            ->getWithPromotionsAndEvent($listDTO->onMain)
            ->groupBy('type')
            ->map(function (Collection $collection, string $key) {
                $repository = self::TYPE_MAPPING[$key];
                return $this->{$repository}->getByIds($collection->pluck('id')->toArray());
            })
            ->flatten()
            ->sortByDesc('created_at')
            ->sortBy('priority')
            ->splice($listDTO->start, $listDTO->offset)
        );
    }
}
