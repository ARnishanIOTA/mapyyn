<?php

namespace App\Console;

use App\Models\Provider;
use App\Models\RequestOffer;
use App\Models\MobileNotification;
use App\Models\RequestOfferProvider;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            
            $offers = RequestOffer::where('reply_time', '<', date('Y-m-d'))->where('status', '!=', 'closedWithoutOffer')->where('provider_id', null);
            $ids = $offers->pluck('id');
            $offers = $offers->update(['status' => 'closedWithoutOffer']);
            

            RequestOfferProvider::whereIn('request_offer_id', $ids)->where('status', 'pending')->update(['status' => 'closed']);
            RequestOfferProvider::whereIn('request_offer_id', $ids)->where('status', 'waiting')->update(['status' => 'closed']);

            $requestOffers = RequestOfferProvider::whereIn('request_offer_id', $ids)->where('status', 'closed')->get();

            foreach ($requestOffers as $requestOffer) {
                $inputs['message_en'] = "request offer number : $requestOffer->request_offer_id has been closed" ;
                $inputs['message_ar'] = "العرض المقدم رقم : $requestOffer->request_offer_id  تم اغلاقه" ;
                $inputs['request_offer_id'] = $requestOffer->request_offer_id; 
                $inputs['provider_id'] = $requestOffer->provider_id;
                $inputs['type'] = 'request_closed';
                $inputs['user_type'] = 10;
                MobileNotification::create($inputs);
            } 

            // $ids = RequestOfferProvider::whereIn('request_offer_id', $ids)->whereNotNull('price')->where('status', 'closed')->pluck('provider_id');
            // $providers = Provider::whereIn('id', $ids)->pluck('fcm_token')->toArray();
            // $inputs['token'] = $providers;

            // $this->sendFcm($inputs);
        })->dailyAt('23:00');
    }


    // private function sendFcm(array $data)
    // {

    //     $url = 'https://fcm.googleapis.com/fcm/send';

    //     $fcmData = [
    //         'type'      => 'request_closed',
    //         'id'        => $data['request_offer_id'],
    //         'user_type' => 10,
    //     ];

    //     $fields = [
    //         'registration_ids' => $data['token'],

    //         'notification' => [
    //             "body"     => $data['message_en'],
    //             'title'    => 'Mappyen',
    //             'vibrate'  => 1,
    //             'sound'    => 1,
    //             "icon"     => "myicon",
    //             "color"    => "#2bc0d1"
    //         ],

    //         "data" => $fcmData
                
    //     ];

    //     $fields = json_encode($fields);

    //     $headers = array(
    //         'Authorization: key=AAAADGyngdY:APA91bF5jVOhtMfRZD1c45I0wODjB4KQf7t8nsusua_C3bZjudn96NI5i7CXQu9rFOKhfWaYYuc5Qs_Qb_C34d6O65LOvNAnSeTgmJQSJXVy_qeCJJAvVm3Yv7zCeZ4nzYUvNFg8YV5o',
    //         'Content-Type: application/json'
    //     );

    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    //     $result = curl_exec($ch);
    //     //  echo $result;dd($result,"ddddddddddd");
    //     curl_close($ch);
    // }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
