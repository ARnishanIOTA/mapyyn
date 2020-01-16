@extends('layouts.master')

@section('title') {{trans('lang.update-provider')}} @endsection

@section('content')
<!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title ">{{ trans('lang.update-provider') }}</h3>
            </div>
        </div>
    </div>
    <!-- END: Subheader -->
       <!--begin::Portlet-->
            <div class="row">
                <div class="col-md-12">
                    
                <form id="modelForm" class="m-form m-form--fit m-form--label-align-right m-form--state" method="POST" action="{{route('admin.update-provider', ['id' => $provider->id])}}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                        <div class="m-portlet__body">
                            <div class="form-group m-form__group">
                                <div style="display:none" class="alert alert-success" id="successMessage" role="alert"><strong>{{ trans('lang.wellDone') }}</strong>
                                    {{ trans('lang.successMessage') }}
                                </div>
                            </div> 
                            <div class="form-group m-form__group" id="first_nameGroup">
                                <label for="first_name">{{ trans('lang.first_name') }}</label>
                                <input type="text" name="first_name" value="{{$provider->first_name}}" class="form-control m-input m-input--square" id="first_name" placeholder="{{trans('lang.first_name')}}">
                                <div class="form-control-feedback" id="first_nameMessage"></div>
                            </div>

                            <div class="form-group m-form__group" id="last_nameGroup">
                                <label for="last_name">{{ trans('lang.last_name') }}</label>
                                <input type="text" name="last_name" value="{{$provider->last_name}}" class="form-control m-input m-input--square" id="last_name" placeholder="{{trans('lang.last_name')}}">
                                <div class="form-control-feedback" id="last_nameMessage"></div>
                            </div>


                            <div class="form-group m-form__group" id="emailGroup">
                                <label for="email">{{ trans('lang.email') }}</label>
                                <input type="text" name="email" value="{{$provider->email}}" class="form-control m-input m-input--square" id="email" placeholder="{{trans('lang.email')}}">
                                <div class="form-control-feedback" id="emailMessage"></div>
                            </div>


                            <div class="form-group m-form__group" id="passwordGroup">
                                <label for="password">{{ trans('lang.password') }}</label>
                                <input type="password" name="password" class="form-control m-input m-input--square" id="password" placeholder="{{trans('lang.password')}}">
                                <div class="form-control-feedback" id="passwordMessage"></div>
                            </div>

                            <div class="form-group m-form__group" id="addressGroup">
                                <label for="address">{{ trans('lang.address') }}</label>
                                <input type="text" name="address" value="{{$provider->address}}" class="form-control m-input m-input--square" id="address" placeholder="{{trans('lang.address')}}">
                                <div class="form-control-feedback" id="addressMessage"></div>
                            </div>

                            {{-- <div class="form-group m-form__group" id="countryGroup">
                                <label for="country">{{ trans('lang.country') }}</label>
                                <input type="text" name="country" class="form-control m-input m-input--square" id="country" placeholder="{{trans('lang.country')}}">
                                <div class="form-control-feedback" id="countryMessage"></div>
                            </div> --}}

                            <div class="form-group m-form__group" id="countryGroup">
                                <label for="country">{{ trans('lang.country') }}</label>
                                <select disabled class="form-control m-select2" style="width:100%" name="country" id="m_select2_1">
                                    <option></option>
                                    <option selected>{{$provider->country}}</option>
                                </select>
                                <div class="form-control-feedback" id="countryMessage"></div>
                            </div>


                            <div class="form-group m-form__group" id="phoneGroup">
                                    <label for="phone">{{ trans('lang.phone') }}</label>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input type="text" value="{{$provider->code}}" class="form-control" id="phone" disabled placeholder="{{trans('lang.code')}}">
                                            <input type="hidden" name="code" value="{{$provider->code}}" class="form-control" id="code" >
                                        </div>
    
                                        <div class="col-md-9">
                                            <input type="text" value="{{$provider->phone}}" name="phone" class="form-control m-input m-input--square" placeholder="{{trans('lang.phone')}}">
                                        </div>  
    
                                    </div>
                                    <div class="form-control-feedback" id="phoneMessage"></div>
                                </div>

                                <div class="form-group m-form__group" id="cityGroup">
                                    <label for="city">{{ trans('lang.city') }}</label>
                                    <input type="text" name="city" value="{{$provider->city}}" class="form-control m-input m-input--square" placeholder="{{trans('lang.city')}}">
                                    <div class="form-control-feedback" id="cityMessage"></div>
                                </div>


                            <div class="form-group m-form__group" id="categoriesGroup">
                                <label for="categories">{{ trans('lang.categories') }}</label>
                                <select class="form-control m-select2" name="categories[]" multiple style="width:100%" id="m_select2_3" >
                                    @foreach ($categories as $category)
                                        @if ($category == 1)
                                            <option value="1" selected>{{ trans('lang.entertainment') }}</option>
                                        @elseif($category == 2)
                                            <option value="2" selected>{{ trans('lang.educational') }}</option>
                                        @elseif($category == 3)   
                                            <option value="3" selected>{{ trans('lang.sport') }}</option>
                                        @else
                                            <option value="4" selected>{{ trans('lang.medical') }}</option>
                                        @endif
                                    @endforeach
                                    @if (!in_array(1, $categories))
                                        <option value="1">{{ trans('lang.entertainment') }}</option>
                                    @elseif(!in_array(2, $categories))
                                        <option value="2">{{ trans('lang.educational') }}</option>
                                    @elseif(!in_array(3, $categories))   
                                        <option value="3">{{ trans('lang.sport') }}</option>
                                    @else
                                        <option value="4">{{ trans('lang.medical') }}</option>
                                    @endif
                                </select>
                                <div class="form-control-feedback" id="categoriesMessage"></div>
                            </div>


                            <div class="form-group m-form__group" id="is_activeGroup">
                                <label for="is_active">{{ trans('lang.is_active') }}</label>
                                <select class="form-control m-input m-input--square" name="is_active">
                                    <option value="1" {{$provider->is_active == 1 ? 'selected' : ''}}>{{ trans('lang.active') }}</option>
                                    <option value="0" {{$provider->is_active == 0 ? 'selected' : ''}}>{{ trans('lang.nonaction') }}</option>
                                </select>
                                <div class="form-control-feedback" id="is_activeMessage"></div>
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
                                <?php $image = $provider->logo == null ? 'default.jpg' : $provider->logo; ?>
                                <img id="output" src='{{asset("uploads/$image")}}' width="100" height="100" alt="Preview Image">
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
<script src="{{asset('assets/js/select2.js')}}"></script>
    <script> 
        $(document).ready(function(){

            $('#m_select2_1').change(function(){
				id = $(this).val();
                code = $('option:selected', this).attr('code');
                $('#phone').val(code)
                $('#code').val(code)
				// $("#m_select2_5").find('option').remove();
                // $.ajax({
                //     type : 'GET',
                //     url : '{{url("/backend/get-cities")}}' + '/' + id,

                //     beforeSend:function(){
                //         $('#m_select2_2').fadeOut(1000);
                //     },
                //     success: function (response) {
                //         $('#cityGroup').fadeIn();
				// 		$('#m_select2_2').append($("<option></option>")); 
                //         $.each(response, function(key, value) {
				// 			$('#m_select2_2').append($("<option></option>").attr("value",value.name).text(value.name)); 
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


						if('is_active' in error){
							$('#is_activeGroup').addClass('has-danger');
							$('#is_activeMessage').text(error.is_active);
						}

                        if('logo' in error){
							$('#logoGroup').addClass('has-danger');
							$('#logoMessage').text(error.logo);
						}



						$('#update').prop('disabled', false);
						$('#update').removeClass('m-loader m-loader--right m-loader--light');
                    }
                });
            })
        })   
            var loadFile = function(event) {
                var output = document.getElementById('output');
                output.src = URL.updateObjectURL(event.target.files[0]);
            };
        
    </script>
@endsection