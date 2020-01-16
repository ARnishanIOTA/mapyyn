@extends('layouts.master')

@section('title') {{trans('lang.update-provider')}} @endsection

@section('content')
<!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title ">{{ trans('lang.update-ptofile') }}</h3>
            </div>
        </div>
    </div>
    <!-- END: Subheader -->
       <!--begin::Portlet-->
            <div class="row">
                <div class="col-md-12">
                    <!--begin::Form-->
                    

                <form id="modelForm" class="m-form m-form--fit m-form--label-align-right m-form--state" method="POST" action="{{route('update-provider')}}" enctype="multipart/form-data">
                       
                    @csrf
                    @method('PATCH')
                        <div class="m-portlet__body">
                                <div class="form-group m-form__group">
                                    <div class="alert alert-info" role="alert"><strong>{{ trans('lang.successMessageProvider') }}</strong>
                                    </div>
                                </div>
                                
                                <div class="form-group m-form__group">
                                    <div style="display:none" class="alert alert-success" id="successMessage" role="alert"><strong>{{ trans('lang.wellDone') }}</strong>
                                        {{ trans('lang.successMessage') }}
                                    </div>
                                </div> 

                            <div class="form-group m-form__group" id="first_nameGroup">
                                <label for="first_name">{{ trans('lang.first_name') }}</label>
                                <input type="text" value="{{auth('providers')->user()->first_name}}" name="first_name" class="form-control m-input m-input--square" id="first_name" placeholder="{{trans('lang.first_name')}}">
                                <div class="form-control-feedback" id="first_nameMessage"></div>
                            </div>

                            <div class="form-group m-form__group" id="last_nameGroup">
                                <label for="last_name">{{ trans('lang.last_name') }}</label>
                                <input type="text" value="{{auth('providers')->user()->last_name}}" name="last_name" class="form-control m-input m-input--square" id="last_name" placeholder="{{trans('lang.last_name')}}">
                                <div class="form-control-feedback" id="last_nameMessage"></div>
                            </div>



                            <div class="form-group m-form__group" id="emailGroup">
                                <label for="email">{{ trans('lang.email') }}</label>
                                <input type="text" value="{{auth('providers')->user()->email}}" name="email" class="form-control m-input m-input--square" id="email" placeholder="{{trans('lang.email')}}">
                                <div class="form-control-feedback" id="emailMessage"></div>
                            </div>


                            <div class="form-group m-form__group" id="passwordGroup">
                                <label for="password">{{ trans('lang.password') }}</label>
                                <input type="password" name="password" class="form-control m-input m-input--square" id="password" placeholder="{{trans('lang.password')}}">
                                <div class="form-control-feedback" id="passwordMessage"></div>
                            </div>

                            <div class="form-group m-form__group" id="addressGroup">
                                <label for="address">{{ trans('lang.address') }}</label>
                                <input type="text" value="{{auth('providers')->user()->address}}" name="address" class="form-control m-input m-input--square" id="address" placeholder="{{trans('lang.address')}}">
                                <div class="form-control-feedback" id="addressMessage"></div>
                            </div>

                            <div class="form-group m-form__group" id="countryGroup">
                                <label for="country">{{ trans('lang.country') }}</label>
                                <select disabled class="form-control m-select2" style="width:100%" name="country" id="country">
                                    <option selected>{{auth('providers')->user()->country}}</option>
                                </select>
                                <div class="form-control-feedback" id="countryMessage"></div>
                            </div>

                            <div class="form-group m-form__group" id="phoneGroup">
                                <label for="phone">{{ trans('lang.phone') }}</label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" value="{{auth('providers')->user()->code}}" class="form-control" id="phone" disabled placeholder="{{trans('lang.code')}}">
                                        <input type="hidden" name="code" value="{{auth('providers')->user()->code}}" id="code" >
                                    </div>

                                    <div class="col-md-9">
                                        <input type="text" value="{{auth('providers')->user()->phone}}" name="phone" class="form-control m-input m-input--square" placeholder="{{trans('lang.phone')}}">
                                    </div>  

                                </div>
                                <div class="form-control-feedback" id="phoneMessage"></div>
                            </div>


                            <div class="form-group m-form__group" id="cityGroup">
                                <label for="city">{{ trans('lang.city') }}</label>
                                <input type="text" value="{{auth('providers')->user()->city}}" name="city" class="form-control m-input m-input--square" placeholder="{{trans('lang.city')}}">
                                <div class="form-control-feedback" id="cityMessage"></div>
                            </div>
                            
                            

                            <div class="form-group m-form__group" id="categoriesGroup">
                                <label for="categories">{{ trans('lang.categories') }}</label>
                                @foreach (auth('providers')->user()->categories as $category)
                                    <?php $cat[] = $category->category_id; ?>
                                @endforeach
                                <select class="form-control m-select2" name="categories[]" multiple style="width:100%" id="m_select2_3" >
                                    <option value="1" {{in_array( 1, $cat) ? 'selected' : ''}}>{{trans('lang.entertainment')}}</option>
                                    <option value="2" {{in_array( 2, $cat) ? 'selected' : ''}}>{{trans('lang.educational')}}</option>
                                    <option value="3" {{in_array( 3, $cat) ? 'selected' : ''}}>{{trans('lang.sport')}}</option>
                                    <option value="4" {{in_array( 4, $cat) ? 'selected' : ''}}>{{trans('lang.medical')}}</option>
                                </select>
                                <div class="form-control-feedback" id="categoriesMessage"></div>
                            </div>


                           
                            
                            <div class="form-group m-form__group custom-file" id="logoGroup">
                                <label for="logo">{{ trans('lang.logo') }}</label>
                                <div class="custom-file">
                                    <input type="file" accept="image/*" name="logo" onchange="loadFile(event)" class="form-control custom-file-input" id="customFile" placeholder="{{trans('lang.logo')}}">
                                    <label class="custom-file-label" for="customFile">{{ trans('lang.chooseFile') }}</label>
                                </div>

                                <div class="form-control-feedback" id="logoMessage"></div>
                            </div>
                            <?php $image = auth('providers')->user()->logo != null ? auth('providers')->user()->logo : 'default.png' ?>
                            <div class="form-group m-form__group">
                                <label></label>
                                <img id="output" src='{{asset("uploads/$image")}}' width="100" height="100" alt="Preview Image">
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
<script src="{{asset('assets/js/select2.js')}}"></script>
    <script> 
        $(document).ready(function(){

            if($('#country').val() !=  null){
                id = $(this).val();
                code = $('option:selected', this).attr('code');
                $('#phone').val(code)
                $('#code').val(code)
				// $("#m_select2_2").find('option').remove();
                // $.ajax({
                //     type : 'GET',
                //     url : '{{url("/providers/get-cities")}}' + '/' + id,

                //     beforeSend:function(){
                //         $('#m_select2_2').fadeOut(1000);
                //     },
                //     success: function (response) {
                //         $('#cityGroup').fadeIn();
                //         old = '{{auth('providers')->user()->city}}'
                //         $.each(response, function(key, value) {
				// 			$('#m_select2_2').append($("<option "+ ((old == value.name) ? 'selected' : '') +"></option>").attr("value",value.id).text(value.name)); 
                //         });
                //     }, 
                // });
            }
            $('#country').change(function(){
                id = $(this).val();
                code = $('option:selected', this).attr('code');
                $('#phone').val(code)
                $('#code').val(code)
				// $("#m_select2_2").find('option').remove();
                // $.ajax({
                //     type : 'GET',
                //     url : '{{url("/providers/get-cities")}}' + '/' + id,

                //     beforeSend:function(){
                //         $('#m_select2_2').fadeOut(1000);
                //     },
                //     success: function (response) {
                //         $('#cityGroup').fadeIn();
                //         old = '{{auth('providers')->user()->city}}'
                //         $.each(response, function(key, value) {
				// 			$('#m_select2_2').append($("<option></option>").attr("value",value.id).text(value.name)); 
                //         });
                //     }, 
                // });
			})

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
                        setTimeout(function(){
                            location.reload();
                        }, 2000)
                    },

                    error : function(response){
                        if(response.responseJSON.password != null){
                            $('#passwordGroup').addClass('has-danger');
							$('#passwordMessage').text(response.responseJSON.password);
                        }else{
                            error = response.responseJSON.errors;
                            if('first_name' in error){
                                $('#first_nameGroup').addClass('has-danger');
                                $('#first_nameMessage').text(error.first_name);
                            }

                            if('last_name' in error){
                                $('#last_nameGroup').addClass('has-danger');
                                $('#last_nameMessage').text(error.last_name);
                            }
                            
                            if('email' in error){
                                $('#emailGroup').addClass('has-danger');
                                $('#emailMessage').text(error.email);
                            }

                            if('phone' in error){
                                $('#phoneGroup').addClass('has-danger');
                                $('#phoneMessage').text(error.phone);
                            }

                            if('country' in error){
                                $('#countryGroup').addClass('has-danger');
                                $('#countryMessage').text(error.country);
                            }

                            if('city' in error){
                                $('#cityGroup').addClass('has-danger');
                                $('#cityMessage').text(error.city);
                            }

                            if('categories' in error){
                                $('#categoriesGroup').addClass('has-danger');
                                $('#categoriesMessage').text(error.categories);
                            }


                            if('address' in error){
                                $('#addressGroup').addClass('has-danger');
                                $('#addressMessage').text(error.address);
                            }

                            if('password' in error){
                                $('#passwordGroup').addClass('has-danger');
                                $('#passwordMessage').text(error.password);
                            }

                            if('logo' in error){
                                $('#logoGroup').addClass('has-danger');
                                $('#logoMessage').text(error.logo);
                            }
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