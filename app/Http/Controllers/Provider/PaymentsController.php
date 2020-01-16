<?php

namespace App\Http\Controllers\Provider;

use App\Models\Client;
use App\Models\Payment;
use App\Models\Passenger;
use App\Models\ClientOffer;
use App\Models\RequestOffer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\PaymentAttachment;
use App\Models\MobileNotification;
use App\Http\Controllers\Controller;
use App\Models\RequestOfferProvider;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::with(['offer', 'client' => function($query) {
            $query->select('id', 'first_name', 'last_name', 'email', 'phone');
        }, 'requestOffer', 'files'])->where('provider_id', auth('providers')->id())->get();

        return view('providers.payments.index', compact('payments'));
    }

    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        if($payment->provider_id != auth('providers')->id()){
            return back();
        }

        return view('providers.payments.update', compact('payment'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        if($payment->provider_id != auth('providers')->id()){
            return response('exists');
        }

        $request->validate([
            'status'   => ['required', Rule::in(['processing', 'paid'])],
            'files'    => 'required|array|max:3',
            'files.*'  => 'required|image|max:5000'
        ]);

        $old = $payment->sataus;
        $payment->update(['status' => request('status')]);

        foreach (request('files') as $file) {
            PaymentAttachment::create([
                'file'       => $file->store('attachment'),
                'payment_id' => $payment->id
            ]);
        }
       
        
        // foreach(request('files') as $file){
        //     PaymentAttachment::create([
        //         'file' => $file->store('attachment'),
        //         'payment_id' => $payment->id
        //     ]);
        // }

        if(request('status') == 'paid'){
            if($payment->offer_id != null){
                $number = $payment->offer_id;
                $notify['type'] = 'payment_offer';
                $notify['offer_id'] = $payment->offer_id;
                ClientOffer::where('offer_id', $payment->offer_id)->where('client_id', $payment->client_id)->update([
                    'status'    => 'paid'
                ]);
                $inputs['offer_id'] = $number;
                $notify['message_en']   = "Provider has change payment status for request number : " .  $number  . ' And upload your travel ticket ';
                $notify['message_ar']  = "قام مزود الخدمة بتغيير حالة الدفع للطلب رقم : "  .  $number  . ' وقام برفع تذاكر السفر الخاصة بك';
            }else{
                $number = $payment->request_offer_id;
                $notify['request_offer_id'] = $payment->request_offer_id;
                $notify['type'] = 'payment_request_offer';
                $requestOffer = RequestOffer::where('id', $payment->request_offer_id)->first();
                RequestOfferProvider::where('request_offer_id', $requestOffer->id)->where('provider_id', $requestOffer->provider_id)->update(['status' => 'paid']);
                $requestOffer->update(['status' => 'paid']);
                $inputs['request_offer_id'] = $number;

                $notify['message_en']   = "Provider has change payment status for request number : " .  $number  . ' And upload your travel ticket ';
                $notify['message_ar']  = "قام مزود الخدمة بتغيير حالة الدفع للطلب رقم : "  .  $number  . ' وقام برفع تذاكر السفر الخاصة بك';

            }
            
            $notify['client_id']   = $payment->client_id;
            $notify['user_type']   = 14;
            MobileNotification::create($notify);
            $notify['number'] = $number;

            $inputs['user_type']   = 36;
            $inputs['type']        = 'admin_payment_status';
            $inputs['message_en']   = "Provider has change payment status for request number : " .  $number ;
            $inputs['message_ar']  = "قام مزود الخدمة بتغيير حالة الدفع للطلب رقم : "  .  $number ;
            
            MobileNotification::create($inputs);

            $client = Client::find($payment->client_id);
            $client->sendComplatePaymentNotification();
            $this->sendFcm($notify);

            
        }
        return response('success');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function passengers(Payment $payment)
    {
        if($payment->provider_id != auth('providers')->id()){
            return back();
        }
        $passengers = Passenger::where('payment_id', $payment->id)->get();

        return view('providers.payments.passengers', compact('passengers'));
    }



    private function sendFcm(array $data)
    {
        $message  = "Provider has change payment status for request number : " . $data['number'] ;
        $token = \App\Models\Client::where('id', $data['client_id'])->pluck('fcm_token')->toArray();

        $fcmData = [
            'type'      => $data['type'],
            'id'        => $data['number'],
            'user_type' => 14,
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


    /**
     * download attachment 
     * @param Payment $payment
     * 
     * @return file
     */
    public function download(Payment $payment)
    {
        $files = PaymentAttachment::where('payment_id', $payment->id)->pluck('file')->toArray();
        $attachments = 'file.zip';
        $zip = new ZipArchive;
        $zip->open($attachments, ZipArchive::CREATE);
        foreach ($files as $file) {
            $zip->addFile($file);
        }
        $zip->close();

        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$attachments);
        header('Content-Length: ' . filesize($attachments));
        readfile($attachments);
    }

 
}
