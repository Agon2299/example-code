<?php

namespace App\Entities\Transaction\Controllers;

use App\Base\BaseController;
use App\Entities\MobileDevice\Models\MobileDevice;
use App\Entities\MobileUser\Models\MobileUser;
use App\Entities\Transaction\FormRequests\TransactionListRequest;
use App\Entities\Transaction\Services\TransactionService;

class TransactionController extends BaseController
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function getListTransactionsUsers(TransactionListRequest $request)
    {
        $transactionListDTO = $request->requestToDto();
        $mobileUser = MobileUser::find($transactionListDTO->mobileUserId);
        if (!$mobileUser) {
            return $this->dataError(
                [
                    'error_message' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        if ($mobileUser->remember_token != $transactionListDTO->token) {
            return $this->dataError(
                [
                    'error_message' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        return $this->success($this->transactionService->getListTransactionsUsers($transactionListDTO));
    }
}
