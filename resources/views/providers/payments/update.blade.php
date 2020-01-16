@extends('layouts.master')

@section('title') {{trans('lang.update-payment')}} @endsection

@section('content')
<!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title ">{{ trans('lang.update-payment') }}</h3>
            </div>
        </div>
    </div>
    
            <div class="row">
                <div class="col-md-12">
                    <!--begin::Form-->
                    

                <form id="modelForm" class="m-form m-form--fit m-form--label-align-right m-form--state" method="POST" action="{{route('update-payment', ['id' => $payment->id])}}" enctype="multipart/form-data">
                    @method('PATCH') 
                    @csrf
                        <div class="m-portlet__body">
                                <div class="form-group m-form__group">
                                    <div style="display:none" class="alert alert-success" id="successMessage" role="alert"><strong>{{ trans('lang.wellDone') }}</strong>
                                        {{ trans('lang.successMessage') }}
                                    </div>
                                </div> 

                            <div class="form-group m-form__group" id="filesGroup">
                                <label for="files">{{ trans('lang.files') }}</label>
                                <input name="files[]" multiple type="file" class="form-control m-input m-input--square" id="files">
                                <div class="form-control-feedback" id="filesMessage"></div>
                            </div>

                            <div class="form-group m-form__group" id="statusGroup">
                                <label for="status">{{ trans('lang.status') }}</label>
                                <select name="status" class="form-control m-input m-input--square" id="status">
                                    <option value="processing" {{$payment->status == 'processing' ? 'selected' : ''}}>{{ trans('lang.processing') }}</option>
                                    <option value="paid" {{$payment->status == 'paid' ? 'selected' : ''}}>{{ trans('lang.paid') }}</option>
                                </select>
                                <div class="form-control-feedback" id="statusMessage"></div>
                            </div>
                        </div>
                        @if($payment->status != 'paid')
                            <div class="m-portlet__foot m-portlet__foot--fit">
                                <div class="m-form__actions">
                                    <button type="submit" id="update" class="btn btn-success">{{trans('lang.update')}}</button>
                                </div>
                            </div>
                        @endif
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
                        window.location.replace("{{url('/providers/payments')}}");

                    },

                    error : function(response){
                        error = response.responseJSON.errors;
                        if('status' in error){
							$('#statusGroup').addClass('has-danger');
							$('#statusMessage').text(error.status);
                        }
                        
                        if('files' in error){
							$('#filesGroup').addClass('has-danger');
							$('#filesMessage').text(error.files);
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