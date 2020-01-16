<?php

namespace App\Http\Controllers\Backend;

use App\Models\Chat;
use App\Models\City;
use App\Models\Country;
use App\Models\Provider;
use App\Models\ChatMessage;
use App\Models\RequestOffer;
use Illuminate\Http\Request;
use App\Models\ProviderCategory;
use Yajra\Datatables\Datatables;
use App\Models\MobileNotification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\RequestOfferProvider;
use App\Http\Requests\Backend\ProviderRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ProvidersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.providers.index');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
        $providers = Provider::with('categories');

        $url = url('/backend/providers');
        return Datatables::of($providers)
            ->addColumn('action', function ($provider) use($url) {
                return '
                <a href="'.$url.'/offers/'.$provider->id.'" class="btn btn-primary m-btn m-btn--icon">'.trans('lang.offers').'</a>
                <a href="'.$url.'/'.$provider->id.'" class="btn btn-success m-btn m-btn--icon">'.trans('lang.update').'</a>
                <a href="'.$url.'/chat/'.$provider->id.'" class="btn btn-info m-btn m-btn--icon">'.trans('lang.chat').'</a>
                <a href="" data-id="'.$provider->id.'" class="btn btn-danger delete-button m-btn m-btn--icon">'.trans('lang.remove').'</a>';
            })
            ->editColumn('first_name', function ($provider){
                // if($provider->logo == null){
                //     return $provider->name;
                // }else{
                //     return $provider->name;
                // }
                return $provider->first_name . ' ' . $provider->last_name;
            })
            ->editColumn('categories', function ($provider){
                $label = [];
                foreach($provider->categories as $category) {
                    if($category->category_id == 1){
                        $label[] = '<span class="m-badge m-badge--default m-badge--wide">'.trans('lang.entertainment').'</span>';
                    }elseif($category->category_id == 2){
                        $label[] = '<span class="m-badge m-badge--default m-badge--wide">'.trans('lang.educational').'</span>';
                    }elseif($category->category_id == 3){
                        $label[] = '<span class="m-badge m-badge--default m-badge--wide">'.trans('lang.sport').'</span>';
                    }else{
                        $label[] = '<span class="m-badge m-badge--default m-badge--wide">'.trans('lang.medical').'</span>';
                    }

                };
                return implode('&nbsp;', $label);
            })

            ->editColumn('is_active', function ($provider) use($url){
                if($provider->is_active == 0){
                    return '<a href="'.$url.'/status/'.$provider->id.'" class="btn btn-warning m-btn m-btn--icon">'.trans('lang.nonactive').'</a>';
                }else{
                    return '<a href="'.$url.'/status/'.$provider->id.'" class="btn btn-success m-btn m-btn--icon">'.trans('lang.active').'</a>';
                }
            })

            ->editColumn('phone', function ($provider) use($url){
                return $provider->code.$provider->phone;
            })
          
            ->rawColumns(['action', 'is_active', 'first_name', 'categories'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }
        $countries = Country::select('id', "$name as name", 'phonecode')->get();
        return view('backend.providers.create', compact('countries'));
    }

    /**
     * Display a listing of the resource.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCities($id)
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }
        $cities = City::select('id', "$name as name")->where('country_id', $id)->get();
        return response($cities);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProviderRequest $request)
    {

        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }
        $inputs = $request->only([
            'first_name', 
            'last_name',
            'email',
            'address',
            'is_active',
        ]);

        $inputs['phone'] = request('phone');
        $inputs['code']  = request('code');
        $country = Country::where('id', $request->country)->first();
        $inputs['country'] = $country->$name;
        $inputs['city']    = $request->city;
        $inputs['password'] = bcrypt($request->password);
        $inputs['logo'] = $request->logo->store('providers');

        DB::transaction(function () use ($inputs) {
            $provider = Provider::create($inputs);

            $requestOffers = RequestOffer::where('is_active', 1)->where('reply_time', '>=', date('Y-m-d'))->where('provider_id', null)->get();
            foreach(request('categories') as $category){

                foreach($requestOffers as $requestOffer){
                    if($requestOffer->category_id == $category){
                        RequestOfferProvider::create([
                            'provider_id' => $provider->id,
                            'request_offer_id' => $requestOffer->id
                        ]);
    
                        $inputs['user_type']        = 6;
                        $inputs['type']             = 'new_request_offer';
                        $inputs['provider_id']      = $provider->id;
                        $inputs['request_offer_id'] = $requestOffer->id;
                        $inputs['client_id']       = $requestOffer->client_id;
                        $this->sendNotification($inputs);
                    }
                }
                ProviderCategory::create([
                    'category_id' => $category,
                    'provider_id' => $provider->id
                ]);
            }

            $chat_inputs = [
                'provider_id'   => $provider->id,
                'type'          => 3,
                'offer_type'    => 0
            ];
            $chat = Chat::create($chat_inputs);
            
            if($inputs['is_active'] == 1){
                $provider->sendWelcomeNotification();
            }else{
                $provider->sendActivateNotification();
            }
        });

        return response('success');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Provider $provider)
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }
        $categories = ProviderCategory::where('provider_id', $provider->id)->pluck('category_id')->toArray();
        $countries  = Country::select('id', "$name as name", 'phonecode')->get();
        return view('backend.providers.show', compact('countries', 'categories', 'provider'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function update(ProviderRequest $request, Provider $provider)
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }
        $inputs = $request->only([
            'first_name',
            'last_name',
            'email',
            'address',
            'is_active',
        ]);
        
        $inputs['phone'] = request('phone');
        $inputs['code']  = request('code');

        // if($request->city != null){
        //     $inputs['city'] = $request->city;
        // }

        // $country = Country::where('id', $request->country)->first();
        // $inputs['country'] = $country->$name;
        if($request->password != null){
            $inputs['password'] = bcrypt($request->password);
        }
        if($request->logo != null){
            $inputs['logo'] = $request->logo->store('providers');
        }

        DB::transaction(function () use ($inputs, $provider) {
            if($inputs['is_active'] != $provider->is_active){
                if((int) $inputs['is_active'] > 0){
                    $provider->sendWelcomeNotification();
                }
            }
            $provider->update($inputs);
            if(request('categories') != null){
                ProviderCategory::where('provider_id', $provider->id)->delete();
                foreach(request('categories') as $category){
                    ProviderCategory::create([
                        'category_id' => $category,
                        'provider_id' => $provider->id
                    ]);
                }
            }
            
            
        });

        return response('success');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provider $provider)
    {
        $provider->delete();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showChat(Provider $provider)
    {
        $chat =  Chat::with('provider', 'messages')->where('type', 3)->where('provider_id', $provider->id)->where('offer_type', 0)->first();
        $chatId = $chat == null ? 0 : $chat->id;
        $messages = ChatMessage::where('chat_id', $chatId)->get();
        
        return view('backend.providers.chat', compact('chat', 'provider', 'messages'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function chat(Request $request, Provider $provider)
    {
        $request->validate([
            'message' => 'required|string',
        ]);


        $inputs = $request->only('message');
        $inputs['provider_id'] = $provider->id;
        $inputs['type'] = 3;

        $chat = Chat::where('type', 3)->where('provider_id', $provider->id)->where('offer_type', 0)->first();
        if($chat == null){
            $chat_inputs = [
                'provider_id'   => $provider->id,
                'type'          => 3,
                'offer_type'    => 0
            ];
            $chat = Chat::create($chat_inputs);
        }
        ChatMessage::create([
            'chat_id' => $chat->id,
            'message' => $request->message,
            'type'    => 3
        ]);


        $notify['provider_id'] = $chat->provider_id;
        $notify['user_type']   = 4;
        $notify['chat_id']     = $chat->id;
        $notify['type']        = 'provider_chat';
        $notify['message_en']  = 'you have new message from mapyyn';
        $notify['message_ar']  = 'لديك رسالة جديدة من ادارة مابين';
        
        MobileNotification::create($notify);
        $this->sendfFcm($notify);
        return back()->with('success', 'success');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Provider $provider
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, Provider $provider)
    {
        if($provider->is_active == 1){
            $provider->is_active = 0;
            $provider->save();
        }else{
            $provider->is_active = 1;
            $provider->save();
        }

        return back();
    }



    private function sendfFcm(array $data)
    {
        $message  = "you have new message from mapyyn" ;
        $token = Provider::where('id', $data['provider_id'])->pluck('fcm_token')->toArray();

            $fcmData = [
                'type'      => 'provider_chat',
                'user_type' => 4,
                'id'        => $data['chat_id']
            ];

        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = [
            'registration_ids' => $token,

            'notification' => [
                "body"     => $message,
                'title'    => 'Mappyen',
                'vibrate'  => 1,
                'sound'    => 1,
                "icon"     => "myicon",
                "color"    => "#2bc0d1"
            ],

            "data" => $fcmData
                
        ];

        $fields = json_encode($fields);

        $headers = array(
            'Authorization: key=AAAADGyngdY:APA91bF5jVOhtMfRZD1c45I0wODjB4KQf7t8nsusua_C3bZjudn96NI5i7CXQu9rFOKhfWaYYuc5Qs_Qb_C34d6O65LOvNAnSeTgmJQSJXVy_qeCJJAvVm3Yv7zCeZ4nzYUvNFg8YV5o',
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        $result = curl_exec($ch);
        //  echo $result;dd($result,"ddddddddddd");
        curl_close($ch);
    }

}
