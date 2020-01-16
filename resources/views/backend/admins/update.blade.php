@extends('layouts.master')

@section('title') {{trans('lang.update-admin')}} @endsection

@section('content')
<!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title ">{{ trans('lang.update-admin') }}</h3>
            </div>
        </div>
    </div>
    
            <div class="row">
                <div class="col-md-12">
                    <!--begin::Form-->
                    

                <form id="modelForm" class="m-form m-form--fit m-form--label-align-right m-form--state" method="POST" action="{{route('update-admin', ['id' => $user->id])}}" enctype="multipart/form-data">
                    @method('PATCH') 
                    @csrf
                    <div class="m-portlet__body">
                            <div class="form-group m-form__group">
                                <div style="display:none" class="alert alert-success" id="successMessage" role="alert"><strong>{{ trans('lang.wellDone') }}</strong>
                                    {{ trans('lang.successMessage') }}
                                </div>
                            </div> 


                        <div class="form-group m-form__group" id="nameGroup">
                            <label for="name">{{ trans('lang.name') }}</label>
                            <input type="text" value="{{$user->name}}" name="name" class="form-control m-input m-input--square" id="name" placeholder="{{trans('lang.name')}}">
                            <div class="form-control-feedback" id="nameMessage"></div>
                        </div>


                        <div class="form-group m-form__group" id="emailGroup">
                            <label for="email">{{ trans('lang.email') }}</label>
                            <input type="text" value="{{$user->email}}" name="email" class="form-control m-input m-input--square" id="email" placeholder="{{trans('lang.email')}}">
                            <div class="form-control-feedback" id="emailMessage"></div>
                        </div>
                        

                        <div class="form-group m-form__group" id="passwordGroup">
                            <label for="password">{{ trans('lang.password') }}</label>
                            <input type="password" name="password" class="form-control m-input m-input--square" id="password" placeholder="{{trans('lang.password')}}">
                            <div class="form-control-feedback" id="passwordMessage"></div>
                        </div>

                        <div class="form-group m-form__group" id="permission_idGroup">
                                <label for="permission_id">{{ trans('lang.permissions') }}</label>
                                <select name="permission_id" class="form-control m-select2" style="width:100%" id="m_select2_5">
                                    <option></option>
                                    @foreach ($permissions as $permission)
                                        <option value="{{$permission->id}}" {{$permission->id == auth()->user()->permission_id ? 'selected' : ''}}>{{ucfirst($permission->name)}}</option>
                                    @endforeach
                                </select>
                                <div class="form-control-feedback" id="permission_idMessage"></div>
                            </div>

                        <div class="form-group m-form__group custom-file" id="imageGroup">
                            <label for="image">{{ trans('lang.image') }}</label>
                            <div class="custom-file">
                                <input type="file" accept="image/*" name="image" onchange="loadFile(event)" class="form-control custom-file-input" id="customFile" placeholder="{{trans('lang.image')}}">
                                <label class="custom-file-label" for="customFile">{{ trans('lang.chooseFile') }}</label>
                            </div>

                            <div class="form-control-feedback" id="imageMessage"></div>
                        </div>
                        <?php $image = $user->image == null ? 'default.png' : $user->image ?>
                        <div class="form-group m-form__group">
                            <label></label>
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
						if('name' in error){
							$('#nameGroup').addClass('has-danger');
							$('#nameMessage').text(error.name);
                        }
                        
                        if('image' in error){
							$('#imageGroup').addClass('has-danger');
							$('#imageMessage').text(error.image);
                        }
                        

                        if('permission_id' in error){
							$('#permission_idGroup').addClass('has-danger');
							$('#permission_idMessage').text(error.permission_id);
						}

                        if('password' in error){
							$('#passwordGroup').addClass('has-danger');
							$('#passwordMessage').text(error.password);
						}


                        if('email' in error){
							$('#emailGroup').addClass('has-danger');
							$('#emailMessage').text(error.email);
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