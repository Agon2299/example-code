<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/news')
    ->name('news.')
    ->group(static function () {
        Route::get('with/promotions', '\App\Entities\News\Controllers\NewsController@getWithPromotions')
            ->name('with-promotions');
        Route::get('with/promotions-and-event', '\App\Entities\News\Controllers\NewsController@getWithPromotionsAndEvent')
            ->name('with-promotions-and-events');
        Route::get('{newsId}', '\App\Entities\News\Controllers\NewsController@getSingle')->name('get-single');
    });

Route::prefix('/promotions')
    ->name('promotions.')
    ->group(static function () {
        Route::get('{promotionId}', '\App\Entities\Promotion\Controllers\PromotionsController@getSingle')->name('get-single');
        Route::get('/', '\App\Entities\Promotion\Controllers\PromotionsController@getList')->name('get-list');
    });

Route::prefix('/events')
    ->name('events.')
    ->group(static function () {
        Route::get('/', '\App\Entities\Event\Controllers\EventsController@getList')->name('get-list');
        Route::get('{eventId}', '\App\Entities\Event\Controllers\EventsController@getSingle')->name('get-single');
    });

Route::prefix('/shops')
    ->name('shops.')
    ->group(static function () {
        Route::get('/', '\App\Entities\Shop\Controllers\ShopController@getList')->name('get-list');
        Route::get('{shopId}', '\App\Entities\Shop\Controllers\ShopController@getSingle')->name('get-single');
        Route::get('{shopId}/promotions', '\App\Entities\Promotion\Controllers\PromotionsController@getPromotionsListByShopId')
            ->name('get-events-by-shop-id');

        Route::prefix('/category')->group(static function () {
            Route::get('{categoryId}', '\App\Entities\Shop\Controllers\ShopController@getByCategory');
            Route::get('/slug/{slug}', '\App\Entities\Shop\Controllers\ShopController@getByCategorySlug');
        });

        Route::prefix('/subcategory')->group(static function () {
            Route::get('{subcategoryId}', '\App\Entities\Shop\Controllers\ShopController@getBySubcategory');
        });
    });

Route::prefix('/categories')
    ->name('categories.')
    ->group(static function () {
        Route::get('/', '\App\Entities\Category\Controllers\CategoryController@getList')->name('get-list');
        Route::get('/slug/{slug}', '\App\Entities\Category\Controllers\CategoryController@getCategoryBySlug')->name('get-category-by-slug');
        Route::prefix('/{categoryId}')->group(static function () {
            Route::get('/subcategories', '\App\Entities\Category\Controllers\CategoryController@getListSubcategories');
        });
    });

Route::prefix('/mobile-users')
    ->name('mobile_users.')
    ->group(static function () {
        Route::post('/login', '\App\Entities\MobileUser\Controllers\MobileUserController@login')
            ->name('login')
            ->middleware('login');
        Route::post('/auth', '\App\Entities\MobileUser\Controllers\MobileUserController@auth')
            ->name('auth')
            ->middleware('login');
        Route::get('/balance', '\App\Entities\MobileUser\Controllers\MobileUserController@getBalance')
            ->name('get-balance');

        Route::prefix('/{mobileUserID}')->group(static function () {
            Route::put('/', '\App\Entities\MobileUser\Controllers\MobileUserController@update')->name('update');
            Route::post('/check-phone', '\App\Entities\MobileUser\Controllers\MobileUserController@checkPhone')->name('check-phone');
            Route::put('/change-phone', '\App\Entities\MobileUser\Controllers\MobileUserController@changePhone')->name('change-phone');
            Route::put('/counter-share', '\App\Entities\MobileUser\Controllers\MobileUserController@changeCounterShare')->name('change-counter-share');

            Route::post('/logout', '\App\Entities\MobileUser\Controllers\MobileUserController@logout')->name('logout');
            Route::get(
                '/transactions',
                '\App\Entities\Transaction\Controllers\TransactionController@getListTransactionsUsers'
            )->name('get-my-transaction');

            Route::post(
                '/referral',
                '\App\Entities\MobileUser\Controllers\MobileUserController@addReferral'
            )->name('add-referral');

            Route::post(
                '/survey',
                '\App\Entities\MobileUser\Controllers\MobileUserController@addSurvey'
            )->name('add-survey');

            Route::post(
                '/events/{eventId}/attend',
                '\App\Entities\MobileUser\Controllers\MobileUserController@addEvent'
            )->name('add-event');

            Route::post(
                '/add-register',
                '\App\Entities\MobileUser\Controllers\MobileUserController@addRigisterCode'
            )->name('add-register');

            Route::prefix('/offers')
                ->name('offers.')
                ->group(static function () {
                    Route::get(
                        '/',
                        '\App\Entities\MobileUser\Controllers\MobileUserController@getOffers'
                    )->name('get-my-offers');

                    Route::post(
                        '/{offerId}/attend',
                        '\App\Entities\MobileUser\Controllers\MobileUserController@addOffer'
                    )->name('add-offer');

                    Route::post(
                        '/{offerId}/activated',
                        '\App\Entities\MobileUser\Controllers\MobileUserController@activatedOffer'
                    )->name('activated-offer');
                });

            Route::prefix('/checks')
                ->name('checks.')
                ->group(static function () {
                    Route::get('/', '\App\Entities\Check\Controllers\CheckController@getList')->name('get-list');
                    Route::get('/{checkId}', '\App\Entities\Check\Controllers\CheckController@getSingle')->name('get-single');
                    Route::post('/', '\App\Entities\MobileUser\Controllers\MobileUserController@addCheck')
                        ->name('add-check')
                        ->middleware('check');
                });
        });
    });

Route::prefix('/mobile-device')
    ->name('mobile_device.')
    ->group(static function () {
        Route::post('/', '\App\Entities\MobileDevice\Controllers\MobileDeviceController@store')->name('store');
        Route::prefix('/{mobileDeviceId}')->group(static function () {
            Route::post('/token', '\App\Entities\MobileDevice\Controllers\MobileDeviceController@addToken')
                ->name('store-push-token');
            Route::put('/setting-push', '\App\Entities\MobileDevice\Controllers\MobileDeviceController@updateSettings')
                ->name('update-setting-push');
            Route::get('/', '\App\Entities\MobileDevice\Controllers\MobileDeviceController@getSingle')
                ->name('get-single');

            Route::put('/campaign-status/{campaignId}/open', '\App\Entities\CampaignStatus\Controllers\CampaignStatusController@open');
        });
    });

Route::prefix('/app-feedback')
    ->name('app_feedback.')
    ->group(static function () {
        Route::post('/', '\App\Entities\AppFeedback\Controllers\AppFeedbackController@store')->name('store');
    });

Route::prefix('/offers')
    ->name('offers.')
    ->group(static function () {
        Route::get('/', '\App\Entities\Offer\Controllers\OfferController@getList')->name('get-list');
        Route::get('/{offerId}', '\App\Entities\Offer\Controllers\OfferController@getSingle')->name('get-single');
    });