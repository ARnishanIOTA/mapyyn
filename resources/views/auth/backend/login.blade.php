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
            <h3 class="m-login__title">{{ trans('lang.sign_admin') }}</h3>
        </div>
        <form class="m-login__form m-form" id="login"  method="POST" action="{{ route('login-backend') }}">
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

    <div class="m-login__forget-password">
        <div class="m-login__head">
                <h3 class="m-login__title">{{ trans('lang.forgot_password') }}</h3>
                <div class="m-login__desc">{{ trans('lang.passwordMessage') }}</div>
        </div>
    
        <form class="m-login__form m-form" id="login"  method="POST" action="{{ route('users.password.email') }}">
            @csrf
           
            <div class="form-group m-form__group">
                <input class="form-control m-input" type="text" placeholder="{{ trans('lang.email') }}" name="email" autocomplete="off">
            </div>
            
            <div class="m-login__form-action">
                <button id="m_login_signin_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn">{{ trans('lang.reset_password') }}</button>
                <button id="m_login_forget_password_cancel" class="btn btn-outline-focus m-btn m-btn--pill m-btn--custom  m-login__btn">{{ trans('lang.cancel') }}</button>
            </div>
        </form>
            
    </div>
@endsection
