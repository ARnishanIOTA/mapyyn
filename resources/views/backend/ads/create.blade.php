@extends('layouts.master')

@section('title') {{trans('lang.create-ads')}} @endsection

@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/lity.min.css')}}">
@endsection
@section('content')

@if(LaravelLocalization::getCurrentLocale() == 'ar')
        <?php 
            $image = 'image_ar'; 
        ?>
        @else
        <?php 
            $image = 'image_en'; 
        ?>
    @endif

<!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title ">{{ trans('lang.create-ads') }}</h3>
            </div>
        </div>
    </div>
    
            <div class="row">
                <div class="col-md-12">
                    <!--begin::Form-->
                    

                <form id="modelForm" class="m-form m-form--fit m-form--label-align-right m-form--state" method="POST" action="{{route('create-ads')}}" enctype="multipart/form-data">
                    @csrf
                        <div class="m-portlet__body">

                                <div class="form-group m-form__group">
                                        <div class="alert alert-warning" role="alert"></strong>
                                            {{ trans('lang.dimentions_ads') }}
                                        </div>
                                    </div>

                                <div class="form-group m-form__group">
                                    <div style="display:none" class="alert alert-success" id="successMessage" role="alert"><strong>{{ trans('lang.wellDone') }}</strong>
                                        {{ trans('lang.successMessage') }}
                                    </div>
                                </div> 

                            <div class="form-group m-form__group" id="start_atGroup">
                                <label for="start_at">{{ trans('lang.start_at') }}</label>
                                <input type="date" name="start_at" class="form-control m-input m-input--square" id="start_at" placeholder="{{trans('lang.start_at')}}">
                                <div class="form-control-feedback" id="start_atMessage"></div>
                            </div>


                            <div class="form-group m-form__group" id="end_atGroup">
                                <label for="end_at">{{ trans('lang.end_at') }}</label>
                                <input type="date" name="end_at" class="form-control m-input m-input--square" id="end_at" placeholder="{{trans('lang.end_at')}}">
                                <div class="form-control-feedback" id="end_atMessage"></div>
                            </div>

                            <div class="form-group m-form__group custom-file" id="image_arGroup">
                                <label for="image">{{ trans('lang.image_ar') }}</label>
                                <div class="custom-file">
                                    <input type="file" accept="image/*" name="image_ar" onchange="loadFile(event)" class="form-control custom-file-input" id="customFile" placeholder="{{trans('lang.image_ar')}}">
                                    <label class="custom-file-label" for="customFile">{{ trans('lang.chooseFile') }}</label>
                                </div>
    
                                <div class="form-control-feedback" id="image_arMessage"></div>
                            </div>
                            <div class="form-group m-form__group">
                                <label></label>
                               <img id="output" src='{{asset("uploads/default.jpg")}}' width="100" height="100" alt="Preview Image">
                            </div>

                            <div class="form-group m-form__group custom-file" id="image_enGroup">
                                <label for="image_en">{{ trans('lang.image_en') }}</label>
                                <div class="custom-file">
                                    <input type="file" accept="image/*" name="image_en" onchange="loadFile2(event)" class="form-control custom-file-input" id="customFile" placeholder="{{trans('lang.image_en')}}">
                                    <label class="custom-file-label" for="customFile">{{ trans('lang.chooseFile') }}</label>
                                </div>
    
                                <div class="form-control-feedback" id="image_enMessage"></div>
                            </div>
                            <div class="form-group m-form__group">
                                <label></label>
                                <img id="output2" src='{{asset("uploads/default.jpg")}}' width="100" height="100" alt="Preview Image">
                            </div>
                        </div>
                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions">
                                <button type="submit" id="create" class="btn btn-success">{{trans('lang.create')}}</button>
                            </div>
                        </div>
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

                    success : function(){
                        $("html, body").animate({ scrollTop: 0 }, "slow");
						$('#successMessage').show();
						$('#create').prop('disabled', false);
						$('#create').removeClass('m-loader m-loader--right m-loader--light');
                    },

                    error : function(response){
                        error = response.responseJSON.errors;
                        if('start_at' in error){
							$('#start_atGroup').addClass('has-danger');
							$('#start_atMessage').text(error.start_at);
						}

                        if('end_at' in error){
							$('#end_atGroup').addClass('has-danger');
							$('#end_atMessage').text(error.end_at);
						}

                        if('image_ar' in error){
							$('#image_arGroup').addClass('has-danger');
							$('#image_arMessage').text(error.image_ar);
						}

                        if('image_en' in error){
							$('#image_enGroup').addClass('has-danger');
							$('#image_enMessage').text(error.image_en);
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

            var loadFile2 = function(event) {
                var output = document.getElementById('output2');
                output.src = URL.createObjectURL(event.target.files[0]);
            };
        
    </script>
@endsection