<?php


namespace App\Entities\MobileUser\Repositories;


use App\Base\BaseRepository;
use App\Entities\ActivatedOffer\Models\ActivatedOffer;
use App\Entities\Cashbox\Models\Cashbox;
use App\Entities\Check\Models\Check;
use App\Entities\Event\Models\Event;
use App\Entities\MobileUser\Models\MobileUser;
use App\Entities\Offer\Models\Offer;
use App\Entities\StaticTransaction\Models\StaticTransaction;
use App\Entities\Transaction\Events\AddTransactionEvent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;


class MobileUserRepository extends BaseRepository
{
    public function store($phone)
    {
        do {
            $referralCode = Str::random(5);
        } while (MobileUser::where('referral_code', $referralCode)->first());

        return MobileUser::create([
            'phone' => $phone,
            'referral_code' => $referralCode,
        ]);
    }

    public function getByPhone($phone)
    {
        return MobileUser::where('phone', $phone)->first();
    }

    public function update($mobileUserId, $data)
    {
        $mobileUser = MobileUser::find($mobileUserId);
        $mobileUserParams = $mobileUser->toArray();

        foreach ($data as $key => $value) {
            $mobileUserParams[$key] = $value;
        }

        $mobileUser->update($mobileUserParams);

        return $mobileUser;
    }

    public function auth($phone, $code)
    {
        $smsCode = MobileUser::where('phone', $phone)->first()->smsCode()->first();
        if ($smsCode->code === $code) {
            $smsCode->code = null;
            $smsCode->save();

            $mobileUser = $smsCode->mobileUser;
            $mobileUser->remember_token = hash('sha256', Str::random(100));
            $mobileUser->save();

            return $mobileUser;
        }

        return [];
    }

    public function logout($rememberToken)
    {
        $mobileUser = MobileUser::where('remember_token', $rememberToken)->first();
        $mobileUser->remember_token = null;
        $mobileUser->save();

        return true;
    }

    public function addReferral($mobileUserId, $referralCode)
    {
        $mobileUser = MobileUser::find($mobileUserId) ?: false;
        $referralMobileUser = MobileUser::where('referral_code', $referralCode)->first() ?: false;

        if (!$mobileUser) {
            throw new \RuntimeException(__('error-api.not_found_mobile_user'), );
        }

        if (!$referralMobileUser) {
            throw new \RuntimeException(__('error-api.not_found_mobile_user'));
        }

        $staticTransactionReferralActivation = StaticTransaction::where('type_transaction', 'referral_activation')->first();
        $staticTransactionReferralActivated = StaticTransaction::where('type_transaction', 'referral_activated')->first();

        if (!$staticTransactionReferralActivation || !$staticTransactionReferralActivated) {
            throw new \RuntimeException(__('error-api.not_found_transactions'));
        }

        $mobileUser->update([
            'registration_by_promo_code' => true,
            'referral_user_id' => $referralMobileUser->id,
            'points_balance' => $mobileUser->points_balance + $staticTransactionReferralActivation->amount,
            'total_points_accumulated' => $mobileUser->total_points_accumulated + $staticTransactionReferralActivation->amount
        ]);

        $referralMobileUser->update([
            'points_balance' => $referralMobileUser->points_balance + $staticTransactionReferralActivated->amount,
            'total_points_accumulated' => $referralMobileUser->total_points_accumulated + $staticTransactionReferralActivated->amount
        ]);

        event(new AddTransactionEvent($mobileUser, $referralMobileUser, 'referral_activation', $staticTransactionReferralActivation->amount));
        event(new AddTransactionEvent($referralMobileUser, $mobileUser, 'referral_activated', $staticTransactionReferralActivated->amount));

        return $mobileUser;
    }

    public function addEvent($mobileUserId, $eventId): void
    {
        $mobileUser = MobileUser::find($mobileUserId);
        $event = Event::find($eventId);

        if (!$event) {
            throw new \RuntimeException(__('error-api.not_found_event'));
        }

        if ($mobileUser->points_balance < $event->cost) {
            $countPoint = $event->cost - $mobileUser->points_balance;
            throw new \RuntimeException(
                __(
                    'error-api.event_coast_more_mobile_user_point',
                    [
                        'countPoint' => $countPoint,
                        'world' => $this->formNumberWord($countPoint, 'балл', ['', 'а', 'ов'])
                    ]
                )
            );
        }

        if ($mobileUser->events()->where('id', $eventId)->exists()) {
            throw new \RuntimeException(__('error-api.event_exists'));
        }

        $mobileUser->update([
            'points_balance' => $mobileUser->points_balance - $event->cost,
            'total_points_spent' => $mobileUser->total_points_spent + $event->cost
        ]);

        event(new AddTransactionEvent($mobileUser, $event, 'buy_event', '-' . $event->cost));

        $mobileUser->events()->attach($eventId);
    }

    public function formNumberWord($number, $value, $suffix)
    {
        $keys = array(2, 0, 1, 1, 1, 2);
        $mod = $number % 100;
        $suffix_key = $mod > 4 && $mod < 21 ? 2 : $keys[min($mod % 10, 5)];
        return $value . $suffix[$suffix_key];
    }

    public function addOffer($mobileUserId, $offerId): void
    {
        $mobileUser = MobileUser::find($mobileUserId);
        $offer = Offer::find($offerId);

        if (!$offer) {
            throw new \RuntimeException(__('error-api.not_found_offer'));
        }

        if ($offer->count <= $offer->transactions->count()) {
            throw new \RuntimeException(__('error-api.count_many'));
        }

        if ($mobileUser->points_balance < $offer->cost) {
            $countPoint = $offer->cost - $mobileUser->points_balance;
            throw new \RuntimeException(
                __(
                    'error-api.offer_coast_more_mobile_user_point',
                    [
                        'countPoint' => $countPoint,
                        'world' => $this->formNumberWord($countPoint, 'балл', ['', 'а', 'ов'])
                    ]
                )
            );
        }

        if ($mobileUser->offers()->where('id', $offerId)->exists()) {
            throw new \RuntimeException(__('error-api.offer_exists'));
        }

        if (ActivatedOffer::where('offer_id', $offerId)->where('mobile_user_id', $mobileUserId)->exists() && $offer->type === 'welcome_offer') {
            throw new \RuntimeException(__('error-api.activated_offer_welcome_offer'));
        }

        $mobileUser->update([
            'points_balance' => $mobileUser->points_balance - $offer->cost,
            'total_points_spent' => $mobileUser->total_points_spent + $offer->cost
        ]);

        event(new AddTransactionEvent($mobileUser, $offer, 'buy_offer', '-' . $offer->cost));

        $mobileUser->offers()->attach($offerId);
    }

    public function getOffers($mobileUserId)
    {
        $mobileUser = MobileUser::find($mobileUserId);
        return $mobileUser->offers ?? [];
    }

    public function addCheck($mobileUserId, $amount, $fss, $number, $fiscalsign, $purchaseDate, $cashback = 0): void
    {
        $mobileUser = MobileUser::find($mobileUserId);
        $casbox = Cashbox::where('number_cahsbox', $fss)->first();
        $casboxId = $casbox ? $casbox->id : null;

        if (!$casbox) {
            throw new \RuntimeException(__('error-api.not_found_cashbox'));
        }

//        if (!$casbox->work) {
//            throw new \RuntimeException(__( 'error-api.check_cashbox_not_work'));
//        }

        $timeNow = Carbon::now();
        $timeCheck = Carbon::parse((int)$purchaseDate);
        $diffInHours = $timeCheck->diffInHours($timeNow, false);
        if ($diffInHours >= 24) {
            throw new \RuntimeException(__('error-api.more_hours_that_day'));
        }

        if ($diffInHours < 0) {
            throw new \RuntimeException(__('error-api.more_hours'));
        }

        $hasCheck = Check::where([
            ['cashbox_id', $casboxId],
            ['number', $number],
            ['fiscalsign', $fiscalsign],
            ['amount', $amount],
        ])->exists();

        if ($hasCheck) {
            throw new \RuntimeException(__('error-api.has_check'));
        }

        $shop = $casbox ? $casbox->shop : null;
        $shopId = $shop ? $shop->id : null;
        if (!$shop->cashback) {
            throw new \RuntimeException(__('error-api.empty_cashback'));
        }

        $checksCount = Check::where([
            ['mobile_user_id', $mobileUserId],
            ['shop_id', $shopId]
        ])->whereDate('created_at', '=', date('Y-m-d'))->count();

        if ($checksCount > 3) {
            throw new \RuntimeException(__('error-api.check_limit'));
        }

        $checksAmount = Check::where([
            ['mobile_user_id', $mobileUserId],
            ['shop_id', $shopId]
        ])->whereDate('created_at', '=', date('Y-m-d'))->sum('amount');

        if ($checksAmount + $amount > 50000) {
            throw new \RuntimeException(__('error-api.check_limit'));
        }

        $check = Check::create([
            'mobile_user_id' => $mobileUserId,
            'cashbox_id' => $casboxId,
            'number' => $number,
            'fiscalsign' => $fiscalsign,
            'amount' => $amount,
            'shop_id' => $shopId,
            'purchase_date' => $purchaseDate
        ]);
    }

    public function addSurvey($mobileUserId)
    {
        $mobileUser = MobileUser::find($mobileUserId);

        if ($mobileUser->transactionsMobileUser()->where('type', 'survey')->first()) {
            throw new \RuntimeException(__('error-api.transactions_exists'));
        }

        $staticTransactionSurvey = StaticTransaction::where('type_transaction', 'survey')->first();
        if (!$staticTransactionSurvey) {
            throw new \RuntimeException(__('error-api.not_found_transactions'));
        }

        $mobileUser->update([
            'points_balance' => $mobileUser->points_balance + $staticTransactionSurvey->amount,
            'total_points_accumulated' => $mobileUser->total_points_accumulated + $staticTransactionSurvey->amount
        ]);

        event(new AddTransactionEvent($mobileUser, null, 'survey', $staticTransactionSurvey->amount));
    }

    public function addRigisterCode($mobileUserId)
    {
        $mobileUser = MobileUser::find($mobileUserId);

        if (empty($mobileUser->name)) {
            throw new \RuntimeException(__('error-api.empty_mobile_user_name'));
        }

        if (empty($mobileUser->email)) {
            throw new \RuntimeException(__('error-api.empty_mobile_user_email'));
        }

        if ($mobileUser->transactionsMobileUser()->where('type', 'registration')->first()) {
            throw new \RuntimeException(__('error-api.transactions_exists'));
        }

        $staticTransactionRegistration = StaticTransaction::where('type_transaction', 'registration')->first();
        if (!$staticTransactionRegistration) {
            throw new \RuntimeException(__('error-api.not_found_transactions'));
        }

        $mobileUser->update([
            'points_balance' => $mobileUser->points_balance + $staticTransactionRegistration->amount,
            'total_points_accumulated' => $mobileUser->total_points_accumulated + $staticTransactionRegistration->amount
        ]);

        event(new AddTransactionEvent($mobileUser, null, 'registration', $staticTransactionRegistration->amount));
    }

    public function getBalance($token)
    {
        return MobileUser::where('remember_token', $token)->first() ?: [];
    }

    public function changePhone($code, $mobileUserId, $phone)
    {
        $smsCode = MobileUser::find($mobileUserId)->smsCode()->first();
        if ($smsCode->code === $code) {
            $smsCode->code = null;
            $smsCode->save();

            $mobileUser = $smsCode->mobileUser;
            $mobileUser->phone = $phone;
            $mobileUser->save();

            return $mobileUser;
        }

        throw new \RuntimeException(__('error-api.diff_sms_code'));
    }

    public function activatedOffer($mobileUserId, $offerId): void
    {
        $mobileUser = MobileUser::find($mobileUserId);
        $offer = Offer::find($offerId);

        if (!$offer) {
            throw new \RuntimeException(__('error-api.not_found_offer'));
        }

        if (!$mobileUser->offers()->where('id', $offerId)->exists()) {
            throw new \RuntimeException(__('error-api.offer_exists'));
        }

        if (in_array($offer->type, ['single', 'welcome_offer'])) {
            $mobileUser->offers()->detach($offerId);
        }

        if ($offer->type === 'multiple'
            && ActivatedOffer::where('mobile_user_id', $mobileUserId)->where('offer_id', $offerId)->exists()) {
            return;
        }

        ActivatedOffer::create([
            'mobile_user_id' => $mobileUserId,
            'offer_id' => $offerId,
            'type' => 'activated'
        ]);
    }

    public function changeCounterShare($mobileUserId)
    {
        $mobileUser = MobileUser::find($mobileUserId);
        $mobileUser->increment('count_share');
        return $mobileUser;
    }
}
