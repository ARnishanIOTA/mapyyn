@extends('layouts.front')


@section('title') {{ trans('lang.contact_us') }} @endsection

@section('content')
    <!-- start booking  -->
	<div class="booking ">
			<div class="container">
				<div class="row">
					<div class="col-md-12 mb-5">
						<h4 class="active text-center"> {{ trans('lang.contact_us') }} </h4>
					</div>
					<div class="col-md-12">
						<div class="booking-content">
							<form method="POST" action="{{route('contact_us')}}" id="modelForm">
								@csrf
								<div class="row">

									<div class="col-md-12">
										<div class="form-group mb-4">
											<select name="country_id" class="form-control p-3" id="country">
												<option selected value="" disabled>{{ trans('lang.country') }}</option>
												@foreach ($countries as $country)
													<option value="{{$country->id}}" {{old('country') == $country->name ? 'selected' : ''}} code='{{$country->phonecode}}'>{{$country->name}}</option>
												@endforeach
											</select>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group mb-4">
											<input name="name" type="text" class="form-control" placeholder="{{ trans('lang.name') }}">
										</div>

										<div class="form-group mb-4">
											<div class="row">
												<div class="col-md-3">
													<input type="text" id="phone" class="form-control" disabled placeholder="{{trans('lang.code')}}">
												</div>
												<div class="col-md-9">
													<input name="phone" type="text" class="form-control" placeholder="{{ trans('lang.phone') }}">
												</div>
											</div>
											
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group mb-4">
											<input name="email" type="text" class="form-control" placeholder="{{ trans('lang.email') }}">
										</div>

										<div class="form-group mb-4">
											<input name="subject" type="text" class="form-control" placeholder="{{ trans('lang.subject') }}">
										</div>
									</div>
								</div>
									
								<div class="row">
									<div class="col-md-12  ">
										<div class="custome-input full-row ">
											<div class="form-group">
												<textarea name="message" class="form-control" id="Textarea" placeholder="{{ trans('lang.message') }}"></textarea>
											</div>
										</div>
									</div>

									<div class="col-6 col-md-4 offset-md-4 mt-5">
										<button class="btn btn-info btn-block" id="button">{{ trans('lang.send') }}</button>
									</div>
								</div>	
								<button type="reset" id="reset" style="display:none"></button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End booking  -->
@endsection
@section('js')
	<script>
	@if(LaravelLocalization::getCurrentLocale() == 'ar')
		successMessage = 'تم ارسال رسالتك بنجاح'
	@else
		successMessage = 'Your message has been sent'
	@endif
		$('#country').change(function(){
            code = $('option:selected', this).attr('code');
            $('#phone').val(code)
        });
	</script>
@endsection