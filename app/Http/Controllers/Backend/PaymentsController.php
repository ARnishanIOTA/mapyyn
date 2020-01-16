<?php

namespace App\Http\Controllers\Backend;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $total_payments  = Payment::count();
        $total_payments_price  = Payment::sum('price');
        $month_payments  = Payment::where('month', date('Y-m'))->count();
        $month_payments_price  = Payment::where('month', date('Y-m'))->sum('price');
        $proccess_payments  = Payment::where('status', 'processing')->count();
        $proccess_payments_price  = Payment::where('status', 'processing')->sum('price');
        $done_payments  = Payment::where('status', 'paid')->count();
        $done_payments_price  = Payment::where('status', 'paid')->sum('price');
        $hold_payments  = Payment::where('admin_status', 'hold')->count();
        $hold_payments_price  = Payment::where('admin_status', 'hold')->sum('price');
        $billed_payments  = Payment::where('admin_status', 'billed')->count();
        $billed_payments_price  = Payment::where('admin_status', 'billed')->sum('price');
        $refund_payments  = Payment::where('admin_status', 'refund')->count();
        $refund_payments_price  = Payment::where('admin_status', 'refund')->sum('price');

        return view('backend.payments.index', [
            'total_payments' => $total_payments,
            'total_payments_price' => $total_payments_price,
            'month_payments' => $month_payments,
            'month_payments_price' => $month_payments_price,
            'proccess_payments' => $proccess_payments,
            'proccess_payments_price' => $proccess_payments_price,
            'done_payments' => $done_payments,
            'done_payments_price' => $done_payments_price,
            'hold_payments' => $hold_payments,
            'hold_payments_price' => $hold_payments_price,
            'billed_payments' => $billed_payments,
            'billed_payments_price' => $billed_payments_price,
            'refund_payments' => $refund_payments,
            'refund_payments_price' => $refund_payments_price,
        ]);
    }

    
    
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_data()
    {
        $payments = Payment::with('client', 'provider', 'offer', 'requestOffer', 'files');
        return DataTables::of($payments)
        ->addColumn('action', function ($payment){
            return '
            <a href="'.route('admin.payments.show', $payment->id).'" class="btn btn-success m-btn m-btn--icon">
            <span>'.trans('lang.update').' </span></span></a>
            ';
        })
        ->editColumn('client.first_name', function ($payment) {
            return optional($payment->client)->first_name . ' ' . optional($payment->client)->last_name;
        })
        ->editColumn('provider.first_name', function ($payment) {
            return '<a href="'.url('/backend/providers/'.$payment->provider_id).'" target="_blank">'.optional($payment->provider)->first_name . ' ' . optional($payment->provider)->last_name.'</a>';
        })
        ->editColumn('status', function ($payment) {
            if($payment->status == 'processing'){
                return '<label class="m-badge m-badge--primary m-badge--wide">'.trans("lang.processing").'</td></label>';
            }else{
                return '<label class="m-badge m-badge--success m-badge--wide">'.trans("lang.paid").'</td></label>';
            }
        })
        ->editColumn('admin_status', function ($payment) {
            if($payment->admin_status == 'refund'){
                return '<label class="m-badge m-badge--primary m-badge--wide">'.trans("lang.refund").'</td></label>';
            }elseif($payment->admin_status == 'billed'){
                return '<label class="m-badge m-badge--success m-badge--wide">'.trans("lang.billed").'</td></label>';
            }else{
                return '<label class="m-badge m-badge--warning m-badge--wide">'.trans("lang.hold").'</td></label>';
            }
        })
        ->addColumn('attachment', function ($payment) {
            $lable = '';
            foreach ($payment->files as $item) {
                $lable .= '<a href="'.asset("uploads/$item->file").'" download >
                    <span><span>'.trans('lang.download').'</span></span></a>&nbsp;&nbsp';
            }

            return $lable;
        })
        ->addColumn('offer', function ($payment) {
            return $payment->offer_id == null ? 
            '<a href="'.url('/backend/request_offers/details/'.$payment->request_offer_id).'" target="_blank">'.trans('request_offer'). ' - ' . $payment->request_offer_id .'</a>' :
            '<a href="'.url('/backend/providers/offers/details/'.$payment->offer_id).'" target="_blank">'.trans('offer'). ' - ' . $payment->offer_id .'</a>';
        })
        ->rawColumns(['action', 'status', 'admin_status', 'attachment', 'client.first_name', 'provider.first_name', 'offer'])
        ->make(true);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        return view('backend.payments.update', compact('payment'));
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
        $request->validate([
            'admin_status'   => ['required', Rule::in(['refund', 'billed', 'hold'])],
            'notes'     => 'required|string',
        ]);

        $payment->update(['admin_status' => request('admin_status'), 'notes' => request('notes')]);

        return response('success');
    }

 
}
