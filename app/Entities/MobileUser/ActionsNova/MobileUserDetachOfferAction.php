<?php

namespace App\Entities\MobileUser\ActionsNova;

use App\Entities\Transaction\Events\AddTransactionEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class MobileUserDetachOfferAction extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The text to be used for the action's confirm button.
     *
     * @var string
     */
    public $confirmButtonText = 'Вернуть баллы';

    /**
     * The text to be used for the action's cancel button.
     *
     * @var string
     */
    public $cancelButtonText = 'Отмена';

    /**
     * The text to be used for the action's confirmation text.
     *
     * @var string
     */
    public $confirmText = 'Уверены что хотите забрать у данного пользователя данный подарок?';

    public $runCallback = true;

    public function name()
    {
        return __('Забрать подарок');
    }

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $meta = $this->meta();
        $offer_id = $meta['offer_id'];
        foreach ($models as $model) {
            $transaction = $model->transactionsMobileUser()->where('transactionstable_id', $offer_id)->latest()->first();
            event(new AddTransactionEvent($model, null, 'refund_offer', $transaction->change_balance * -1));
            $transaction->delete();

            $model->offers()->detach($offer_id);
        }

        return Action::message('Баллы востановлены');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }

    public function authorizedToRun(Request $request, $model)
    {
        return true;
    }
}
