<?php


namespace App\Entities\Check\Services;


use App\Base\BaseService;
use App\Entities\Check\DTO\CheckListDTO;
use App\Entities\Check\DTO\SingleCheckDTO;
use App\Entities\Check\Repositories\CheckRepository;
use App\Entities\Check\Resources\CheckResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection as AnonymousResourceCollectionAlias;

class CheckService extends BaseService
{

    protected CheckRepository $checkRepository;

    public function __construct(CheckRepository $checkRepository)
    {
        $this->checkRepository = $checkRepository;
    }

    public function getSingle(SingleCheckDTO $singleCheckDTO): CheckResource
    {
        return new CheckResource($this->checkRepository->getById($singleCheckDTO->mobileUserId, $singleCheckDTO->token, $singleCheckDTO->checkId));
    }

    /**
     * @param CheckListDTO $checkListDTO
     * @return AnonymousResourceCollectionAlias
     */
    public function getCheckList(CheckListDTO $checkListDTO): AnonymousResourceCollectionAlias
    {
        return CheckResource::collection(
            $this->checkRepository
                ->getList($checkListDTO->mobileUserId, $checkListDTO->token)
                ->splice($checkListDTO->start, $checkListDTO->offset)
        );
    }
}
