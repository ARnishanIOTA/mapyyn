@extends('layouts.front')

@section('title') {{ trans('lang.login') }} @endsection

@section('content')
<div class="login my-5 ">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="data-login border p-4 bg-white">
                    <h5 class="mb-5 "> {{ trans('lang.login') }} <a href="{{url('register')}}" class="float-right h-6 d-block"> {{trans('lang.register')}} </a></h5>
                    <form id="forms" method="POST" action="{{route('login-client')}}">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{session('success')}}
                            </div>
                        @endif
                        @if (session('danger'))
                            <div class="alert alert-danger">
                                {{session('danger')}}
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </div>
                        @endif
                        <div class="form-group mb-4">
                            <input id="custome-input" name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="{{trans('lang.email')}}">
                        </div>
                        <div class="form-group">
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="{{trans('lang.password')}}">
                        </div>
                        <div class="form-group form-check">
                            @csrf
                            <div class="row">
                                <div class="col-6">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">{{trans('lang.remember')}} </label>
                                </div>
                                <div class="col-6 ">
                                <a href="{{route('password.request')}}" class="float-right d-block">{{trans('lang.forgot_my_password')}}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 offset-md-4 mt-5">
                            <button type="submit" class="btn btn-info btn-block">{{ trans('lang.signin') }}</button>
                            <button type="reset" id="reset" style="display:none"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
