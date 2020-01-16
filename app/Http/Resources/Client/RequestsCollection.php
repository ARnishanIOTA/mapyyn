<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RequestsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if(request('lang') == 'ar'){
            $from_city = 'from_city_ar';
            $to_city   = 'to_city_ar';

            $from_country = 'from_country_ar';
            $to_country   = 'to_country_ar';
        }else{
            $from_city = 'from_city_en';
            $to_city   = 'to_city_en';

            $from_country = 'from_country_en';
            $to_country   = 'to_country_en';
        }
        return $this->collection->transform(function($page) use($from_city, $to_city, $from_country, $to_country){
            if($page->payment == null){
                $files = [];
            }else{
                if($page->payment->files->count() <= 0){
                    $files = [];
                }else{
                    $files = $page->payment->files;
                }
            }
            return [
                'id'       => $page->id,
                'provider_id'  => $page->provider_id,
                'client_id'=> $page->client_id,
                'from_country'=>  $page->$from_country,
                'from_city'=>  $page->$from_city,
                'to_country'=>  $page->$to_country,
                'to_city'=>  $page->$to_city,
                'go_date'=>  $page->go_date,
                'back_date'=>  $page->back_date,
                'trip_stop'=>  $page->trip_stop,
                'transport'=>  $page->transport,
                'hotel_level'=> $page->hotel_level,
                'category_id'=> $page->category_id,
                'league'=> $page->league,
                'event_name'=> $page->event_name,
                'days'=> $page->days,
                'adult'=> $page->adult,
                'children'=> $page->children,
                'babies'=> $page->babies,
                'rate'=> $page->rate,
                'note'=> $page->note,
                'reply_time'=>  $page->reply_time,
                'help'=> $page->help,
                'price'=>  $page->price,
                'change_date'=>  $page->change_date,
                'status'=>  $page->status,
                'is_active'=> $page->is_active,
                'created_at' =>  date('Y-m-d', strtotime($page->created_at)),
                'updated_at' =>  date('Y-m-d', strtotime($page->updated_at)),
                'provider' => $page->provider,
                'provider_offer' => $page->providerOffer,
                'files'          => $files,
                'interests' => $page->interests
            ];
        });
    }
}
