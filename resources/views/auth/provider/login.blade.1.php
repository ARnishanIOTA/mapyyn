@extends('layouts.auth')

@section('title') {{ trans('lang.login') }} @endsection

@section('content')

    <div class="m-login__logo">
        <a href="#">
            <img src="{{asset('assets/images/logo-1.png')}}">
        </a>
    </div>
    <div class="m-login__signin">
        <div class="m-login__head">
            <h3 class="m-login__title">{{ trans('lang.sign_provider') }}</h3>
        </div>
        <form class="m-login__form m-form" id="login"  method="POST" action="{{ route('login-provider') }}">
            @if (session('status'))
                <div class="alert alert-success animated fadeIn" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success animated fadeIn" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('danger'))
                <div class="alert alert-danger animated fadeIn" role="alert">
                    {{ session('danger') }}
                </div>
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                <div class="alert alert-danger animated fadeIn" role="alert">
                        {{ $error }}
                    </div>
                @endforeach
            @endif
            @csrf
            <div class="form-group m-form__group">
                <input class="form-control m-input" type="text" placeholder="{{ trans('lang.email') }}" name="email" autocomplete="off">
            </div>
            <div class="form-group m-form__group">
                <input class="form-control m-input m-login__form-input--last" type="password" placeholder="{{ trans('lang.password') }}" name="password">
            </div>
            <div class="row m-login__form-sub">
                <div class="col m--align-left m-login__form-left">
                    <label class="m-checkbox  m-checkbox--light">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        {{ trans('lang.remember') }}
                        <span></span>
                    </label>
                </div>

                <div class="col m--align-right m-login__form-right">
                    <a href="javascript:;" id="m_login_forget_password" class="m-link">{{ trans('lang.forgot_password') }}</a>
                </div>

            </div>
            <div class="m-login__form-action">
                <button id="m_login_signin_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn">{{ trans('lang.login') }}</button>
            </div>
        </form>
    </div>

    
<div class="m-login__signup">
    <div class="m-login__head">
        <h3 class="m-login__title">
            {{ trans('lang.signup') }}
        </h3>
        <div class="m-login__desc">
            {{ trans('lang.signup_provider') }}
        </div>
    </div>
<form id="registration" class="m-login__form m-form" action="{{route('provider.register')}}" method="POST" enctype="multipart/form-data">
        <?php 
            if(LaravelLocalization::getCurrentLocale() == 'ar'){
                $name = 'name_ar';
            }else{
                $name = 'name_en';
            }    
        ?>
        @csrf
        <div class="form-group m-form__group">
            <input type="text" name="name" value="{{old('name')}}" class="form-control m-input m-input--square" id="name" placeholder="{{trans('lang.name')}}">
        </div>


        <div class="form-group m-form__group">
            <input type="text" name="email" value="{{old('email')}}" class="form-control m-input m-input--square" id="email" placeholder="{{trans('lang.email')}}">
        </div>

<br>

        <div class="form-group m-form__group">
            <select class="form-control m-select2" style="width:100%" name="country" id="country">
                <option></option>
                @foreach ($countries as $country)
            <option value="{{$country->id}}" code="{{$country->phonecode}}">{{$country->$name}}</option>
                @endforeach
            </select>
        </div>
    


        <div class="form-group m-form__group" id="phoneGroup">
            <input type="text" name="phone" value="{{old('phone')}}" class="form-control m-input m-input--square" id="phone" placeholder="{{trans('lang.phone')}}">
        </div>

        <div class="form-group m-form__group" id="imageGroup">
            <input type="file" name="logo" class="form-control m-input m-input--square" id="customFile">
        </div>

        <div class="form-group m-form__group">
            <input type="password" name="password" class="form-control m-input m-input--square" id="password" placeholder="{{trans('lang.password')}}">
        </div>

        <div class="form-group m-form__group" id="addressGroup">
            <input type="text" name="address" value="{{old('address')}}" class="form-control m-input m-input--square" id="address" placeholder="{{trans('lang.address')}}">
        </div>
        <br>

        
        <div class="form-group m-form__group" id="cityGroup" style="display:none">
            <select class="form-control m-select2" style="width:100%" name="city" id="city" placeholder="{{trans('lang.city')}}">
                <option></option>
            </select>
        </div>
<br>
        <div class="form-group m-form__group" id="other_cityGroup">
            <input type="text" name="other_city" value="{{old('other_city')}}" class="form-control m-input m-input--square"  placeholder="{{trans('lang.other_city')}}">
        </div>
    
        <div class="form-group m-form__group">
            <select class="form-control" name="categories[]" multiple style="width:100%" placeholder="{{trans('lang.categories')}}">
                <option value="1">{{trans('lang.entertainment')}}</option>
                <option value="2">{{trans('lang.educational')}}</option>
                <option value="3">{{trans('lang.sport')}}</option>
                <option value="4">{{trans('lang.medical')}}</option>
            </select>
        </div>
        
        <div class="m-login__form-action">
            <button id="m_login_signup_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn">
                {{ trans('lang.signup') }}
            </button>
            &nbsp;&nbsp;
            <button id="m_login_signup_cancel" class="btn btn-outline-focus m-btn m-btn--pill m-btn--custom m-login__btn">
                {{ trans('lang.cancel') }}
            </button>
            <button style="display:none" type="reset" id="reset"> </button>
        </div>
    </form>
</div>

<div class="m-login__forget-password">
    <div class="m-login__head">
        <h3 class="m-login__title">{{ trans('lang.forgot_password') }}</h3>
    </div>

    <form class="m-login__form m-form"  method="POST" action="{{ route('providers.password.email') }}">
        @csrf
       
        <div class="form-group m-form__group">
            <input class="form-control m-input" type="text" placeholder="{{ trans('lang.email') }}" name="email" autocomplete="off">
        </div>
        
        <div class="m-login__form-action">
            <button  class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn">{{ trans('lang.reset_password') }}</button>
            <button id="m_login_forget_password_cancel" class="btn btn-outline-focus m-btn m-btn--pill m-btn--custom  m-login__btn">{{ trans('lang.cancel') }}</button>
        </div>
    </form>
        
</div>


<div class="m-login__account">
<span class="m-login__account-msg">
{{ trans('lang.haveAccount') }}
</span>
&nbsp;&nbsp;
<a href="javascript:;" id="m_login_signup" class="m-link m-link--light m-login__account-link">
{{ trans('lang.signup') }}
</a>
</div>
@endsection
