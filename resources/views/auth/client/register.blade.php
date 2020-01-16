@extends('layouts.front')

@section('title') {{ trans('lang.login') }} @endsection

@section('content')
@if(LaravelLocalization::getCurrentLocale() == 'ar')
    <?php $name = 'name_ar' ?>
@else
    <?php $name = 'name_en' ?>
@endif
<div class="login my-5 ">
    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="data-login border p-4 bg-white">
                    <h5 class="mb-5 ">{{trans('lang.register')}}<a href="{{url('/login')}}" class="float-right h-6 d-block">{{ trans('lang.login') }}</a>   </h5>
                    <form id="forms" method="POST" action="{{route('client-register')}}">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </div>
                        @endif
                        
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{session('success')}}
                            </div>
                        @endif
                        @csrf

                        <div class="form-group mb-4">
                            <select name="country" class="form-control p-3" id="country">
                                <option selected value="" disabled>{{ trans('lang.country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{$country->$name}}" {{old('country') == $country->$name ? 'selected' : ''}} code='{{$country->phonecode}}'>{{$country->$name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                    <input type="text" id="phone" value="{{old('code')}}" class="form-control" disabled placeholder="{{trans('lang.code')}}">
                                    <input type="hidden" value="{{old('code')}}" name="code" id="code" class="form-control">
                            </div>
                            <div class="col-md-9">
                                <input name="phone" type="text" value="{{old('phone')}}" class="form-control" placeholder="{{ trans('lang.phone') }}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <input name="first_name" value="{{old('first_name')}}" type="text" class="form-control" placeholder="{{ trans('lang.first_name') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <input name="last_name" value="{{old('last_name')}}" type="text" class="form-control" placeholder="{{ trans('lang.last_name') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <input name="email" type="email" value="{{old('email')}}" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="{{trans('lang.email')}}">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="{{ trans('lang.password') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <input type="password" name="password_confirmation" class="form-control" id="exampleInputPassword2" placeholder="{{ trans('lang.confirm_password') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12  mb-4">
                                <div class="form-group mb-4">
                                    <input name="city" value="{{old('city')}}" type="text" class="form-control" placeholder="{{ trans('lang.city') }}">
                                </div>
                            </div>
                        </div>
                            <h5><input type="checkbox" required> <a href="{{url('/pages/terms')}}" target="_blanck">{{trans('lang.terms_statment')}} {{ trans('lang.terms_conditions') }}</a></h4>
                        <div class="col-md-4 offset-md-4 mt-5">
                            <button type="submit" class="btn btn-info btn-block">{{ trans('lang.signup') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>

        // $(document).ready(function(){
        //     name = $('#country').val();
        //     console.log(name != null)
        //     if(name != null){
        //         $("#city").find('option').remove();
        //         $.ajax({
        //             type : 'GET',
        //             url : '{{url("/get-cities")}}' + '/' + name,
        //             success: function (response) {
        //                 $('#city').append($("<option selected value='' disabled></option>").text('{{ trans('lang.select') }}')); 
        //                 $.each(response, function(key, value) {
        //                     $('#city').append($("<option></option>").attr("value",value.id).text(value.name)); 
        //                 });
        //                 $('#city').fadeIn();
        //             }, 
        //         });
        //     }
        // })
        $('#country').change(function(){
            name = $(this).val();
            code = $('option:selected', this).attr('code');
            $('#phone').val(code)
            $('#code').val(code)
            // $("#city").find('option').remove();
            // $.ajax({
            //     type : 'GET',
            //     url : '{{url("/get-cities")}}' + '/' + name,
            //     success: function (response) {
            //         $('#city').append($("<option selected value='' disabled></option>").text('{{ trans('lang.city') }}'));  
            //         $.each(response, function(key, value) {
            //             $('#city').append($("<option></option>").attr("value",value.name).text(value.name)); 
            //         });
            //         $('#city').fadeIn();
            //     }, 
            // });
        });
    </script>
@endsection
