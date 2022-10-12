<?php


namespace App\Entities\MobileUser\Resources;


use App\Base\BaseResource;

class SingleMobileUserResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'mobile_id' => $this->mobile_id,
            'phone' => $this->phone,
            'name' => $this->name,
            'email' => $this->email,
            'gender' => $this->gender,
            'registration_by_promo_code' => $this->registration_by_promo_code,
            'referral_code' => $this->referral_code,
            'enable_push_promotion' => $this->enablePushPromotion,
            'enable_push_event' => $this->enablePushEvent,
            'enable_push_children' => $this->enablePushChildren,
            'finish_poll' => $this->finish_poll,
            'date_birth' => $this->date_birth,
            'have_kids' => $this->have_kids,
            'count_invited_users' => $this->countReferralUser,
            'count_registered_checks' => $this->count_registered_checks,
            'total_amount_of_all_checks' => (float)$this->total_amount_of_all_checks,
            'average_check' => (float)$this->average_check,
            'points_balance' => $this->points_balance,
            'total_points_accumulated' => $this->total_points_accumulated,
            'total_points_spent' => $this->total_points_spent,
            'count_offers_purchased' => $this->count_offers_purchased,
            'maximum_purchased_offers' => $this->maximum_purchased_offers,
            'app_evaluation' => $this->app_evaluation,
            'remember_token' => $this->remember_token,
            'created_at' => $this->created_at,
            'has_feedback' => $this->appFeedback()->first() ? true : false,
            'count_share' => $this->count_share
        ];
    }
}
