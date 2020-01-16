@extends('layouts.front')

@section('title') {{ trans('lang.chackout') }} @endsection

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
			<div class="row">
				<div class="col-md-10 offset-md-1">
					<div class="data-login border p-4 bg-white">
						<h5 class="mb-5  text-center "> {{ trans('lang.chackout') }}  </h5>
						<form method="POST" action='{{route("chackout"])}}' class="modelForms">
							@csrf
							<div class="row">
								<div class="col-md-6">
									<div class="form-group mb-4">
										<label class="text-muted" > {{ trans('lang.first_name') }}  </label>
										<input type="text" name="first_name" value="{{old('first_name')}}" class="form-control" placeholder="{{ trans('lang.first_name') }}">
									</div>
								</div>

                                <div class="col-md-6">
									<div class="form-group mb-4">
										<label class="text-muted" > {{ trans('lang.last_name') }}  </label>
										<input type="text" name="last_name" value="{{old('last_name')}}" class="form-control" placeholder="{{ trans('lang.last_name') }}">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6 mb-3">
									<label class="text-muted" > {{ trans('lang.country') }} </label>
									<select class="form-control" name="country">
										<option selected disabled value="">{{ trans('lang.select') }}</option>
										@foreach ($countries as $country)
											<option value="{{$country->sortname}}" {{$country->sortname == old('country') ? 'selected' : ''}}>{{$country->name}}</option>
										@endforeach
									</select>
								</div>
								<div class="col-md-6 mb-3">
									<label class="text-muted" > {{ trans('lang.city') }} </label>
									<select class="form-control" name="city">
										<option selected disabled value="">{{ trans('lang.select') }}</option>
										@foreach ($cities as $city)
											<option value="{{$city->name}}" {{$city->name == old('city') ? 'selected' : ''}}>{{$city->name}}</option>
										@endforeach
									</select>
								</div>
                            </div>

                            <div class="row">
								<div class="col-md-6">
									<div class="form-group mb-4">
										<label class="text-muted" > {{ trans('lang.state') }}  </label>
										<input type="text" name="state" value="{{old('state')}}" class="form-control" placeholder="{{ trans('lang.state') }}">
									</div>
								</div>

                                <div class="col-md-6">
									<div class="form-group mb-4">
										<label class="text-muted" > {{ trans('lang.postcode') }}  </label>
										<input type="text" name="postcode" value="{{old('postcode')}}" class="form-control" placeholder="{{ trans('lang.postcode') }}">
									</div>
								</div>
                            </div>

                            <div class="row">
								<div class="col-md-6">
									<div class="form-group mb-4">
										<label class="text-muted" > {{ trans('lang.email') }}  </label>
										<input type="text" name="email" value="{{old('email')}}" class="form-control" placeholder="{{ trans('lang.email') }}">
									</div>
								</div>

                                <div class="col-md-6">
									<div class="form-group mb-4">
										<label class="text-muted" > {{ trans('lang.street1') }}  </label>
										<input type="text" name="street1" value="{{old('street1')}}" class="form-control" placeholder="{{ trans('lang.street1') }}">
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
	</div>
</div>
@endsection

@section('js')
<script src="{{asset('js/date-time-picker.min.js')}}"></script>
	<script>
		$('#birthdate').dateTimePicker();
		$('.modelForms').submit(function(){
			$('.button').attr('disabled','disabled');
		})
	</script>
@endsection
