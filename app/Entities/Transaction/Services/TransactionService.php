<?php


namespace App\Entities\Transaction\Services;


use App\Base\BaseService;
use App\Entities\Transaction\DTO\TransactionListDTO;
use App\Entities\Transaction\Repositories\TransactionRepository;
use App\Entities\Transaction\Resources\ListTransactionResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TransactionService extends BaseService
{
    private TransactionRepository $transactionRepository;

    /** @noinspection UnusedConstructorDependenciesInspection */
    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function getListTransactionsUsers(TransactionListDTO $transactionListDTO): AnonymousResourceCollection
    {
        return ListTransactionResource::collection($this->transactionRepository->getListTransactionsUsers($transactionListDTO->mobileUserId));
    }
}
