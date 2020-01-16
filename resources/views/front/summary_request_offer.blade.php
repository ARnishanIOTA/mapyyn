@extends('layouts.front')

@section('title') {{ trans('lang.summary') }} @endsection

@section('content')

<div class="login my-5  show-info ">
        <style>
            .show-info label {
                font-size: 12px;
            }
        </style>
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="data-login border p-4 bg-white">
                        <h5 class="mb-5  text-center "> {{ trans('lang.continueData') }}  </h5>
                        @foreach ($passengers as $passenger)
                            @if ($passenger['request_offer_id'] == $requestOffer->id)
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="text-muted" > {{ trans('lang.name') }} </label>
                                        <p>{{$passenger['name']}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted" > {{ trans('lang.birthdate') }} </label>
                                        <p>  {{$passenger['birthdate']}} </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="text-muted" > {{ trans('lang.first_name') }} </label>
                                        <p>   {{$passenger['first_name']}}   </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted" >{{ trans('lang.last_name') }} </label>
                                        <p>  {{$passenger['last_name']}} </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="text-muted" > {{ trans('lang.passportCountry') }} </label>
                                        <p>   {{$passenger['passport_country']}}   </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted" >{{ trans('lang.passportNumber') }} </label>
                                        <p>  {{$passenger['passport_number']}} </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="text-muted" > {{ trans('lang.nationality') }} </label>
                                        <p>   {{$passenger['nationality']}}   </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted" > {{ trans('lang.passportEndDate') }} </label>
                                        <p>   {{$passenger['passport_end_date']}}   </p>
                                    </div>
                                </div>
                                <hr>
                                @endif
                        @endforeach

                
                    <form action="{{route('buyRequestOffer', ['id' => $requestOffer->id])}}" class="paymentWidgets" data-brands="VISA MASTER AMEX" href="qa-html-language-declarations.ar">
                        @csrf
                    </form>
               
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<?php $key = Session::get('checkoutId'); ?>
    @if ($key != null)
        <script src="https://oppwa.com/v1/paymentWidgets.js?checkoutId={{ $key->id }}"></script>
    @endif
@endsection