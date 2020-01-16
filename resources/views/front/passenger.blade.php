@extends('layouts.front')

@section('title') {{ trans('lang.passenger') }} @endsection

@section('content')

<div class="login my-5 ">
	<style>
		.description-input {
			color: brown !important;
		}
	</style>
	<div class="container">
		@if ($errors->any())
			@foreach ($errors->all() as $error)
				<div class="alert alert-danger">
					<li>{{$error}}</li>
				</div>
			@endforeach
        @endif
        @if ($checkoutCheck)
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="data-login border p-4 bg-white">
                        <h5 class="mb-5  text-center "> {{ trans('lang.chackout') }}  </h5>
                        <form method="POST" action='{{route("chackout")}}' class="modelForms">
                            @csrf
                            <input type="hidden" name="offer_id" value="{{$offer->id}}" class="form-control">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-muted" > {{ trans('lang.first_name') }}  </label>
                                        <input type="text" name="first_name" class="form-control" placeholder="{{ trans('lang.first_name') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-muted" > {{ trans('lang.last_name') }}  </label>
                                        <input type="text" name="last_name" class="form-control" placeholder="{{ trans('lang.last_name') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted" > {{ trans('lang.country') }} </label>
                                    <select class="form-control" name="country">
                                        <option selected disabled value="">{{ trans('lang.select') }}</option>
                                        @foreach ($countries as $country)
                                            <option value="{{$country->sortname}}">{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted" > {{ trans('lang.city') }} </label>
                                    <select class="form-control" name="city">
                                        <option selected disabled value="">{{ trans('lang.select') }}</option>
                                        @foreach ($cities as $city)
                                            <option value="{{$city->name}}">{{$city->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-muted" > {{ trans('lang.state') }}  </label>
                                        <input type="text" name="state" class="form-control" placeholder="{{ trans('lang.state') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-muted" > {{ trans('lang.postcode') }}  </label>
                                        <input type="text" name="postcode" class="form-control" placeholder="{{ trans('lang.postcode') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-muted" > {{ trans('lang.email') }}  </label>
                                        <input type="text" name="email" class="form-control" placeholder="{{ trans('lang.email') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-muted" > {{ trans('lang.street1') }}  </label>
                                        <input type="text" name="street1" class="form-control" placeholder="{{ trans('lang.street1') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="text-muted" > {{ trans('lang.go_date') }}  </label>
                                        <input type="test" name="go_date" id="go_date" class="form-control" placeholder="{{ trans('lang.go_date') }}">
                                    </div>
                                </div>
                            </div>
                            <h5><input type="checkbox" required><a href="{{url('/pages/terms')}}" target="_blanck">{{trans('lang.terms_statment')}} {{ trans('lang.terms_conditions') }}</a></h4>
                            <div class="col-md-4 offset-md-4 mt-5">
                                <button type="submit" class="btn btn-info btn-block button"> {{ trans('lang.next') }} </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
        <h2>{{$offer->persons}} / {{$sessionCount}}</h2>
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="data-login border p-4 bg-white">
                    <h5 class="mb-5  text-center "> {{ trans('lang.continueDataAdd') }}  </h5>
                    <form method="POST" action='{{route("passengerOffer", ['id' => $offer->id])}}' class="modelForms">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="text-muted" > {{ trans('lang.full_name') }}  </label>
                                    <input type="text" name="name" class="form-control" placeholder="{{ trans('lang.full_name') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="text-muted" > {{ trans('lang.birthdate') }} </label>
                                    <input type="text" name="birthdate" id="birthdate" class="form-control" aria-describedby="emailHelp" placeholder="{{ trans('lang.birthdate') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="text-muted" > {{ trans('lang.first_name') }}  </label>
                                    <input type="text" name="first_name" class="form-control" placeholder="{{ trans('lang.first_name') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="text-muted" > {{ trans('lang.last_name') }} </label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" aria-describedby="emailHelp" placeholder="{{ trans('lang.last_name') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted" > {{ trans('lang.passportCountry') }} </label>
                                <select class="form-control" name="passport_country">
                                    <option selected disabled value="">{{ trans('lang.select') }}</option>
                                    @foreach ($countries as $country)
                                        <option value="{{$country->name}}">{{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 ">
                                <div class="form-group mb-4">
                                    <label class="text-muted" > {{ trans('lang.passportNumber') }}    </label>
                                    <input type="number" name="passport_number" class="form-control" placeholder="{{ trans('lang.passportNumber') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="text-muted" >  {{ trans('lang.nationality') }}</label>
                                <select class="form-control" name="nationality">
                                    <option selected disabled>{{ trans('lang.select') }}</option>
                                    @foreach ($countries as $country)
                                        <option value="{{$country->name}}">{{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="text-muted" > {{ trans('lang.passportEndDate') }} </label>
                                    <input type="text" name="passport_end_date" id="passportEndDate" class="form-control" aria-describedby="emailHelp" placeholder="{{ trans('lang.passportEndDate') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 offset-md-4 mt-5">
                            <button type="submit" class="btn btn-info btn-block button"> {{ trans('lang.passengerRegister') }} </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

	</div>
</div>
@endsection

@section('js')
<script src="{{asset('js/date-time-picker.min.js')}}"></script>
	<script>
		$('#birthdate').dateTimePicker();
		$('#passportEndDate').dateTimePicker();
		$('#go_date').dateTimePicker();
		$('.modelForms').submit(function(){
			$('.button').attr('disabled','disabled');
		})
	</script>

@endsection
