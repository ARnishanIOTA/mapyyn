@extends('layouts.auth')

@section('title') {{ trans('lang.reset_password') }} @endsection

@section('content')
    <div class="m-login__logo">
        <a href="#">
            <img src="{{asset('assets/images/logo-1.png')}}">
        </a>
    </div>
    <div class="m-login__signin">
        <form class="m-login__form m-form" id="login"  method="POST" action="{{ route('providers.password.request') }}">
            @csrf
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                <div class="alert alert-danger" role="alert">
                        {{ $error }}
                    </div>
                @endforeach
            @endif

            <input type="hidden" name="token" value="{{request('token')}}">
            <div class="form-group m-form__group">
                <input class="form-control m-input" type="text" placeholder="{{ trans('lang.email') }}" name="email" autocomplete="off">
            </div>

            <div class="form-group m-form__group">
                <input class="form-control m-input" type="password" placeholder="{{ trans('lang.password') }}" name="password" autocomplete="off">
            </div>

            <div class="form-group m-form__group">
                <input class="form-control m-input" type="password" placeholder="{{ trans('lang.confirm_password') }}" name="password_confirmation" autocomplete="off">
            </div>
            
            <div class="m-login__form-action">
                <button id="m_login_signin_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn">{{ trans('lang.reset_password') }}</button>
            </div>
        </form>
    </div>
@endsection

