<?php


namespace App\Entities\MobileUser\Services;


use App\Base\BaseService;
use App\Entities\MobileUser\DTO\AddCheckMobileUserDTO;
use App\Entities\MobileUser\DTO\AddEventMobileUserDTO;
use App\Entities\MobileUser\DTO\AddOfferMobileUserDTO;
use App\Entities\MobileUser\DTO\AddReferralMobileUserDTO;
use App\Entities\MobileUser\DTO\AddSurveyMobileUserDTO;
use App\Entities\MobileUser\DTO\AuthMobileUserDTO;
use App\Entities\MobileUser\DTO\ChangeCounterShareMobileUserDTO;
use App\Entities\MobileUser\DTO\ChangePhoneMobileUserDTO;
use App\Entities\MobileUser\DTO\CheckPhoneMobileUserDTO;
use App\Entities\MobileUser\DTO\CheckRegisterMobileUserDTO;
use App\Entities\MobileUser\DTO\GetBalanceMobileUserDTO;
use App\Entities\MobileUser\DTO\GetOffersMobileUserDTO;
use App\Entities\MobileUser\DTO\LoginMobileUserDTO;
use App\Entities\MobileUser\DTO\LogoutMobileUserDTO;
use App\Entities\MobileUser\DTO\UpdateMobileUserDTO;
use App\Entities\MobileUser\Models\MobileUser;
use App\Entities\MobileUser\Repositories\MobileUserRepository;
use App\Entities\MobileUser\Resources\GetBalanceMobileUserResource;
use App\Entities\MobileUser\Resources\SingleMobileUserResource;
use App\Entities\Offer\Resources\MobileUserOfferResource;
use App\Entities\SmsCode\Events\SendSmsEvent;
use App\Entities\SmsCode\Events\SendSmsForChangePhoneEvent;
use App\Entities\Transaction\Resources\SingleTransactionResource;


class MobileUserService extends BaseService
{
    private MobileUserRepository $mobileUserRepository;

    public function __construct(MobileUserRepository $mobileUserRepository)
    {
        $this->mobileUserRepository = $mobileUserRepository;
    }

    public function update(UpdateMobileUserDTO $storeMobileUserDTO): SingleMobileUserResource
    {
        return new SingleMobileUserResource( $this->mobileUserRepository->update($storeMobileUserDTO->mobileUserId, $storeMobileUserDTO->data));
    }

    public function login(LoginMobileUserDTO $loginMobileUserDTO)
    {
        $mobileUser = $this->mobileUserRepository->getByPhone($loginMobileUserDTO->phone);
        event(new SendSmsEvent($mobileUser));

        return $mobileUser;
    }

    public function create(LoginMobileUserDTO $loginMobileUserDTO)
    {
        $mobileUser = $this->mobileUserRepository->store($loginMobileUserDTO->phone);
        event(new SendSmsEvent($mobileUser));

        return $mobileUser;
    }

    public function auth(AuthMobileUserDTO $authMobileUserDTO)
    {
        $mobileUser = $this->mobileUserRepository->auth(
            $authMobileUserDTO->phone,
            $authMobileUserDTO->smsCode
        );

        if (!empty($mobileUser)) {
            return new SingleMobileUserResource($mobileUser);
        }

        return $mobileUser;
    }

    public function logout(LogoutMobileUserDTO $logoutMobileUserDTO)
    {
        return $this->mobileUserRepository->logout($logoutMobileUserDTO->token);
    }

    public function addReferral(AddReferralMobileUserDTO $addReferralMobileUserDTO)
    {
        return $this->mobileUserRepository->addReferral($addReferralMobileUserDTO->mobileUserId, $addReferralMobileUserDTO->referralCode);
    }

    public function addEvent(AddEventMobileUserDTO $addEventMobileUserDTO): void
    {
        $this->mobileUserRepository->addEvent($addEventMobileUserDTO->mobileUserId, $addEventMobileUserDTO->eventId);
    }

    public function addOffer(AddOfferMobileUserDTO $addOfferMobileUserDTO): void
    {
        $this->mobileUserRepository->addOffer($addOfferMobileUserDTO->mobileUserId, $addOfferMobileUserDTO->offerId);
    }

    public function getOffers(GetOffersMobileUserDTO $getOffersMobileUserDTO)
    {
        return MobileUserOfferResource::collection(
            $this->mobileUserRepository->getOffers($getOffersMobileUserDTO->mobileUserId)
        );
    }

    public function addCheck(AddCheckMobileUserDTO $addCheckMobileUserDTO)
    {
        $this->mobileUserRepository->addCheck(
            $addCheckMobileUserDTO->mobileUserId,
            $addCheckMobileUserDTO->amount,
            $addCheckMobileUserDTO->fss,
            $addCheckMobileUserDTO->number,
            $addCheckMobileUserDTO->fiscalsign,
            $addCheckMobileUserDTO->purchaseDate,
            $addCheckMobileUserDTO->cashback
        );
    }

    public function addSurvey(AddSurveyMobileUserDTO $addSurveyMobileUserDTO)
    {
        $this->mobileUserRepository->addSurvey($addSurveyMobileUserDTO->mobileUserId, $addSurveyMobileUserDTO->token);
    }

    public function getBalance(GetBalanceMobileUserDTO $getBalanceMobileUserDTO)
    {
        $mobileUser = $this->mobileUserRepository->getBalance($getBalanceMobileUserDTO->token);
        if (!empty($mobileUser)) {
            return new GetBalanceMobileUserResource($mobileUser);
        }

        return [];
    }

    public function addRigisterCode(AddSurveyMobileUserDTO $addSurveyMobileUserDTO)
    {
        $this->mobileUserRepository->addRigisterCode($addSurveyMobileUserDTO->mobileUserId);
    }

    public function getLatestTransaction(MobileUser $mobileUser): SingleTransactionResource
    {
        return new SingleTransactionResource($mobileUser->transactionsMobileUser()->latest()->first());
    }

    public function checkPhone(CheckPhoneMobileUserDTO $checkRegisterMobileUserDTO)
    {
        if (MobileUser::where('phone', $checkRegisterMobileUserDTO->phone)->exists()) {
            throw new \RuntimeException('Данный номер уже зарегистрирован');
        }

        event(
            new SendSmsForChangePhoneEvent(
                MobileUser::find($checkRegisterMobileUserDTO->mobileUserId),
                $checkRegisterMobileUserDTO->phone
            )
        );
    }

    public function changePhone(ChangePhoneMobileUserDTO $changePhoneMobileUserDTO)
    {
        return $this->mobileUserRepository->changePhone(
            $changePhoneMobileUserDTO->smsCode,
            $changePhoneMobileUserDTO->mobileUserId,
            $changePhoneMobileUserDTO->phone
        );
    }

    public function activatedOffer(AddOfferMobileUserDTO $addOfferMobileUserDTO): void
    {
        $this->mobileUserRepository->activatedOffer($addOfferMobileUserDTO->mobileUserId, $addOfferMobileUserDTO->offerId);
    }

    public function changeCounterShare(ChangeCounterShareMobileUserDTO $changeCounterShareMobileUserDTO)
    {
        return new SingleMobileUserResource( $this->mobileUserRepository->changeCounterShare($changeCounterShareMobileUserDTO->mobileUserId));
    }
}
