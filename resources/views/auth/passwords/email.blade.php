@extends('layouts.front')

@section('title') {{ trans('lang.login') }} @endsection

@section('content')
<div class="login my-5 ">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="data-login border p-4 bg-white">
                    <h5 class="mb-5 "> {{ trans('lang.reset_password') }}</h5>
                    
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="form-group mb-4">
                            <input id="custome-input" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required  placeholder="{{trans('lang.email')}}">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="col-md-4 offset-md-4 mt-5">
                            <button type="submit" class="btn btn-info btn-block" id="button">{{ trans('lang.reset_password') }}</button>
                            <button type="reset" id="reset" style="display:none"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

