@extends('layouts.front')

@section('title') {{ trans('lang.activation') }} @endsection

@section('content')
<div class="login my-5 ">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="data-login border p-4 bg-white">
                    <h5 class="mb-5 "> {{ trans('lang.activation') }} <a href="{{url('login')}}" class="float-right h-6 d-block"> {{trans('lang.login')}} </a></h5>
                    <form id="forms" method="POST" action="{{route('activationPost')}}" >
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
                        @csrf
                        <div class="form-group mb-4">
                            <input name="activation_code" type="number" class="form-control" aria-describedby="emailHelp" placeholder="{{trans('lang.activation')}}">
                        </div>

                        <div class="form-group mb-4">
                            <input name="email" type="email" class="form-control" aria-describedby="emailHelp" placeholder="{{trans('lang.email')}}">
                        </div>
                        <div class="col-md-4 offset-md-4 mt-5">
                            <button type="submit" class="btn btn-info btn-block">{{ trans('lang.activate') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
