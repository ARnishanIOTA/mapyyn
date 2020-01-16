@extends('layouts.master')

@section('title') {{trans('lang.requests_offers')}} @endsection

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
            $to_city = 'to_city_ar';
            $from_city = 'from_city_ar';
            $from_country = 'from_country_ar';
            $to_country   = 'to_country_ar';
        ?>
        @else
        <?php 
            $name = 'name_en'; 
            $description = 'description_en';
            $to_city = 'to_city_en';
            $from_city = 'from_city_en';
            $from_country = 'from_country_en';
            $to_country   = 'to_country_en';
        ?>
    @endif
<br>
    <!-- END: Subheader -->
    <div class="row">
        <div class="col-md-12">
                <div class="form-group m-form__group">
                        <div style="display:none" class="alert alert-success" id="successMessage" role="alert"><strong>{{ trans('lang.wellDone') }}</strong>
                            {{ trans('lang.successMessage') }}
                        </div>
                    </div> 
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
                    @if($status == null)
                        <p>{{trans("lang.$requestOffer->status")}}</p>
                    @else
                        <p>{{trans("lang.$status->status")}}</p>
                    @endif
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
            </div>
            <div class="form-group m-form__group row">
                <div class="col-lg-6 m-form__group-sub">
                    <label class="form-control-label">
                        <strong>{{ trans('lang.client') }}</strong>
                    </label>
                    <p>{{$requestOffer->client->first_name. ' ' .$requestOffer->client->last_name}}</p>
                </div>
                @if($status != null)
                    @if ($status->status == 'reject')
                        <div class="col-lg-6 m-form__group-sub">
                            <label class="form-control-label">
                                <strong>{{ trans('lang.reason') }}</strong>
                            </label>
                            <p>{{$status->reason}}</p>
                        </div>
                    @endif
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

            @if ($interests->count() > 0)
            <div class="form-group m-form__group row">
                        <div class="col-lg-2 m-form__group-sub">
                            <label class="form-control-label">
                                <strong>{{ trans('lang.interests') }}</strong>
                            </label>
                            @foreach ($interests as $interest)
                                    <p>{{trans("lang.$interest->title")}}</p>
                            @endforeach

                        </div>
                </div>
            @endif
        </div>
    </div>
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title ">{{ trans('lang.reply_request') }}</h3>
            </div>
        </div>
    </div>
   
            <div class="row">
                <div class="col-md-12">
                    <!--begin::Form-->
                    

                <form id="modelForm" class="m-form m-form--fit m-form--label-align-right m-form--state" method="POST" action="{{ route('reply-request', ['id' => $requestOffer->id]) }}" enctype="multipart/form-data">
                       
                    @csrf
                        <div class="m-portlet__body">
                                
                            <div class="form-group m-form__group" id="priceGroup">
                                <label for="price">{{ trans('lang.price') }}</label>
                            <input type="text" name="price" value="{{$status == null ? '' : $status->price}}" class="form-control m-input m-input--square" id="price" placeholder="{{trans('lang.price')}}">
                                <div class="form-control-feedback" id="priceMessage"></div>
                            </div>

                            <div class="form-group m-form__group" id="descriptionGroup">
                                <label for="description">{{ trans('lang.description') }}</label>
                                <textarea type="text" name="description" class="form-control m-input m-input--square" id="description" placeholder="{{trans('lang.description')}}">{{$status == null ? '' : $status->description}}</textarea>
                                <div class="form-control-feedback" id="descriptionMessage"></div>
                            </div>

                            @if($status->price == null)
                                <div class="form-group m-form__group" id="imageGroup">
                                    <label for="image">{{ trans('lang.image') }}</label>
                                    <input type="file" accept="image/*" name="image" class="form-control m-input m-input--square" id="image">
                                    <div class="form-control-feedback" id="imageMessage"></div>
                                </div>
                            @else
                                @if($status->image != null)
                                <div class="form-group m-form__group">
                                    <img src='{{asset("uploads/$status->image")}}' height="100" width="100">
                                </div>
                                @endif
                            @endif
                            
                        </div>
                        @if($status->price == null)
                            @if($requestOffer->reply_time >= date('Y-m-d'))
                                @if($requestOffer->status == 'pending')
                                    <div class="m-portlet__foot m-portlet__foot--fit" id="button">
                                        <div class="m-form__actions">
                                            <button type="submit" id="create" class="btn btn-success">{{trans('lang.send')}}</button>
                                            <button type="reset" style="display:none" id="reset"></button>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endif
                    </form>
                    <!--end::Form-->
                </div>
            </div>

@endsection

@section('js')
    <script src="{{asset('assets/js/lity.min.js')}}"></script>
    <script> 
        $(document).ready(function(){
            $('#modelForm').submit(function(e){
                e.preventDefault();
                url = $(this).attr('action');
                $.ajax({
                    type : 'POST',
                    url : url,
                    data : new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend : function(){
						$('#create').addClass('m-loader m-loader--right m-loader--light').attr('disabled','disabled');
						$('.form-control-feedback').text('');
						$(".form-group").removeClass("has-danger");
						$('#successMessage').hide();
                    }, 

                    success : function(response){
                        if(response == 'exists'){
                            $('#priceGroup').addClass('has-danger');
                            lang = '{{LaravelLocalization::getCurrentLocale()}}';
                            if(lang == 'ar'){
                                $('#priceMessage').text('تم ارسال السعر من قبل');
                            }else{
                                $('#priceMessage').text('you are already send price before');
                            }
                            $('#create').prop('disabled', false);
                            $('#create').removeClass('m-loader m-loader--right m-loader--light');
                            return;
                        }
                        $("html, body").animate({ scrollTop: 0 }, "slow");
						$('#successMessage').show();
						$('#button').hide();
						$('#create').prop('disabled', false);
						$('#create').removeClass('m-loader m-loader--right m-loader--light');
                        $('#reset').click();
                    },

                    error : function(response){
                        error = response.responseJSON.errors;
						if('price' in error){
							$('#priceGroup').addClass('has-danger');
							$('#priceMessage').text(error.price);
                        }


                        if('description' in error){
							$('#descriptionGroup').addClass('has-danger');
							$('#descriptionMessage').text(error.description);
                        }
                       

                       if('image' in error){
							$('#imageGroup').addClass('has-danger');
							$('#imageMessage').text(error.image);
                        }
                       
						$('#create').prop('disabled', false);
						$('#create').removeClass('m-loader m-loader--right m-loader--light');
                    }
                });
            })
        })   
        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
        };
        
    </script>
@endsection