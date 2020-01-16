@extends('layouts.master')

@section('title') {{trans('lang.offers')}} @endsection

@section('css')
    @if(LaravelLocalization::getCurrentLocaleDirection() == 'ltr')  
        <link href="{{asset('assets/css/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
    @else
        <link href="{{asset('assets/css/datatables.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    @endif
    <link rel="stylesheet" href="{{asset('assets/sweetalert.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/lity.min.css')}}">
@endsection

@section('content')
<!-- BEGIN: Subheader -->
    @if(LaravelLocalization::getCurrentLocale() == 'ar')
        <?php 
            $name = 'name_ar'; 
            $description = 'description_ar'; 
            $from_city = 'from_city_ar';
            $to_city   = 'to_city_ar';
            $from_country = 'from_country_ar';
            $to_country   = 'to_country_ar';
        ?>
        @else
        <?php 
            $name = 'name_en'; 
            $description = 'description_en'; 
            $from_city = 'from_city_en';
            $to_city   = 'to_city_en';
            $from_country = 'from_country_en';
            $to_country   = 'to_country_en';
        ?>
    @endif
<br>
    <!-- END: Subheader -->
    <div class="row">
        <div class="col-md-12">
            <div class="form-group m-form__group row">
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.from_country') }}</strong>
                    </label>
                    <p>{{$requestOffer->$from_country}}</p>
                </div>
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.from_city') }}</strong>
                    </label>
                    <p>{{$requestOffer->$from_city}}</p>
                </div>
            </div>

            <div class="form-group m-form__group row">
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.to_country') }}</strong>
                    </label>
                    <p>{{$requestOffer->$to_country}}</p>
                </div>
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.to_city') }}</strong>
                    </label>
                    <p>{{$requestOffer->$to_city}}</p>
                </div>
            </div>

            <div class="form-group m-form__group row">
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.go_date') }}</strong>
                    </label>
                    <p>{{$requestOffer->go_date}}</p>
                </div>
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.back_date') }}</strong>
                    </label>
                    <p>{{$requestOffer->back_date}}</p>
                </div>
            </div>

             

            <div class="form-group m-form__group row">
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.category') }}</strong>
                    </label>
                    <p>
                        @if($requestOffer->category_id == 1)
                            {{trans('lang.entertainment')}}
                        @elseif($requestOffer->category_id == 2)
                            {{trans('lang.educational')}}
                        @elseif($requestOffer->category_id == 3)
                            {{trans('lang.sport')}}
                        @else
                            {{trans('lang.medical')}}
                        @endif
                    </p>
                </div>

                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.transport') }}</strong>
                    </label>
                    <p>
                        @if($requestOffer->transport == 'home')
                            {{ trans('lang.home') }}
                        @elseif($requestOffer->transport == 'way')
                            {{ trans('lang.way') }}
                        @else
                            {{ trans('lang.both') }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="form-group m-form__group row">
                <div class="col-lg-6 m-form__group-sub">
                        <label class="form-control-label">
                            <strong>{{ trans('lang.hotel_level') }}</strong>
                        </label>
                        <p>{{$requestOffer->hotel_level}}</p>
                    </div>
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.days') }}</strong>
                    </label>
                    <p>{{$requestOffer->days}}</p>
                </div>
            </div>


            <div class="form-group m-form__group row">
                <div class="col-lg-6 m-form__group-sub">
                        <label class="form-control-label">
                            <strong>{{ trans('lang.adult') }}</strong>
                        </label>
                        <p>{{$requestOffer->adult}}</p>
                    </div>
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.children') }}</strong>
                    </label>
                    <p>{{$requestOffer->children}}</p>
                </div>
            </div>

            <div class="form-group m-form__group row">
                <div class="col-lg-6 m-form__group-sub">
                        <label class="form-control-label">
                            <strong>{{ trans('lang.babies') }}</strong>
                        </label>
                        <p>{{$requestOffer->babies}}</p>
                    </div>
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.reply_time') }}</strong>
                    </label>
                    <p>{{$requestOffer->reply_time}}</p>
                </div>
            </div>

            <div class="form-group m-form__group row">
                <div class="col-lg-6 m-form__group-sub">
                        <label class="form-control-label">
                            <strong>{{ trans('lang.prices') }}</strong>
                        </label>
                        <p>{{$requestOffer->price}}</p>
                    </div>
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.status') }}</strong>
                    </label>
                    <p>{{trans("lang.$requestOffer->status")}}</p>
                </div>
            </div>


            <div class="form-group m-form__group row">
                <div class="col-lg-6 m-form__group-sub">
                        <label class="form-control-label">
                            <strong>{{ trans('lang.client') }}</strong>
                        </label>
                        <p>{{$requestOffer->client->first_name . ' ' . $requestOffer->client->last_name}}</p>
                    </div>
                @if($requestOffer->provider != null)
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.provider') }}</strong>
                    </label>
                    <p>{{$requestOffer->provider->first_name . ' ' . $requestOffer->provider->last_name}}</p>
                </div>
                @endif
            </div>

            <div class="form-group m-form__group row">
                <div class="col-lg-6 m-form__group-sub">
                        <label class="form-control-label">
                            <strong>{{ trans('lang.client_phone') }}</strong>
                        </label>
                        <p>{{$requestOffer->client->phone}}</p>
                    </div>
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.client_email') }}</strong>
                    </label>
                    <p>{{$requestOffer->client->email}}</p>
                </div>
            </div>

            

            <div class="form-group m-form__group row">
                @if($requestOffer->note != null)
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.note') }}</strong>
                    </label>
                    <p>
                        {{$requestOffer->note}}
                    </p>
                </div>
                @endif
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.trip_stop') }}</strong>
                    </label>
                    <p>
                        {{trans("lang.$requestOffer->trip_stop")}}
                    </p>
                </div>
                @if($requestOffer->category_id == 3)
                    <div class="col-lg-6 m-form__group-sub">
                        <label class="form-control-label">
                            <strong>{{ trans('lang.event_name') }}</strong>
                        </label>
                        <p>
                                {{$requestOffer->event_name}}
                        </p>
                    </div>
                @endif

                @if($requestOffer->change_date != null)
                    <div class="col-lg-6 m-form__group-sub">
                        <label class="form-control-label">
                            <strong>{{ trans('lang.change_date') }}</strong>
                        </label>
                        <p>
                                {{trans("lang.$requestOffer->change_date")}}
                        </p>
                    </div>
                @endif

                @if($requestOffer->help != null)
                    <div class="col-lg-6 m-form__group-sub">
                        <label class="form-control-label">
                            <strong>{{ trans('lang.help') }}</strong>
                        </label>
                        <p>
                            {{trans("lang.$requestOffer->help")}}
                        </p>
                    </div>
                @endif

            </div>

            @if ($requestOffer->interests()->count() > 0)
                <div class="form-group m-form__group row">
                    
                        <div class="col-lg-2 m-form__group-sub">
                            <label class="form-control-label">
                                <strong>{{ trans('lang.interests') }}</strong>
                            </label>
                            @foreach ($requestOffer->interests as $interest)
                                    <p>{{trans("lang.$interest->title")}}</p>
                            @endforeach

                        </div>
                </div>
            @endif
            
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('assets/js/datatables.bundle.js')}}" type="text/javascript"></script>
    {{--  <script src="{{asset('assets/backend/js/buttons.js')}}" type="text/javascript"></script>  --}}
    <script src="{{asset('assets/js/lity.min.js')}}"></script>
    @if(LaravelLocalization::getCurrentLocale() == 'ar')
        <script>let cancelButton = 'إلغاء';</script>
    @else
        <script>let cancelButton = 'Cancel';</script>
    @endif
    <script src="{{asset('assets/sweetalert.min.js')}}"></script>
@endsection