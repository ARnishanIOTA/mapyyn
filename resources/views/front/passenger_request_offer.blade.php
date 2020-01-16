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
                                <div class="col-md-6 mb-4">
                                    <div class="custome-input">
                                        <label class="text-muted" > {{ trans('lang.country') }} </label>
                                        <select class="form-control" name="country" id="from_country">
                                            <option selected value="" disabled>{{ trans('lang.country') }}</option>
                                            @foreach ($countries as $country)
                                                <option value="{{$country->sortname}}" data-code="{{$country->sortname}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                   </div>
                                </div>
                                <div class="col-md-6  mb-4" id="mapToggle">
                                        <div class="custome-input">
                                            <label class="text-muted">{{ trans('lang.city') }}</label>
                                            <input type="text" name="city"  class="form-control p-3" id="fromm_city">
                                        </div>
                                 </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="custome-input" > {{ trans('lang.state') }}  </label>
                                        <input type="text" name="state" class="form-control" placeholder="{{ trans('lang.state') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
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
                            <h5><input type="checkbox" required><a href="{{url('/pages/terms')}}" target="_blanck">{{trans('lang.terms_statment')}} {{ trans('lang.terms_conditions') }}</a></h4>
                            <div class="col-md-4 offset-md-4 mt-5">
                                <button type="submit" class="btn btn-info btn-block button"> {{ trans('lang.next') }} </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
		<h2>{{$persons}} / {{$sessionCount}}</h2>
			<div class="row">
				<div class="col-md-10 offset-md-1">
					<div class="data-login border p-4 bg-white">
						<h5 class="mb-5  text-center "> {{ trans('lang.continueDataAdd') }}  </h5>
						<form method="POST" action='{{route("passengerRequestOffer", ['id' => $requestOffer->id])}}' class="modelForms">
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
										<input type="text" name="passport_number" class="form-control" placeholder="{{ trans('lang.passportNumber') }}">
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
		$('.modelForms').submit(function(){
			$('.button').attr('disabled','disabled');
		})
	</script>
    <script type="text/javascript">
        function initMap() {
        
        $('#from_country').change(function(){
            var input = document.getElementById('fromm_city');

            country = $('#from_country').find(':selected').data('code');
            $('#mapToggle').show();
            var options = {
                types: ['(cities)'],
                componentRestrictions: {country: country}
            };
            
            var autocomplete = new google.maps.places.Autocomplete(input, options);
            var infowindow = new google.maps.InfoWindow();

            autocomplete.addListener('place_changed', function() {
            infowindow.close();
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    document.getElementById("fromm_city").style.display = 'inline-block';
                    document.getElementById("fromm_city").innerHTML = "Cannot Locate '" + input.value + "' on map";
                    return;
                }
                document.getElementById('from_city').value = place.place_id; // place id

            });
        })

       
    }
</script>
@if (LaravelLocalization::getCurrentLocale() == 'ar')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCt9ApcngmV7Zj_XR8h5hoznS1EaYuPLhI&libraries=places&callback=initMap&language=ar" async defer></script>
@else
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCt9ApcngmV7Zj_XR8h5hoznS1EaYuPLhI&libraries=places&callback=initMap&language=en" async defer></script>
@endif
@endsection
