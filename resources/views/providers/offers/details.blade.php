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
        ?>
        @else
        <?php 
            $name = 'name_en'; 
            $description = 'description_en'; 
        ?>
    @endif
<br>
    <!-- END: Subheader -->
    <div class="row">
        <div class="col-md-12">
            <div class="form-group m-form__group row">
                <div class="col-lg-12 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.description') }}</strong>
                    </label>
                    <p>{{$offer->$description}}</p>
                </div>
            </div>

            <div class="form-group m-form__group row">
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.country') }}</strong>
                    </label>
                    <p>{{$offer->country->$name}}</p>
                </div>
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.location') }}</strong>
                    </label>
                    <p>{{$offer->location}}</p>
                </div>
            </div>

             <div class="form-group m-form__group row">
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.from') }}</strong>
                    </label>
                    {{$offer->from}}
                </div>
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.to') }}</strong>
                    </label>
                    {{$offer->to}}
                </div>
            </div> 

            <div class="form-group m-form__group row">
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.category') }}</strong>
                    </label>
                    <p>
                        @if($offer->category_id == 1)
                            {{trans('lang.entertainment')}}
                        @elseif($offer->category_id == 2)
                            {{trans('lang.educational')}}
                        @elseif($offer->category_id == 3)
                            {{trans('lang.sport')}}
                        @else
                            {{trans('lang.medical')}}
                        @endif
                    </p>
                </div>
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.provider') }}</strong>
                    </label>
                    <p>{{$offer->provider->first_name . ' ' . $offer->provider->last_name}}</p>
                </div>
            </div>

            <div class="form-group m-form__group row">
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.days') }}</strong>
                    </label>
                    <p>
                        {{$offer->days}}
                    </p>
                </div>
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.hotel_level') }}</strong>
                    </label>
                    <p>{{$offer->hotel_level}}</p>
                </div>
            </div>


            

            <div class="form-group m-form__group row">
                <div class="col-lg-6 m-form__group-sub">
                        <label class="form-control-label">
                            <strong>{{ trans('lang.city') }}</strong>
                        </label>
                        <p>{{$offer->city->$name}}</p>
                    </div>
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.prices') }}</strong>
                    </label>
                    <p>{{$offer->price}} {{$offer->currency}}</p>
                </div>
            </div>
 
           

            <div class="form-group m-form__group row">
                @if($offer->category_id == 3)
                    <div class="col-lg-6 m-form__group-sub">
                        <label class="form-control-label">
                            <strong>{{ trans('lang.event_name') }}</strong>
                        </label>
                        <p>
                                {{$offer->event_name}}
                        </p>
                    </div>
                @endif

                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.transport') }}</strong>
                    </label>
                    <p>
                        @if($offer->transport == 'home')
                            {{ trans('lang.home') }}
                        @elseif($offer->transport == 'way')
                            {{ trans('lang.way') }}
                        @else
                            {{ trans('lang.both') }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="form-group m-form__group row">
                
            </div>

            <div class="form-group m-form__group row">
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.persons') }}</strong>
                    </label>
                    <p>{{$offer->persons}}</p>
                </div>
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.end_at') }}</strong>
                    </label>
                    <p>{{$offer->end_at}}</p>
                </div>
            </div>

            <div class="form-group m-form__group">
                <label></label>
                @foreach ($offer->images as $image)
                    <a href='{{asset("uploads/$image->image")}}' data-lity><img src='{{asset("uploads/$image->image")}}' width="100" height="100"></a>
                @endforeach
            </div>

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