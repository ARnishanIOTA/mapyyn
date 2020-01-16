<?php

namespace App\Http\Controllers\Api\Clients;

use App\Models\Rate;
use App\Models\Offer;
use App\Models\Provider;
use App\Models\RequestOffer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;

class RateController extends ApiController
{
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'rate' => ['required','integer', Rule::in([1,2,3,4,5,6,7,8,9,10])],
        ];

        if($request->type == 'offer'){
            $rules['offer_id'] = 'required|integer|exists:offers,id';
        }else{
            $rules['offer_id'] = 'required|integer|exists:request_offers,id';
        }
        $request->validate($rules);

        if($request->type == 'offer'){
            $check = Rate::where('offer_id', $request->offer_id)
            ->where('client_id', $this->client()->id())->exists();
        }else{
            $check = Rate::where('request_offer_id', $request->offer_id)
            ->where('client_id', $this->client()->id())->exists();
        }
        

        if($check){
            if(request('lang') == 'ar'){
                return $this->apiResponse((object)[], 'التقييم موجود من قبل', 404);
            }else{
                return $this->apiResponse((object)[], 'rate already exists', 404);
            }
        }

        DB::transaction(function () use ($request) {
            if($request->type == 'offer'){
                Rate::create([
                    'rate' => $request->rate,
                    'offer_id' => $request->offer_id,
                    'client_id' => $this->client()->id(),
                    'type' => 'offer'
                ]);

                $rates = Rate::where('type', 'offer')->where('offer_id', $request->offer_id)->sum('rate');
                $count = Rate::where('type', 'offer')->where('offer_id', $request->offer_id)->get()->count();

                Offer::where('id', $request->offer_id)->update([
                    'rate' => $rates / $count
                ]);

                $offer = Offer::where('id', $request->offer_id)->first();
                Provider::where('id', $offer->provider_id)->update([
                        'rate' => $rates / $count
                    ]);
            }else{
                Rate::create([
                    'rate' => $request->rate,
                    'request_offer_id' => $request->offer_id,
                    'client_id' => $this->client()->id(),
                    'type' => 'request_offer'
                ]);
                $rates = Rate::where('type', 'request_offer')->where('request_offer_id', $request->offer_id)->sum('rate');
                $count = Rate::where('type', 'request_offer')->where('request_offer_id', $request->offer_id)->get()->count();
                
                RequestOffer::where('id', $request->offer_id)->update([
                    'rate' => $rates / $count
                ]);
            }

            
        });

        if(request('lang') == 'ar'){
            return $this->apiResponse(['success' => 'تم بنجاح']);
        }else{
            return $this->apiResponse(['success' => 'Successfully']);
        }
        
    }

}
