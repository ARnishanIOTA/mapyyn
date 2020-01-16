@extends('layouts.master')

@section('title') {{trans('lang.create-admin-offer')}} @endsection

@section('content')
<!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title ">{{ trans('lang.create-admin-offer') }}</h3>
            </div>
        </div>
    </div>
   
            <div class="row">
                <div class="col-md-12">
                    <!--begin::Form-->
                    

                <form id="modelForm" class="m-form m-form--fit m-form--label-align-right m-form--state" method="POST" action="{{route('create-admin-offer')}}" enctype="multipart/form-data">
                       
                    @csrf
                        <div class="m-portlet__body">
                            <div class="form-group m-form__group">
                                <div style="display:none" class="alert alert-success" id="successMessage" role="alert"><strong>{{ trans('lang.wellDone') }}</strong>
                                    {{ trans('lang.successMessage') }}
                                </div>
                            </div> 
                            <div class="form-group m-form__group" id="descriptionGroup">
                                <label for="description">{{ trans('lang.description') }}</label>
                                <textarea name="description" class="form-control m-input m-input--square" id="description" placeholder="{{trans('lang.description')}}">{{old('description')}}</textarea>
                                <div class="form-control-feedback" id="descriptionMessage"></div>
                            </div>


                            <div class="form-group m-form__group" id="fromGroup">
                                <label for="from">{{ trans('lang.admin_offer_from') }}</label>
                                <input type="date" name="from" class="form-control m-input m-input--square" id="from" placeholder="{{trans('lang.admin_offer_from')}}">
                                <div class="form-control-feedback" id="fromMessage"></div>
                            </div>


                            <div class="form-group m-form__group" id="toGroup">
                                <label for="to">{{ trans('lang.admin_offer_to') }}</label>
                                <input type="date" name="to" class="form-control m-input m-input--square" id="to" placeholder="{{trans('lang.admin_offer_to')}}">
                                <div class="form-control-feedback" id="toMessage"></div>
                            </div>

                            <div class="form-group m-form__group custom-file" id="imageGroup">
                                <label for="image">{{ trans('lang.image') }}</label>
                                <div class="custom-file">
                                    <input type="file" accept="image/*" name="image" onchange="loadFile(event)" class="form-control custom-file-input" id="customFile" placeholder="{{trans('lang.image')}}">
                                    <label class="custom-file-label" for="customFile">{{ trans('lang.chooseFile') }}</label>
                                </div>
    
                                <div class="form-control-feedback" id="imageMessage"></div>
                            </div>
                            <div class="form-group m-form__group">
                                <label></label>
                               <img id="output" src='{{asset("uploads/default.jpg")}}' width="100" height="100" alt="Preview Image">
                            </div>
                            
                        </div>
                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions">
                                <button type="submit" id="create" class="btn btn-success">{{trans('lang.create')}}</button>
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

                    success : function(){
                        $("html, body").animate({ scrollTop: 0 }, "slow");
						$('#successMessage').show();
						$('#create').prop('disabled', false);
						$('#create').removeClass('m-loader m-loader--right m-loader--light');
                        $('#reset').click();
                    },

                    error : function(response){
                        error = response.responseJSON.errors;
						if('description' in error){
							$('#descriptionGroup').addClass('has-danger');
							$('#descriptionMessage').text(error.description);
                        }
                        
                        if('from' in error){
							$('#fromGroup').addClass('has-danger');
							$('#fromMessage').text(error.from);
						}

                        if('to' in error){
							$('#toGroup').addClass('has-danger');
							$('#toMessage').text(error.to);
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