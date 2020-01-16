@extends('layouts.master')

@section('title') {{trans('lang.reply')}} @endsection

@section('content')
<!-- BEGIN: Subheader -->
    <!-- END: Subheader -->
    <div class="m-content">
       <!--begin::Portlet-->
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon m--hide">
                            <i class="la la-gear"></i>
                        </span>
                        <h3 class="m-portlet__head-text">
                            {{ trans('lang.reply') }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!--begin::Form-->
                    

                <form id="modelForm" class="m-form m-form--fit m-form--label-align-right m-form--state" method="POST" action="{{route('reply-contact', ['id' => $contact->id])}}">
                    @csrf
                        <div class="m-portlet__body">
                                <div class="form-group m-form__group">
                                    <div style="display:none" class="alert alert-success" id="successMessage" role="alert"><strong>{{ trans('lang.wellDone') }}</strong>
                                        {{ trans('lang.successMessage') }}
                                    </div>
                                </div> 

                            <div class="form-group m-form__group">
                                <label>{{ trans('lang.client-message') }}</label>
                                <textarea rows="10" disabled class="form-control m-input m-input--square" >{{$contact->message}}</textarea>
                            </div>

                            <div class="form-group m-form__group" id="subjectGroup">
                                <label for="subject">{{ trans('lang.subject') }}</label>
                                <input type="text" name="subject" class="form-control m-input m-input--square" id="subject" placeholder="{{trans('lang.subject')}}">
                                <div class="form-control-feedback" id="subjectMessage"></div>
                            </div>


                            <div class="form-group m-form__group" id="messageGroup">
                                <label for="message">{{ trans('lang.message') }}</label>
                                <textarea rows="10" name="message" class="form-control m-input m-input--square" id="message" placeholder="{{trans('lang.message')}}"></textarea>
                                <div class="form-control-feedback" id="messageMessage"></div>
                            </div>
                        </div>
                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions">
                                <button type="submit" id="update" class="btn btn-success">{{trans('lang.send')}}</button>
                                <button type="reset" style="display:none" id="reset"></button>
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
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
						$('#update').addClass('m-loader m-loader--right m-loader--light').attr('disabled','disabled');
						$('.form-control-feedback').text('');
						$(".form-group").removeClass("has-danger");
						$('#successMessage').hide();
                    }, 

                    success : function(){
                        $("html, body").animate({ scrollTop: 0 }, "slow");
						$('#successMessage').show();
						$('#update').prop('disabled', false);
                        $('#update').removeClass('m-loader m-loader--right m-loader--light');
                        $('#reset').click();
                    },

                    error : function(response){
                        error = response.responseJSON.errors;
						if('subject' in error){
							$('#subjectGroup').addClass('has-danger');
							$('#subjectMessage').text(error.subject);
                        }
                        
                        if('message' in error){
							$('#messageGroup').addClass('has-danger');
							$('#messageMessage').text(error.message);
						}

						$('#update').prop('disabled', false);
						$('#update').removeClass('m-loader m-loader--right m-loader--light');
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