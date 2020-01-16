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
                    

                <form id="modelForm" class="m-form m-form--fit m-form--label-align-right m-form--state" method="POST" action="{{route('admin.payments.update', $payment->id)}}" enctype="multipart/form-data">
                    @method('PATCH') 
                    @csrf
                        <div class="m-portlet__body">
                                <div class="form-group m-form__group">
                                    <div style="display:none" class="alert alert-success" id="successMessage" role="alert"><strong>{{ trans('lang.wellDone') }}</strong>
                                        {{ trans('lang.successMessage') }}
                                    </div>
                                </div> 

                                <div class="form-group m-form__group" id="notesGroup">
                                        <label for="notes">{{ trans('lang.notes') }}</label>
                                        <textarea rows="10" name="notes" class="form-control m-input m-input--square" id="notes" placeholder="{{ trans('lang.notes') }}">{{$payment->notes}}</textarea>
                                        <div class="form-control-feedback" id="notesMessage"></div>
                                    </div>

                            <div class="form-group m-form__group" id="admin_statusGroup">
                                <label for="admin_status">{{ trans('lang.admin_status') }}</label>
                                <select name="admin_status" class="form-control m-input m-input--square" id="admin_status">
                                    <option value="hold" {{$payment->admin_status == 'hold' ? 'selected' : ''}}>{{ trans('lang.hold') }}</option>
                                    <option value="refund" {{$payment->admin_status == 'refund' ? 'selected' : ''}}>{{ trans('lang.refund') }}</option>
                                    <option value="billed" {{$payment->admin_status == 'billed' ? 'selected' : ''}}>{{ trans('lang.billed') }}</option>
                                </select>
                                <div class="form-control-feedback" id="admin_statusMessage"></div>
                            </div>
                        </div>
                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions">
                                <button type="submit" id="update" class="btn btn-success">{{trans('lang.update')}}</button>
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
                        window.location.reload(true)
                    },

                    error : function(response){
                        error = response.responseJSON.errors;
                        if('admin_status' in error){
							$('#admin_statusGroup').addClass('has-danger');
							$('#admin_statusMessage').text(error.admin_status);
                        }
                        
                        if('notes' in error){
							$('#notesGroup').addClass('has-danger');
							$('#notesMessage').text(error.notes);
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