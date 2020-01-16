@extends('layouts.master')

@section('title') {{trans('lang.update-category')}} @endsection

@section('content')
<!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title ">{{ trans('lang.update-category') }}</h3>
            </div>
        </div>
    </div>
    
            <div class="row">
                <div class="col-md-12">
                    <!--begin::Form-->
                    

                <form id="modelForm" class="m-form m-form--fit m-form--label-align-right m-form--state" method="POST" action="{{route('update-category', ['id' => $category->id])}}" enctype="multipart/form-data">
                    @method('PATCH') 
                    @csrf
                        <div class="m-portlet__body">
                                <div class="form-group m-form__group">
                                    <div style="display:none" class="alert alert-success" id="successMessage" role="alert"><strong>{{ trans('lang.wellDone') }}</strong>
                                        {{ trans('lang.successMessage') }}
                                    </div>
                                </div> 
                            <div class="form-group m-form__group" id="name_arGroup">
                                <label for="name_ar">{{ trans('lang.name_ar') }}</label>
                                <input type="text" value="{{$category->name_ar}}" name="name_ar" class="form-control m-input m-input--square" id="name_ar" placeholder="{{trans('lang.name_ar')}}">
                                <div class="form-control-feedback" id="name_arMessage"></div>
                            </div>

                            <div class="form-group m-form__group" id="name_enGroup">
                                <label for="name_en">{{ trans('lang.name_en') }}</label>
                                <input type="text" value="{{$category->name_en}}" name="name_en" class="form-control m-input m-input--square" id="name_en" placeholder="{{trans('lang.name_en')}}">
                                <div class="form-control-feedback" id="name_enMessage"></div>
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
                    },

                    error : function(response){
                        error = response.responseJSON.errors;
						if('name_ar' in error){
							$('#name_arGroup').addClass('has-danger');
							$('#name_arMessage').text(error.name_ar);
                        }
                        
                        if('name_en' in error){
							$('#name_enGroup').addClass('has-danger');
							$('#name_enMessage').text(error.name_en);
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