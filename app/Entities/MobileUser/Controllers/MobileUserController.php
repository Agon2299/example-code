<?php


namespace App\Entities\MobileUser\Controllers;


use App\Base\BaseController;
use App\Entities\MobileDevice\Models\MobileDevice;
use App\Entities\MobileDevice\Services\MobileDeviceService;
use App\Entities\MobileUser\FormRequests\AddCheckMobileUser;
use App\Entities\MobileUser\FormRequests\AddEventMobileUserRequest;
use App\Entities\MobileUser\FormRequests\AddOfferMobileUserRequest;
use App\Entities\MobileUser\FormRequests\AddReferralMobileUser;
use App\Entities\MobileUser\FormRequests\AddSurveyMobileUserRequest;
use App\Entities\MobileUser\FormRequests\AuthMobileUser;
use App\Entities\MobileUser\FormRequests\ChangeCounterShareMobileUserRequest;
use App\Entities\MobileUser\FormRequests\ChangePhoneMobileUserRequest;
use App\Entities\MobileUser\FormRequests\CheckPhoneMobileUserRequest;
use App\Entities\MobileUser\FormRequests\GetBalanceMobileUser;
use App\Entities\MobileUser\FormRequests\GetOffersMobileUserRequest;
use App\Entities\MobileUser\FormRequests\LoginMobileUser;
use App\Entities\MobileUser\FormRequests\LogoutMobileUser;
use App\Entities\MobileUser\FormRequests\UpdateMobileUser;
use App\Entities\MobileUser\Models\MobileUser;
use App\Entities\MobileUser\Services\MobileUserService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;


class MobileUserController extends BaseController
{

    private MobileUserService $mobileUserService;
    private MobileDeviceService $mobileDeviceService;

    public function __construct(MobileUserService $mobileUserService, MobileDeviceService $mobileDeviceService)
    {
        $this->mobileUserService = $mobileUserService;
        $this->mobileDeviceService = $mobileDeviceService;
    }

    public function update(UpdateMobileUser $request)
    {
        $requestToDto = $request->requestToDto();
        $mobileUser = MobileUser::find($requestToDto->mobileUserId);

        if (!$mobileUser) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }


        if ($mobileUser->remember_token !== $requestToDto->token) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        if (array_key_exists('phone', $requestToDto->data)) {
            return $this->dataError(
                [
                    'error_text' => 'Нельзя менять телефон в этом методе'
                ],
                400
            );
        }

        return $this->mobileUserService->update($request->requestToDto());
    }

    public function login(LoginMobileUser $request): Response
    {
        $request->validated();
        $requestToDto = $request->requestToDto();

        if ($requestToDto->phone[0] !== '+') {
            return $this->dataError(
                ['error_text' => 'Не верный формат телефонного номера'],
                400
            );
        }

        $isLogin = MobileUser::where('phone', $requestToDto->phone)->first() ? true : false;
        try {
            DB::transaction(function () use ($isLogin, $requestToDto) {
                if ($isLogin) {
                    return $this->mobileUserService->login($requestToDto);
                }

                return $this->mobileUserService->create($requestToDto);
            }, 3);
        } catch (\Exception $exception) {
            return $this->serviceError(['error_message' => $exception->getMessage()]);
        }

        return $isLogin ? $this->success([]) : $this->created([]);
    }

    public function auth(AuthMobileUser $request)
    {
        $request->validated();
        $requestToDto = $request->requestToDto();
        $mobileUser = $this->mobileUserService->auth($requestToDto);

        if (!MobileDevice::find($requestToDto->mobileDeviceId)) {
            return $this->dataError(
                [
                    'error_message' => 'Устройство не зарегестрировано',
                    'error_code' => 10
                ],
                400
            );
        }

        if (!empty($mobileUser)) {
            $this->mobileDeviceService->updateUserId($mobileUser, $requestToDto->mobileDeviceId);
            return $this->success($mobileUser);
        }

        return $this->dataError(
            ['error_text' => 'Код не подошёл'],
            400
        );
    }

    public function logout(LogoutMobileUser $request)
    {
        $request->validated();
        $requestToDto = $request->requestToDto();
        $mobileUser = MobileUser::find($requestToDto->mobileUserId);
        if (!$mobileUser) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        if ($mobileUser->remember_token !== $requestToDto->token) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        $mobileDevice = MobileDevice::find($requestToDto->mobileDeviceId);
        if (!$mobileDevice) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Устройство не зарегестрировано',
                    'error_code' => 10
                ],
                400
            );
        }

        if ($mobileDevice->mobile_user_id !== $requestToDto->mobileUserId) {
            return $this->dataError(['error_text' => 'У такого устройства другой пользователь'], 400);
        }

        if (!$this->mobileUserService->logout($requestToDto)) {
            return $this->dataError(
                ['error' => 'Не известная ошибка'],
                400
            );
        }

        if (!$this->mobileDeviceService->logout($requestToDto->mobileDeviceId)) {
            return $this->dataError(
                ['error' => 'Не известная ошибка'],
                400
            );
        }

        return $this->success([]);
    }

    public function addReferral(AddReferralMobileUser $request)
    {
        $requestToDto = $request->requestToDto();
        $mobileUser = MobileUser::find($requestToDto->mobileUserId);
        if (!$mobileUser) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        if ($mobileUser->registration_by_promo_code) {
            return $this->dataError(['error_text' => 'Уже активирован'], 400);
        }

        if ($mobileUser->remember_token !== $requestToDto->token) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        if (!MobileUser::where('referral_code', $requestToDto->referralCode)->first()) {
            return $this->dataError(['error_text' => 'Неправильный реферальный код'], 400);
        }

        try {
            DB::transaction(function () use ($requestToDto) {
                return $this->mobileUserService->addReferral($requestToDto);
            }, 3);
        } catch (\Exception $exception) {
            $errors = explode('%exp%', $exception->getMessage());
            return $this->dataError(
                [
                    'error_header' => trim($errors[0]),
                    'error_text' => trim($errors[1])
                ], 400);
        }

        return $this->success($this->mobileUserService->getLatestTransaction($mobileUser));
    }

    public function addEvent(AddEventMobileUserRequest $request)
    {
        $requestToDto = $request->requestToDto();
        $mobileUser = MobileUser::find($requestToDto->mobileUserId);
        if (!$mobileUser) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        if ($mobileUser->remember_token !== $requestToDto->token) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        try {
            DB::transaction(function () use ($requestToDto) {
                $this->mobileUserService->addEvent($requestToDto);
            }, 1);
        } catch (\Exception $exception) {
            $errors = explode('%exp%', $exception->getMessage());
            return $this->dataError(
                [
                    'error_header' => trim($errors[0]),
                    'error_text' => trim($errors[1])
                ], 400);
        }

        return $this->success($this->mobileUserService->getLatestTransaction($mobileUser));
    }

    public function addOffer(AddOfferMobileUserRequest $request)
    {
        $requestToDto = $request->requestToDto();
        $mobileUser = MobileUser::find($requestToDto->mobileUserId);
        if (!$mobileUser) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        if ($mobileUser->remember_token !== $requestToDto->token) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        try {
            DB::transaction(function () use ($requestToDto) {
                $this->mobileUserService->addOffer($requestToDto);
            }, 1);
        } catch (\Exception $exception) {
            $errors = explode('%exp%', $exception->getMessage());
            return $this->dataError(
                [
                    'error_header' => trim($errors[0]),
                    'error_text' => trim($errors[1])
                ], 400);
        }

        return $this->success($this->mobileUserService->getLatestTransaction($mobileUser));
    }

    public function getOffers(GetOffersMobileUserRequest $request)
    {
        $requestToDto = $request->requestToDto();
        $mobileUser = MobileUser::find($requestToDto->mobileUserId);
        if (!$mobileUser) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        if ($mobileUser->remember_token !== $requestToDto->token) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        return $this->success($this->mobileUserService->getOffers($requestToDto));
    }


    public function addCheck(AddCheckMobileUser $request)
    {
        $requestToDto = $request->requestToDto();
        $mobileUser = MobileUser::find($requestToDto->mobileUserId);
        if (!$mobileUser) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        if ($mobileUser->remember_token !== $requestToDto->token) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        try {
            DB::transaction(function () use ($requestToDto) {
                $this->mobileUserService->addCheck($requestToDto);
            }, 1);
        } catch (\Exception $exception) {
            $errors = explode('%exp%', $exception->getMessage());
            return $this->dataError(
                [
                    'error_header' => trim($errors[0]),
                    'error_text' => trim($errors[1])
                ],
                400
            );
        }

        return $this->success($this->mobileUserService->getLatestTransaction($mobileUser));
    }

    public function getBalance(GetBalanceMobileUser $request)
    {
        return $this->success($this->mobileUserService->getBalance($request->requestToDto()));
    }

    public function addSurvey(AddSurveyMobileUserRequest $request)
    {
        $requestToDto = $request->requestToDto();
        $mobileUser = MobileUser::find($requestToDto->mobileUserId);
        if (!$mobileUser) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        if ($mobileUser->remember_token !== $requestToDto->token) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        try {
            DB::transaction(function () use ($requestToDto) {
                $this->mobileUserService->addSurvey($requestToDto);
            }, 1);
        } catch (\Exception $exception) {
            $errors = explode('%exp%', $exception->getMessage());
            return $this->dataError(
                [
                    'error_header' => trim($errors[0]),
                    'error_text' => trim($errors[1])
                ], 400);
        }

        return $this->success($this->mobileUserService->getLatestTransaction($mobileUser));
    }

    public function addRigisterCode(AddSurveyMobileUserRequest $request)
    {
        $requestToDto = $request->requestToDto();
        $mobileUser = MobileUser::find($requestToDto->mobileUserId);
        if (!$mobileUser) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        if ($mobileUser->remember_token !== $requestToDto->token) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        if ($mobileUser->transactionsMobileUser()->where('type', 'registration')->first()) {
            return $this->success();
        }

        try {
            DB::transaction(function () use ($requestToDto) {
                $this->mobileUserService->addRigisterCode($requestToDto);
            }, 1);
        } catch (\Exception $exception) {
            $errors = explode('%exp%', $exception->getMessage());
            return $this->dataError(
                [
                    'error_header' => trim($errors[0]),
                    'error_text' => trim($errors[1])
                ], 400);
        }

        return $this->success($this->mobileUserService->getLatestTransaction($mobileUser));
    }

    public function checkPhone(CheckPhoneMobileUserRequest $request): Response
    {
        $requestToDto = $request->requestToDto();
        $mobileUser = MobileUser::find($requestToDto->mobileUserId);
        if (!$mobileUser) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        if ($mobileUser->remember_token !== $requestToDto->token) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        try {
            DB::transaction(function () use ($requestToDto) {
                $this->mobileUserService->checkPhone($requestToDto);
            }, 1);
        } catch (\Exception $exception) {
            $errors = explode('%exp%', $exception->getMessage());
            return $this->dataError(
                [
                    'error_header' => trim($errors[0]),
                    'error_text' => trim($errors[1])
                ], 400);
        }

        return $this->success();
    }

    public function changePhone(ChangePhoneMobileUserRequest $request): Response
    {
        $requestToDto = $request->requestToDto();
        $mobileUser = MobileUser::find($requestToDto->mobileUserId);
        if (!$mobileUser) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        if ($mobileUser->remember_token !== $requestToDto->token) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        try {
            DB::transaction(function () use ($requestToDto) {
                $this->mobileUserService->changePhone($requestToDto);
            }, 1);
        } catch (\Exception $exception) {
            $errors = explode('%exp%', $exception->getMessage());
            return $this->dataError(
                [
                    'error_header' => trim($errors[0]),
                    'error_text' => trim($errors[1])
                ], 400);
        }

        return $this->success(MobileUser::find($requestToDto->mobileUserId));
    }

    public function activatedOffer(AddOfferMobileUserRequest $request)
    {
        $requestToDto = $request->requestToDto();
        $mobileUser = MobileUser::find($requestToDto->mobileUserId);
        if (!$mobileUser) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        if ($mobileUser->remember_token !== $requestToDto->token) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        try {
            DB::transaction(function () use ($requestToDto) {
                $this->mobileUserService->activatedOffer($requestToDto);
            }, 1);
        } catch (\Exception $exception) {
            $errors = explode('%exp%', $exception->getMessage());
            return $this->dataError(
                [
                    'error_header' => trim($errors[0]),
                    'error_text' => trim($errors[1])
                ], 400);
        }

        return $this->success();
    }

    public function changeCounterShare(ChangeCounterShareMobileUserRequest $request)
    {
        $requestToDto = $request->requestToDto();
        $mobileUser = MobileUser::find($requestToDto->mobileUserId);
        if (!$mobileUser) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        if ($mobileUser->remember_token !== $requestToDto->token) {
            return $this->dataError(
                [
                    'error_header' => 'Ошибка',
                    'error_text' => 'Пользователь не найден',
                    'error_code' => 11
                ],
                400
            );
        }

        $mobileUserChange = $this->mobileUserService->changeCounterShare($requestToDto);

        return $this->success($mobileUserChange);
    }
}
