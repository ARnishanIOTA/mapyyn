<?php

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


/*========================= Client Auth System =============================*/
Route::prefix('clients')->group(function () {

    Route::middleware('guest:apiClient')->namespace('Auth')->group(function () {
        // Login
        Route::post('/login', 'LoginController@clientLogin');
        // Registration
        Route::post('/register', 'RegisterController@register');
        // Activation Code
        Route::post('/activation-code', 'RegisterController@activate');
        // Resend Code
        Route::post('/resend', 'RegisterController@resend');
        // Forgot Password & Send Reset Code
        Route::post('/forgot-password', 'ForgotPasswordController@forgotPassword');
        // reset code
        Route::post('/check-code', 'ForgotPasswordController@checkCode');
        // New Password
        Route::post('/reset-password', 'ForgotPasswordController@newPassword');
    });


    Route::middleware('auth:apiClient')->namespace('Clients')->group(function () {
        // my profile
        Route::get('/my-profile', 'ProfileController@show');
        // update my profile
        Route::post('/my-profile', 'ProfileController@update');
        // update Image
        Route::post('/update-image', 'ProfileController@updateImage');

        // update Image
        Route::get('/passenger/offers/{offer}', 'PassengerController@offer');
        Route::get('/passenger/request_offers/{requestOffer}', 'PassengerController@requestOffer');


        Route::post('/passenger/submit/data/offers/{offer}', 'PassengerController@offerSubmit');
        Route::post('/passenger/submit/data/request_offers/{requestOffer}', 'PassengerController@requestOfferSubmit');

        // notification
        Route::get('/notifications', 'NotificationsController@index');
        // notification count
        Route::get('/notifications/counter', 'NotificationsController@notificationCount');
        // notification update
        Route::get('/notifications/update', 'NotificationsController@notificationUpdate');


        // list of chat
        Route::get('/chat', 'ChatController@index');
        // chat details
        Route::get('/chat-details/{chat}', 'ChatController@show');
        // start chat
        Route::post('/start-chat', 'ChatController@startChat');
        // Reply
        Route::post('/chat-reply/{chat}', 'ChatController@store');
        // check
        Route::get('/check-if-chat-exists', 'ChatController@check');

        // client offers
        Route::get('/offers', 'OffersController@offers');

        // rate
        Route::post('/rate', 'RateController@store');

        // requests-offers
        Route::get('/requests-offers', 'RequestOfferController@index');
        // cancel request offer
        Route::get('/requests-offers/cancel/{requestOffer}', 'RequestOfferController@cancel');
        // chat details
        Route::get('/request-offer-details/{request_offer}', 'RequestOfferController@show');
        // submit request
        Route::post('/request-offer', 'RequestOfferController@store');
        // accept
        Route::post('/request-offer/accept/{request_offer}', 'RequestOfferController@accept');
        // reject
        Route::post('/request-offer/reject/{request_offer}', 'RequestOfferController@reject');

        // buy offer
        Route::post('/buy-offer', 'OrdersController@store');

        Route::get('/get-checkout-id', 'OrdersController@checkout_id');
        Route::get('/notification-url', 'OrdersController@notification');

        // orders
        Route::get('/orders', 'OrdersController@index');
        // order details
        Route::get('/order-details/{payment}', 'OrdersController@show');

         // order details
         Route::post('/change/currency', 'CurrencyController@currency');

    });

});

/*=========================================================================*/

// Refresh
Route::post('/client-refresh', 'Auth\LoginController@clientRefresh');
Route::post('/provider-refresh', 'Auth\LoginController@providerRefresh');

/*========================= Provider Auth System =============================*/
Route::prefix('providers')->group(function () {

    Route::middleware('guest:apiProvider')->namespace('Auth')->group(function () {
        // Login
        Route::post('/login', 'LoginController@ProviderLogin');
        // Forgot Password & Send Reset Code
        Route::post('/forgot-password', 'ForgotPasswordController@forgotPassword');
        // reset code
        Route::post('/check-code', 'ForgotPasswordController@checkCode');
        // New Password
        Route::post('/reset-password', 'ForgotPasswordController@newPassword');
    });


    Route::middleware('auth:apiProvider')->namespace('Providers')->group(function () {
        // my profile
        Route::get('/my-profile', 'ProfileController@show');
        // update my profile
        Route::post('/my-profile', 'ProfileController@update');
        Route::post('/password', 'ProfileController@password');
        // update Image
        Route::post('/update-image', 'ProfileController@updateImage');

        // list of offers
        Route::get('/offers', 'OffersController@index');
        //offer details
        Route::get('/offer-details/{offer}', 'OffersController@show');

        // notification
        Route::get('/notifications', 'NotificationsController@index');
        // notification count
        Route::get('/notifications/counter', 'NotificationsController@notificationCount');
        // notification update
        Route::get('/notifications/update', 'NotificationsController@notificationUpdate');


        // list of chat
        Route::get('/chat', 'ChatController@index');
        // chat details
        Route::get('/chat-details/{chat}', 'ChatController@show');
        // start chat
        // Route::post('/start-chat', 'ChatController@start');
        // Reply
        Route::post('/chat-reply/{chat}', 'ChatController@store');


        // offers requests
        Route::get('/requests', 'RequestOfferController@index');
        // offers requests
        // Route::get('/requests/reply/{request_offer}', 'RequestOfferController@store');
        // offer request details
        Route::get('/requests/details/{request_offer}', 'RequestOfferController@show');

        // orders
        Route::get('/orders', 'OrdersController@index');
        // order details
        Route::get('/order-details/{payment}', 'OrdersController@show');
    });

   

});
/*=========================================================================*/



/*============================= Countries & Cities =========================*/
        // countries
        Route::get('/countries', 'CountryController@countries');
        // cities
        Route::get('/cities', 'CityController@cities');
/*==========================================================================*/



/*================================ General ================================ */
    Route::get('/leagues', 'Clients\RequestOfferController@leagues');
    Route::get('/events/{id}', 'Clients\RequestOfferController@events');
    Route::get('/offers', 'Clients\OffersController@index');
    Route::get('/map', 'Clients\OffersController@map');
    Route::get('/offer-details/{offer}', 'Clients\OffersController@details');
    Route::get('/search', 'Clients\SearchController@index');
    Route::post('/contact-us', 'ContactUsController@store');
    Route::get('/site-info', 'SettingController@index');
    Route::get('/admin-offers', 'AdminOfferController@index');

