@extends('layouts.master')

@section('title') {{trans('lang.create-faq')}} @endsection

@section('content')
<!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title ">{{ trans('lang.create-faq') }}</h3>
            </div>
        </div>
    </div>
            <div class="row">
                <div class="col-md-12">
                    <!--begin::Form-->
                <form id="modelForm" class="m-form m-form--fit m-form--label-align-right m-form--state" method="POST" action="{{route('create-faq')}}" enctype="multipart/form-data">
                       
                    @csrf
                        <div class="m-portlet__body">
                                <div class="form-group m-form__group">
                                    <div style="display:none" class="alert alert-success" id="successMessage" role="alert"><strong>{{ trans('lang.wellDone') }}</strong>
                                        {{ trans('lang.successMessage') }}
                                    </div>
                                </div> 
                                <div class="form-group m-form__group" id="title_arGroup">
                                    <label for="title_ar">{{ trans('lang.title_ar') }}</label>
                                    <input type="text" name="title_ar" class="form-control m-input m-input--square" id="title_ar" placeholder="{{trans('lang.title_ar')}}">
                                    <div class="form-control-feedback" id="title_arMessage"></div>
                                </div>
    
    
                                <div class="form-group m-form__group" id="title_enGroup">
                                    <label for="title_en">{{ trans('lang.title_en') }}</label>
                                    <input type="text" name="title_en" class="form-control m-input m-input--square" id="title_en" placeholder="{{trans('lang.title_en')}}">
                                    <div class="form-control-feedback" id="title_enMessage"></div>
                                </div>
    
                                <div class="form-group m-form__group" id="description_arGroup">
                                    <label for="description_ar">{{ trans('lang.description_ar') }}</label>
                                    
                                    <textarea rows="10"  name="description_ar" class="form-control m-input m-input--square" id="description_ar" placeholder="{{trans('lang.description_ar')}}"></textarea>
                                    <div class="form-control-feedback" id="description_arMessage"></div>
                                </div>
    
                                <div class="form-group m-form__group" id="description_enGroup">
                                    <label for="description_en">{{ trans('lang.description_en') }}</label>
                                    
                                    <textarea rows="10"  name="description_en" class="form-control m-input m-input--square" id="description_en" placeholder="{{trans('lang.description_en')}}"></textarea>
                                    <div class="form-control-feedback" id="description_enMessage"></div>
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
						if('title_ar' in error){
							$('#title_arGroup').addClass('has-danger');
							$('#title_arMessage').text(error.title_ar);
                        }

                        if('title_en' in error){
							$('#title_enGroup').addClass('has-danger');
							$('#title_enMessage').text(error.title_en);
                        }
                        
                        if('description_ar' in error){
							$('#description_arGroup').addClass('has-danger');
							$('#description_arMessage').text(error.description_ar);
						}

                        if('description_en' in error){
							$('#description_enGroup').addClass('has-danger');
							$('#description_enMessage').text(error.description_en);
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