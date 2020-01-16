@extends('layouts.front')

@section('title') {{ trans('lang.request-offer-details') }} @endsection
@section('css')

<link rel="stylesheet" href="{{asset('assets/css/lity.min.css')}}">
@endsection
@section('content')
<?php
if(LaravelLocalization::getCurrentLocale() == 'ar'){
    $from_city   = 'from_city_ar';
        $to_city   = 'to_city_ar';
        $from_country = 'from_country_ar';
        $to_country   = 'to_country_ar';
}else{
    $from_city   = 'from_city_en';
        $to_city   = 'to_city_en';
        $from_country = 'from_country_en';
        $to_country   = 'to_country_en';
}
?>

<div class="details-offer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if(session('success'))
                        <div class="alert alert-success" style="text-align:center">
                            {{session('success')}}
                        </div>
                    @endif
                    @if(session('danger'))
                        <div class="alert alert-danger" style="text-align:center">
                            {{session('danger')}}
                        </div>
                    @endif
                        <?php
                        if($requestOffer->category_id == 1){
                            $category = trans('lang.entertainment');
                        }elseif($requestOffer->category_id == 2){
                            $category = trans('lang.educational');
                        }elseif($requestOffer->category_id == 3){
                            $category = trans('lang.sport') ;
                        }else{
                            $category = trans('lang.medical');
                        }
                    ?>
                    <h6> <a href="{{url('/')}}"> {{ trans('lang.main') }} </a> / <a href="#">{{$category}}</a> 
                    / <a href="#" class="active">{{ trans('lang.buy_trip') }}</a>
                        </h6>
                    <h3 class="mt-4">{{ trans('lang.trip_information') }}</h3>
                </div>
                <div class="col-md-12">
                    <div class="desc-offer">
                        <div class="row">
                            <div class="col-md-4 border-right">
                                <div class="first-desc">
                                    <div class="row">
                                        <div class="col-2">
                                            <span><img class="img-fluid" src="{{asset('img/flag.png')}}" alt="flag"> </span>
                                        </div>
                                        <div class="col-10">
                                            <h5> {{$requestOffer->$to_country}} - {{$requestOffer->$to_city}}</h5>
                                            <p> {{$providerOffer->provider->first_name . ' ' . $providerOffer->provider->last_name}}</p>
                                                <ul class="list-inline mt-2">
                                                    {{$requestOffer->rate }} / 10
                                                </ul>
                                            {{-- <small> 20-10-2018 </small> --}}

                                            @if($payment != null)
                                                @if ($payment->count() > 0)
                                                    <h5> {{ trans('lang.continueData') }}</h5>
                                                    <?php $p_count = 1; ?>
                                                    @foreach($payment as $key => $p)
                                                        <a href="{{asset("uploads/$p->file")}}" download >{{$p_count}} - {{ trans('lang.download') }}</a>
                                                        <?php $p_count = $p_count + 1;?> 
                                                    @endforeach
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="col-md-5 border-right">
                                <div class="tow-desc">
                                        <ul class="list-unstyled">
                                            <li> {{ trans('lang.hotel_level') }} : <span class="float-right"> 
                                                @for ($i = 0; $i < $requestOffer->hotel_level; $i++)
                                                    <li class="list-inline-item"><i class="far fa-star"></i></li>
                                                @endfor
                                            </span> </li>

                                            <li>{{ trans('lang.num_persons') }} :<span class="float-right"> {{$requestOffer->adult + $requestOffer->babies + $requestOffer->children}} {{trans('lang.persons')}} </span> </li>
                                            <li>{{ trans('lang.transport') }} : <span class="float-right"> {{trans("lang.$requestOffer->transport")}} </span></li>
                                        </ul>
                                </div>
                            </div>
                            <!--  -->
                            <div class="col-md-3 text-center">
                                <div class="three-desc">
                                    <div class="row">
                                        {{-- <div class="col-2">
                                            <img class="img-fluid d-block mx-auto" src="{{asset('img/offers.png')}}" alt="offers">
                                        </div> --}}
                                        <div class="col-10">
                                                <p>{{$requestOffer->days}} {{trans('lang.days')}} - {{$requestOffer->days - 1}} {{trans('lang.night')}}</p>
                                                <h6>{{ trans('lang.price') }}</h6>
                                                <h5> {{trans("lang.$requestOffer->price")}} </h5>
                                                <h6>{{ trans('lang.provider_price') }}</h6>
                                                <h5> {{$providerOffer->price}} </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  -->

                            <div class="col-md-12">
                                <div class="slider-photo">
                                    <hr>
                                    @if($requestOffer->note != null)
                                        <h6 class="mb-3"> {{ trans('lang.note') }}</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="mt-2">{{$requestOffer->note}}</p>
                                            </div>
                                        </div>
                                    @endif
                                    @if($providerOffer->description != null)
                                        <h6 class="mb-3"> {{ trans('lang.description') }}</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="mt-2">{{$providerOffer->description}}</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($requestOffer->interests()->count() > 0)
                                        <h6 class="mb-3"> {{ trans('lang.interests') }}</h6>
                                        <div class="row">
                                            @foreach ($requestOffer->interests as $interest)
                                                <div class="col-md-2">
                                                    <p class="mt-2">{{trans("lang.$interest->title")}}</p>
                                                </div>
                                            @endforeach

                                        </div>
                                    @endif
                                    <div class="row">
                                        @if($providerOffer->image != null)
                                            <div class="col-md-9">
                                                <?php $image = $providerOffer->image; ?>
                                                <a href="{{asset("uploads/$image")}}" data-lity><img src='{{asset("uploads/$image")}}' height="100" width="100"></a>
                                            </div>
                                        @endif

                                        @if($requestOffer->provider_id == null )
                                            <div class="col-md-3">
                                                <button class="btn btn-success btn-block" onclick="location.href='{{url('/passenger/request_offers/'.$requestOffer->id)}}';">{{ trans('lang.complate_payment') }}</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


{{-- <div class="payment-gateway">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="payment-complate">
                  <h4 class="text-center"> Payment gateway goes here </h4>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection

@section('js')

<script src="{{asset('assets/js/lity.min.js')}}"></script>
@endsection

