<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/eslam', function () {
//     $url = "https://oppwa.com/v1/checkouts";
//     $data = "authentication.userId=8ac9a4ca692f2ddb01698b8e46b034df" .
//         "&authentication.password=xnFQjPpa2z" .
//         "&authentication.entityId=8ac9a4ca692f2ddb01698b90133c34ea" .
//         "&amount=92.00" .
//         "&currency=SAR" .
//         "&paymentType=DB" .
//         "&notificationUrl=".url('notifyeslam');

//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_POST, 1);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     $responseData = curl_exec($ch);
//     if(curl_errno($ch)) {
//         return curl_error($ch);
//     }
//     curl_close($ch);

//     return $responseData;



//     exit;
//     $config = new \Peach\Oppwa\Configuration(
//         '8ac9a4ca692f2ddb01698b8e46b034df',
//         'xnFQjPpa2z',
//         '8ac9a4ca692f2ddb01698b90133c34ea'
//     );
//     $client = new \Peach\Oppwa\Client($config);
//     $client->setTestMode(true);



//     $debit = new \Peach\Oppwa\Payments\Debit($client);
//     $debitResult = $debit
//         ->setCardBrand(\Peach\Oppwa\Cards\Brands::VISA)
//         ->setAmount(95.99)
//         ->setCurrency('SAR')
//         ->setAuthOnly(true)
//         ->process();

//     dd($debitResult);
// });


// Route::get('/notifyeslam', function (\Illuminate\Http\Request $request) {
//     $r = $request->input('res');
//     return [
//         "https://oppwa.com$r?authentication.userId=8ac9a4ca692f2ddb01698b8e46b034df&authentication.password=xnFQjPpa2z&authentication.entityId=8ac9a4ca692f2ddb01698b90133c34ea",
//         file_get_contents("https://oppwa.com$r?authentication.userId=8ac9a4ca692f2ddb01698b8e46b034df&authentication.password=xnFQjPpa2z&authentication.entityId=8ac9a4ca692f2ddb01698b90133c34ea")
//     ] ;
// });

// Route::get('/home1',function(){
//     return view('welcome');
// });

Route::group([
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ],
    function()
    {
        Route::prefix('backend')->group(function () {

            /*=========================== Backend Authentication Routes ========================*/
                Route::get('/login', 'Auth\Backend\LoginController@showLoginForm');
                Route::post('/login', 'Auth\Backend\LoginController@login')->name('login-backend');
                Route::post('/logout', 'Auth\Backend\LoginController@logout')->name('logout-backend');

                // Password Reset Routes...
                Route::post('password/email', 'Auth\Backend\ForgotPasswordController@sendResetLinkEmail')->name('users.password.email');
                Route::get('password/reset/{token}', 'Auth\Backend\ResetPasswordController@showResetForm')->name('users.password.reset');

                Route::post('password/reset', 'Auth\Backend\ResetPasswordController@reset')->name('users.password.request');
            /*==================================================================================*/
            Route::get('/get-cities/{id}', 'Backend\ProvidersController@getCities');

            Route::middleware('auth')->namespace('Backend')->group(function () {

                // statistics
                Route::get('edit_profile_request', 'RequestProfileEditController@index')->name('edit_profile_request');
                Route::get('edit_profile_request/{editProfile}/{status}', 'RequestProfileEditController@status')->name('edit_profile_request_update');
                // Permissions
                Route::prefix('permissions')->group(function () {
                    Route::get('/', 'PermissionsController@index')->name('permissions')->middleware('read');
                    Route::get('/create', 'PermissionsController@create')->name('create-permission')->middleware('create');
                    Route::post('/create', 'PermissionsController@store')->middleware('create');
                    Route::get('/{permission}', 'PermissionsController@show')->name('update-permission')->middleware('update');
                    Route::patch('/{permission}', 'PermissionsController@update')->middleware('update');
                    Route::get('/delete/{permission}', 'PermissionsController@destroy')->middleware('delete');
                });


                Route::prefix('cities')->group(function () {
                    Route::get('/', 'CitiesController@index')->name('cities')->middleware('read');
                    Route::get('/create', 'CitiesController@create')->name('create-city')->middleware('create');
                    Route::post('/create', 'CitiesController@store')->middleware('create');
                    Route::get('/{city}', 'CitiesController@show')->name('update-city')->middleware('update');
                    Route::patch('/{city}', 'CitiesController@update')->middleware('update');
                    Route::get('/delete/{city}', 'CitiesController@destroy')->middleware('delete');
                });

                Route::prefix('payments')->group(function () {
                    Route::get('/', 'PaymentsController@index')->name('admin.payments.index')->middleware('read');
                    Route::get('/data', 'PaymentsController@index_data')->name('admin.payments.data')->middleware('read');
                    Route::get('/show/{payment}', 'PaymentsController@show')->name('admin.payments.show')->middleware('create');
                    Route::patch('/update/{payment}', 'PaymentsController@update')->name('admin.payments.update')->middleware('create');
                    Route::get('/download/data', 'DownloadsController@payments')->name('admin.downloads.payments')->middleware('read');
                });

                // statistics
                Route::get('dashboard/statistics', 'StatisticsController@index');

                //videos in admin
                Route::get('dashboard/video','VideosController@index')->name('videos');
                Route::get('dashboard/video/create','VideosController@create')->name('videos.create');
                Route::post('dashboard/video/store','VideosController@store')->name('videos.store');


                Route::get('dashboard/video/show','VideosController@show')->name('videos.show');

                // permission error
                Route::get('/errors', 'StatisticsController@permission');

                // Settins
                Route::get('dashboard/settings', 'SettingsController@index')->name('settings');
                Route::patch('dashboard/settings', 'SettingsController@update');

                Route::prefix('clients')->group(function () {
                    Route::get('/', 'ClientsController@index')->name('clients')->middleware('read');
                    Route::get('/data', 'ClientsController@indexData')->name('clients-data')->middleware('read');
                    Route::get('/{client}', 'ClientsController@update')->name('update-clients')->middleware('read');
                    Route::get('/delete/{client}', 'ClientsController@destroy')->middleware('delete');
                    Route::get('/download/data', 'DownloadsController@clients')->name('admin.downloads.clients')->middleware('read');
                });

                Route::prefix('providers')->group(function () {
                    Route::get('/', 'ProvidersController@index')->name('providers')->middleware('read');
                    Route::get('/status/{provider}', 'ProvidersController@status')->name('status')->middleware('read');
                    Route::get('/data', 'ProvidersController@indexData')->name('providers-data')->middleware('read');
                    Route::get('/create', 'ProvidersController@create')->name('create-provider')->middleware('create');
                    Route::post('/create', 'ProvidersController@store')->middleware('create');
                    Route::get('/{provider}', 'ProvidersController@show')->name('admin.update-provider')->middleware('update');
                    Route::patch('/{provider}', 'ProvidersController@update')->middleware('update');
                    Route::get('/delete/{provider}', 'ProvidersController@destroy')->middleware('delete');

                    Route::get('/chat/{provider}', 'ProvidersController@showChat')->name('admin-add-message')->middleware('read');
                    Route::post('/chat/{provider}', 'ProvidersController@chat')->middleware('read');

                    Route::get('/register/requests', 'ProvidersRegisterRequestController@index')->name('providers_register_request')->middleware('read');
                    Route::get('/register/requests/delete/{providerRegisterRequest}', 'ProvidersRegisterRequestController@destroy')->middleware('read');

                    Route::get('/offers/{provider}', 'OffersController@index')->name('offers')->middleware('read');
                    Route::get('/offers/details/{offer}', 'OffersController@show')->name('offer-details')->middleware('read');
                    Route::get('/offers/status/{offer}', 'OffersController@update')->middleware('read');
                    Route::get('/offers/data/{provider}', 'OffersController@indexData')->name('offers-data')->middleware('read');
                    Route::get('/offers/delete/{offer}', 'OffersController@destroy')->name('admin-offer-delete');

                    Route::get('/download/data', 'DownloadsController@providers')->name('admin.downloads.providers')->middleware('read');

                });


                Route::prefix('chats')->group(function () {
                    Route::get('/', 'ChatController@index')->middleware('read');
                    Route::get('/data', 'ChatController@indexData')->name('admin_client_chat')->middleware('read');
                    Route::get('/offers/{chat}', 'ChatController@showOffer')->middleware('read');
                    Route::get('/request_offers/{chat}', 'ChatController@showRequestOffer')->middleware('read');
                    Route::post('/store/data/{chat}', 'ChatController@store')->name('backend.chat')->middleware('read');
                });

                Route::prefix('request_offers')->group(function () {
                    Route::get('/', 'RequestOffersController@index')->name('requests-offer')->middleware('read');
                    Route::get('/details/{request_offer}', 'RequestOffersController@show')->middleware('read');
                    Route::get('/is_active/{request_offer}', 'RequestOffersController@update')->middleware('read');
                    Route::get('/data', 'RequestOffersController@indexData')->name('requests-offer-data')->middleware('read');
                    Route::get('/offers/show/{request_offer}', 'RequestOffersController@showOffers')->name('show-offers-requests-data')->middleware('read');
                    Route::get('/offers/data/{request_offer}', 'RequestOffersController@offersData')->name('offers-requests-data')->middleware('read');
                });

                Route::prefix('faq')->group(function () {
                    Route::get('/', 'FaqController@index')->name('faq')->middleware('read');
                    Route::get('/get', 'FaqController@indexData')->name('faq-data')->middleware('read');
                    Route::get('/create', 'FaqController@create')->name('create-faq')->middleware('create');
                    Route::post('/create', 'FaqController@store')->middleware('create');
                    Route::get('/{faq}', 'FaqController@show')->name('update-faq')->middleware('update');
                    Route::patch('/{faq}', 'FaqController@update')->middleware('update');
                    Route::get('/delete/{faq}', 'FaqController@destroy')->middleware('delete');
                });


                Route::prefix('admin-offers')->group(function () {
                    Route::get('/', 'AdminOfferController@index')->name('admin-offer');
                    Route::get('/get', 'AdminOfferController@indexData')->name('admin-offer-data');
                    Route::get('/create', 'AdminOfferController@create')->name('create-admin-offer');
                    Route::post('/create', 'AdminOfferController@store');
                    Route::get('/{offer}', 'AdminOfferController@show')->name('update-admin-offer');
                    Route::patch('/{offer}', 'AdminOfferController@update');
                    Route::get('/delete/{offer}', 'AdminOfferController@destroy');
                });


                // Contact Us
                Route::prefix('contact_us')->group(function () {
                    Route::get('/', 'ContactUsController@index')->name('contactus')->middleware('read');
                    Route::get('/{contactUs}', 'ContactUsController@show')->name('reply')->middleware('update');
                    Route::post('/send/{contactUs}', 'ContactUsController@update')->name('reply-contact')->middleware('update');
                    Route::get('/delete/{contactUs}', 'ContactUsController@destroy')->middleware('delete');
                });


                // Subscribe
                Route::prefix('subscribes')->group(function () {
                    Route::get('/', 'SubscribesController@index')->name('subscribes')->middleware('read');
                    Route::get('/{subscribe}', 'SubscribesController@show')->middleware('update');
                    Route::post('/send/{subscribe}', 'SubscribesController@update')->name('reply-subscribe')->middleware('update');
                    Route::get('/delete/{subscribe}', 'SubscribesController@destroy')->middleware('delete');
                });


                // ads
                Route::prefix('ads')->group(function () {
                    Route::get('/', 'AdsController@index')->name('ads')->middleware('read');
                    Route::get('/{ads}', 'AdsController@show')->name('update-ads')->middleware('update');
                    Route::patch('/{ads}', 'AdsController@update')->middleware('update');
                    Route::get('/slider/create', 'AdsController@create')->middleware('create')->name('create-ads');
                    Route::post('/slider/create', 'AdsController@store')->middleware('create');
                    Route::get('/delete/{ads}', 'AdsController@destroy')->middleware('delete');
                });

                // Send Notification
                Route::prefix('send_notifications')->group(function () {
                    Route::get('/', 'SendNotificationsController@index')->name('send-notifications')->middleware('read');
                    Route::get('/data', 'SendNotificationsController@indexData')->name('send-notifications-data')->middleware('read');
                    Route::get('/create', 'SendNotificationsController@create')->name('send-notifications-create')->middleware('create');
                    Route::post('/create', 'SendNotificationsController@store')->middleware('create');
                    Route::get('/delete/{mobile_notification}', 'SendNotificationsController@destroy')->middleware('delete');
                });

                Route::get('/admin_notifications', 'NotificationsController@index')->name('admin_notifications');
                Route::get('/admin_notifications/data', 'NotificationsController@indexData')->name('admin_notifications_data');
                Route::get('/admin_notifications/delete', 'NotificationsController@destroy');
                Route::get('/admin_notifications/delete/data/{notification}', 'NotificationsController@delete');

                 // admins
                 Route::prefix('admins')->group(function () {
                    Route::get('/', 'UsersController@index')->name('admins')->middleware('read');
                    Route::get('/create', 'UsersController@create')->name('create-admin')->middleware('create');
                    Route::post('/create', 'UsersController@store')->middleware('create');
                    Route::get('/{user}', 'UsersController@show')->name('update-admin')->middleware('update');
                    Route::patch('/{user}', 'UsersController@update')->middleware('update');
                    Route::get('/delete/{user}', 'UsersController@destroy')->middleware('delete');
                });

            });

        });



        Route::prefix('providers')->group(function () {

                /*=========================== Backend Authentication Routes ========================*/
                    Route::get('/login', 'Auth\Provider\LoginController@showLoginForm');
                    Route::post('/login', 'Auth\Provider\LoginController@login')->name('login-provider');
                    Route::post('/logout', 'Auth\Provider\LoginController@logout')->name('logout-provider');

                    // Route::post('/register', 'Auth\Provider\RegisterController@register')->name('provider.register');

                // Password Reset Routes...
                    Route::get('password/reset/{token}', 'Auth\Provider\ResetPasswordController@showResetForm')->name('providers.password.reset');
                    Route::post('password/reset', 'Auth\Provider\ResetPasswordController@reset')->name('providers.password.request');

                    Route::post('password/email', 'Auth\Provider\ForgotPasswordController@sendResetLinkEmail')->name('providers.password.email');

                /*==================================================================================*/

                Route::middleware('auth:providers')->namespace('Provider')->group(function () {

                    // statistics
                    Route::get('/statistics', 'StatisticsController@index');
                    Route::get('/get-cities/{id}', 'ProfileController@getCities');


                    Route::get('/notifications', 'NotificationsController@index')->name('notifications');
                    Route::get('/notifications/data', 'NotificationsController@indexData')->name('notifications_data');
                    Route::get('/notifications/delete', 'NotificationsController@destroy');
                    Route::get('/notifications/delete/data/{mobileNotification}', 'NotificationsController@delete');

                    Route::prefix('offers')->group(function () {
                        Route::get('/', 'OffersController@index')->name('provider-offers');
                        Route::get('/data', 'OffersController@indexData')->name('provider-offers-data');

                        // Route::get('/data', 'RequestOffersController@indexData')->name('provider-offer-data');
                        Route::get('/create', 'OffersController@create')->name('create-offer');
                        Route::post('/create', 'OffersController@store');
                        Route::get('/{offer}', 'OffersController@show')->name('provider-offer-details');
                        Route::get('/delete/{offer}', 'OffersController@destroy')->name('provider-offer-delete');
                    });

                    Route::get('/get-events/{id}', 'OffersController@events');
                    Route::get('/get-cities/{id}', 'OffersController@cities');


                    Route::prefix('requests_offers')->group(function () {
                        Route::get('/', 'RequestOffersController@index')->name('provider-requests-offers');
                        Route::get('/details/{request_offer}', 'RequestOffersController@show');
                        Route::get('/reply/{request_offer}', 'RequestOffersController@reply')->name('reply-request');
                        Route::post('/reply/{request_offer}', 'RequestOffersController@store');
                        Route::get('/data', 'RequestOffersController@indexData')->name('provider-requests-offer-data');
                    });

                    // Chat
                    Route::prefix('chat')->group(function () {
                        Route::get('/', 'ChatController@index')->name('providers_chat');
                        // Route::get('/new', 'ChatController@create')->name('providers_chat_create');
                        // Route::post('/new', 'ChatController@startChat');
                        Route::prefix('chat-details')->group(function () {
                            // Route::get('/{chat}', 'ChatController@show')->name('providers_chat_details');
                            Route::post('/', 'ChatController@store')->name('provider-add-message');
                        });

                        Route::get('/clients', 'ChatController@clients')->name('providers_chat_clients');
                        Route::get('/clients/get/data', 'ChatController@indexData')->name('providers_chat_data');

                        Route::get('/clients/show/{chat}', 'ChatController@client_details')->name('providers_chat_clients_details');
                        Route::post('/clients/{chat}', 'ChatController@client_store')->name('providers_chat_clients_store');
                    });


                    // Payment
                    Route::prefix('payments')->group(function () {
                        Route::get('/', 'PaymentsController@index')->name('payments');
                        Route::get('/download/attachment/{payment}', 'PaymentsController@download')->name('payment_attachment');
                        Route::get('/{payment}', 'PaymentsController@show')->name('update-payment');
                        Route::patch('/{payment}', 'PaymentsController@update');
                        Route::get('/passengers/{payment}', 'PaymentsController@passengers')->name('payment.passenger');
                    });

                    // my profile
                    Route::prefix('/statistics/my-profile')->group(function () {
                        Route::get('/', 'ProfileController@index')->name('update-provider');
                        Route::patch('/', 'ProfileController@update');
                    });

                    //  // Tickets
                    //  Route::prefix('admin_tickets')->group(function () {
                    //     Route::get('/', 'AdminTicketsController@index')->name('admin_tickets');
                    //     Route::get('/ticket-data', 'AdminTicketsController@indexData')->name('admin_tickets_data');
                    //     Route::get('/create', 'AdminTicketsController@create')->name('create-ticket');
                    //     Route::post('/create', 'AdminTicketsController@store');
                    //     Route::get('/close/{ticket}', 'AdminTicketsController@closed');
                    // });
                });

        });




        /*=========================== Client Authentication Routes ========================*/
            Route::get('/login', 'Auth\Client\LoginController@showLoginForm');
            Route::post('/login', 'Auth\Client\LoginController@login')->name('login-client');

            Route::get('/register', 'Auth\Client\RegisterController@showRegistrationForm')->name('client-register');
            Route::post('/register', 'Auth\Client\RegisterController@register');

            Route::get('/activation', 'Auth\Client\RegisterController@showActivationForm')->name('activation');
            Route::post('/activationPost', 'Auth\Client\RegisterController@activation')->name('activationPost');

            Route::post('/logout', 'Auth\Client\LoginController@logout')->name('logout-client');

        // Password Reset Routes...
        Route::get('password/reset', 'Auth\Client\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'Auth\Client\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('password/reset/{token}', 'Auth\Client\ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/reset', 'Auth\Client\ResetPasswordController@reset');
        /*==================================================================================*/

        Route::get('/', 'HomeController@index')->name('home');
        Route::get('/get-cities/{name}', 'HomeController@cities');

        Route::get('/change/currency', 'HomeController@currency')->middleware('auth:clients');

        Route::namespace('Front')->group(function () {
            Route::post('/subscribes', 'SubscribesController@store')->name('subscribe-front');
            Route::get('/contact-us', 'ContactUsController@index')->name('contact_us');
            Route::post('/contact-us', 'ContactUsController@store');
            Route::get('/pages/{page}', 'PagesController@index');
            Route::get('/faq', 'PagesController@faq');
            Route::get('/providers/registration', 'ProviderController@index');
            Route::post('/providers/registration', 'ProviderController@store')->name('providerRegistration');
            Route::get('/all-offers', 'OffersController@index')->name('all-offers');
            Route::get('/offers/{offer}', 'OffersController@show')->name('client-offer-details');

        });

        Route::middleware('auth:clients')->namespace('Front')->group(function () {

            Route::get('/passenger/offers/{offer}', 'PassengerController@offer')->name('passengerOffer');
            Route::post('/passenger/offers/{offer}', 'PassengerController@offerSubmit');

            Route::get('/passenger/request_offers/{requestOffer}', 'PassengerController@requestOffer')->name('passengerRequestOffer');
            Route::post('/passenger/request_offers/{requestOffer}', 'PassengerController@requestOfferSubmit');

            Route::post('/checkout', 'PassengerController@submit_checkout')->name('chackout');

            Route::get('/offers/summary/{offer}', 'SummaryController@summeryOffer');
            Route::get('/request_offers/summary/{requestOffer}', 'SummaryController@summeryRequestOffer');

            Route::get('/offers/buy/{offer}', 'OffersController@buy')->name('client-buy-offer');
            Route::get('/offers/rate/{offer}', 'OffersController@rate');
            Route::get('/my-offers', 'OffersController@offers')->name('my-offers');
            Route::get('/cancel-request-offers/{requestOffer}', 'OffersController@cancel_request_offer')->name('cancel_request_offer');

            Route::get('/request-offer', 'RequestOfferController@create')->name('client-request-offer');
            Route::get('client/get-events/{id}', 'RequestOfferController@events');
            Route::post('/request-offer', 'RequestOfferController@store');
            // Route::get('/request-offer/show/{requestOffer}', 'RequestOfferController@show');

            Route::get('/request_offer_provider/accept/{request_offer}', 'RequestOfferController@accept')->name('client_accept_offer');
            Route::post('/request_offer_provider/reject/{request_offer}', 'RequestOfferController@reject')->name('client_reject_offer');
            Route::get('/request_offer_provider/buy/{request_offer}', 'RequestOfferController@buy')->name('client_buy_request_offer');

            Route::post('/chat/start/send-message', 'ChatController@start')->name('client_start_chat');
            Route::post('/chat/store/send-message/{chat}', 'ChatController@store')->name('client_store_chat');

            Route::get('/messages', 'ChatController@index')->name('messages');
            Route::get('/chat/delete/{chat}', 'ChatController@destroy');
            Route::get('/chat/details/{chat}', 'ChatController@show');


            Route::get('profile', 'ProfileController@index');
            Route::patch('profile', 'ProfileController@update')->name('client_my_profile');

            Route::get('/notifications', 'NotificationsController@index');
            Route::get('notifications/delete', 'NotificationsController@destroy');
            Route::get('notifications/destroy/{mobileNotification}', 'NotificationsController@delete');


            Route::get('buy/offer/{offer}', 'OrdersController@buyOffer')->name('buyOffer');
            Route::get('buy/request-offer/{requestOffer}', 'OrdersController@buyRequestOffer')->name('buyRequestOffer');

        });



    }
);

