@extends('layouts.master')

@section('title') {{trans('lang.create-notification')}} @endsection

@section('content')
<!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                       
                <h3 class="m-subheader__title ">{{ trans('lang.create-notification') }}</h3>
            </div>
        </div>
    </div>
   
            <div class="row">
                <div class="col-md-12">
                    <!--begin::Form-->

                <form id="modelForm" class="m-form m-form--fit m-form--label-align-right m-form--state" method="POST" action="{{route('send-notifications-create')}}" enctype="multipart/form-data">
                    @csrf
                        <div class="m-portlet__body">
                                <div class="form-group m-form__group">
                                    <div style="display:none" class="alert alert-success" id="successMessage" role="alert"><strong>{{ trans('lang.wellDone') }}</strong>
                                        {{ trans('lang.successMessage') }}
                                    </div>
                                </div> 
                            <div class="form-group m-form__group" id="titleGroup">
                                <label for="title">{{ trans('lang.title') }}</label>
                                <input type="text" name="title" class="form-control m-input m-input--square" id="title" placeholder="{{trans('lang.title')}}">
                                <div class="form-control-feedback" id="titleMessage"></div>
                            </div>

                            <div class="form-group m-form__group" id="messageGroup">
                                <label for="message">{{ trans('lang.message') }}</label>
                                <textarea name="message" class="form-control m-input m-input--square" id="message" placeholder="{{trans('lang.message')}}"></textarea>
                                <div class="form-control-feedback" id="messageMessage"></div>
                            </div>



                            <div class="form-group m-form__group" id="user_typeGroup">
                                <label for="user_type">{{ trans('lang.user_type') }}</label>
                                <select class="form-control m-input m-input--square" name="user_type">
                                    <option value="">{{ trans('lang.select') }}</option>
                                    <option value="1">{{ trans('lang.providers') }}</option>
                                    <option value="0">{{ trans('lang.clients') }}</option>
                                </select>
                                <div class="form-control-feedback" id="user_typeMessage"></div>
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
                data = $(this).serialize();
                $.ajax({
                    type : 'POST',
                    url : url,
                    data : data,
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
                        $('#reset').click();
                    },

                    error : function(response){
                        error = response.responseJSON.errors;
						
                        if('title' in error){
							$('#titleGroup').addClass('has-danger');
							$('#titleMessage').text(error.title);
                        }
                        
                       

                        if('message' in error){
							$('#messageGroup').addClass('has-danger');
							$('#messageMessage').text(error.message);
						}

                        
                        if('user_type' in error){
							$('#user_typeGroup').addClass('has-danger');
							$('#user_typeMessage').text(error.user_type);
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