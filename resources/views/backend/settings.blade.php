@extends('layouts.master')

@section('title') {{trans('lang.settings')}} @endsection

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
                            {{ trans('lang.site-info') }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!--begin::Form-->
                <form id="modelForm" class="m-form m-form--fit m-form--label-align-right m-form--state" method="POST" action="{{route('settings')}}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="m-portlet__body">
                            <div class="form-group m-form__group">
                                <div style="display:none" class="alert alert-success" id="successMessage" role="alert"><strong>{{ trans('lang.wellDone') }}</strong>
                                    {{ trans('lang.successMessage') }}
                                </div>
                            </div>

                            <div class="form-group m-form__group" id="fbGroup">
                                <label for="fb">{{ trans('lang.fb') }}</label>
                                <input type="text" value="{{$setting->fb}}" name="fb" class="form-control m-input m-input--square" id="fb" placeholder="{{trans('lang.fb')}}">
                                <div class="form-control-feedback" id="fbMessage"></div>
                            </div>


                            <div class="form-group m-form__group" id="twGroup">
                                <label for="tw">{{ trans('lang.tw') }}</label>
                                <input type="text" value="{{$setting->tw}}" name="tw" class="form-control m-input m-input--square" id="tw" placeholder="{{trans('lang.tw')}}">
                                <div class="form-control-feedback" id="twMessage"></div>
                            </div>


                            <div class="form-group m-form__group" id="instagramGroup">
                                <label for="instagram">{{ trans('lang.instagram') }}</label>
                                <input type="text" value="{{$setting->instagram}}" name="instagram" class="form-control m-input m-input--square" id="instagram" placeholder="{{trans('lang.instagram')}}">
                                <div class="form-control-feedback" id="instagramMessage"></div>
                            </div>


                            <div class="form-group m-form__group" id="whatsappGroup">
                                <label for="whatsapp">{{ trans('lang.whatsapp') }}</label>
                                <input type="text" value="{{$setting->whatsapp}}" name="whatsapp" class="form-control m-input m-input--square" id="whatsapp" placeholder="{{trans('lang.whatsapp')}}">
                                <div class="form-control-feedback" id="whatsappMessage"></div>
                            </div>


                            <div class="form-group m-form__group" id="emailGroup">
                                <label for="email">{{ trans('lang.email') }}</label>
                                <input type="email" value="{{$setting->email}}" name="email" class="form-control m-input m-input--square" id="email" placeholder="{{trans('lang.email')}}">
                                <div class="form-control-feedback" id="emailMessage"></div>
                            </div>


                            <div class="form-group m-form__group" id="phoneGroup">
                                <label for="phone">{{ trans('lang.phone') }}</label>
                                <input type="text" value="{{$setting->phone}}" name="phone" class="form-control m-input m-input--square" id="phone" placeholder="{{trans('lang.phone')}}">
                                <div class="form-control-feedback" id="phoneMessage"></div>
                            </div>

                            <div class="form-group m-form__group custom-file" id="logoGroup">
                                <label for="logo">{{ trans('lang.logo') }}</label>
                                <div class="custom-file">
                                    <input type="file" accept="image/*" name="logo" onchange="loadFile(event)" class="form-control custom-file-input" id="customFile" placeholder="{{trans('lang.logo')}}">
                                    <label class="custom-file-label" for="customFile">{{ trans('lang.chooseFile') }}</label>
                                </div>

                                <div class="form-control-feedback" id="logoMessage"></div>
                            </div>

                            <div class="form-group m-form__group">
                                <label></label>
                                <?php 
                                    if($setting->logo == null){
                                        $image = 'default.jpg';
                                    }else{
                                        $image = $setting->logo;
                                    }
                                ?>
                                <img id="output" src='{{asset("uploads/$image")}}' width="100" height="100" alt="Preview Image">
                            </div>

                            <div class="form-group m-form__group" id="about_arGroup">
                                <label for="about_ar">{{ trans('lang.about_ar') }}</label>
                                <textarea rows="10" name="about_ar" class="form-control m-input m-input--square" id="about_ar" placeholder="{{trans('lang.about_ar')}}">{{$setting->about_ar}}</textarea>
                                <div class="form-control-feedback" id="about_arMessage"></div>
                            </div>


                            <div class="form-group m-form__group" id="about_enGroup">
                                    <label for="about_en">{{ trans('lang.about_en') }}</label>
                                    <textarea rows="10" name="about_en" class="form-control m-input m-input--square" id="about_en" placeholder="{{trans('lang.about_en')}}">{{$setting->about_en}}</textarea>
                                    <div class="form-control-feedback" id="about_enMessage"></div>
                                </div>

                            <div class="form-group m-form__group" id="terms_arGroup">
                                <label for="terms_ar">{{ trans('lang.terms_ar') }}</label>
                                <textarea rows="10" name="terms_ar" class="form-control m-input m-input--square" id="terms_ar" placeholder="{{trans('lang.terms_ar')}}">{{$setting->terms_ar}}</textarea>
                                <div class="form-control-feedback" id="terms_arMessage"></div>
                            </div>


                            <div class="form-group m-form__group" id="terms_enGroup">
                                    <label for="terms_en">{{ trans('lang.terms_en') }}</label>
                                    <textarea rows="10" name="terms_en" class="form-control m-input m-input--square" id="terms_en" placeholder="{{trans('lang.terms_en')}}">{{$setting->terms_en}}</textarea>
                                    <div class="form-control-feedback" id="terms_enMessage"></div>
                                </div>
                            
                            
                        </div>
                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions">
                            <button type="submit" id="update" class="btn btn-success">{{trans('lang.update')}}</button><button type="reset" style="display:none" id="reset"></button>
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
        </div>
    </div>
@endsection

@section('js')
	<!--begin::Page Resources -->
    <script> 
        $(document).ready(function(){
            $('#modelForm').submit(function(e){
                e.preventDefault();
                url = $(this).attr('action');
                data = $(this).serialize();
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
						if('fb' in error){
							$('#fbGroup').addClass('has-danger');
							$('#fbMessage').text(error.fb);
						}

                        if('tw' in error){
							$('#twGroup').addClass('has-danger');
							$('#twMessage').text(error.tw);
						}

                        if('instagram' in error){
							$('#instagramGroup').addClass('has-danger');
							$('#instagramMessage').text(error.instagram);
						}

                        if('whatsapp' in error){
							$('#whatsappGroup').addClass('has-danger');
							$('#whatsappMessage').text(error.whatsapp);
						}

						if('email' in error){
							$('#emailGroup').addClass('has-danger');
							$('#emailMessage').text(error.email);
						}

						if('phone' in error){
							$('#phoneGroup').addClass('has-danger');
							$('#phoneMessage').text(error.phone);
						}

                        if('logo' in error){
							$('#logoGroup').addClass('has-danger');
							$('#logoMessage').text(error.logo);
						}

                        if('about_ar' in error){
							$('#about_arGroup').addClass('has-danger');
							$('#about_arMessage').text(error.about_ar);
						}

                        if('about_en' in error){
							$('#about_enGroup').addClass('has-danger');
							$('#about_enMessage').text(error.about_en);
						}

                        if('terms_ar' in error){
							$('#terms_arGroup').addClass('has-danger');
							$('#terms_arMessage').text(error.terms_ar);
						}

                        if('terms_en' in error){
							$('#terms_enGroup').addClass('has-danger');
							$('#terms_enMessage').text(error.terms_en);
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