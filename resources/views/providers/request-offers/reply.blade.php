@extends('layouts.master')

@section('title') {{trans('lang.reply_request')}} @endsection

@section('content')
<!-- BEGIN: Subheader -->
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
                                <div class="form-group m-form__group">
                                    <div style="display:none" class="alert alert-success" id="successMessage" role="alert"><strong>{{ trans('lang.wellDone') }}</strong>
                                        {{ trans('lang.successMessage') }}
                                    </div>
                                </div> 
                            <div class="form-group m-form__group" id="priceGroup">
                                <label for="price">{{ trans('lang.price') }}</label>
                                <input type="text" name="price" class="form-control m-input m-input--square" id="price" placeholder="{{trans('lang.price')}}">
                                <div class="form-control-feedback" id="priceMessage"></div>
                            </div>

                            <div class="form-group m-form__group" id="descriptionGroup">
                                <label for="description">{{ trans('lang.description') }}</label>
                                <textarea type="text" name="description" class="form-control m-input m-input--square" id="description" placeholder="{{trans('lang.description')}}"></textarea>
                                <div class="form-control-feedback" id="descriptionMessage"></div>
                            </div>


                            <div class="form-group m-form__group" id="imageGroup">
                                <label for="image">{{ trans('lang.image') }}</label>
                                <input type="file" accept="image/*" name="image" class="form-control m-input m-input--square" id="image">
                                <div class="form-control-feedback" id="imageMessage"></div>
                            </div>

                            
                        </div>
                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions">
                                <button type="submit" id="create" class="btn btn-success">{{trans('lang.send')}}</button>
                                <button type="reset" style="display:none" id="reset"></button>
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
            </div>
@endsection

@section('js')
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