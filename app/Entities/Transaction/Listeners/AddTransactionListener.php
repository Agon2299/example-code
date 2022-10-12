<?php

namespace App\Entities\Transaction\Listeners;

use App\Entities\Transaction\Repositories\TransactionRepository;
use App\Entities\Transaction\Services\TransactionService;

class AddTransactionListener
{

    public TransactionRepository $transactionRepository;

    /**
     * Create the event listener.
     *
     * @param TransactionService $transactionService
     */
    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $this->transactionRepository->addTransaction(
            $event->mobileUserFrom,
            $event->objectTo,
            $event->referralType,
            $event->changeBalance
        );
    }
}
