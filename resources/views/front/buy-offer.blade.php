@extends('layouts.front')


@section('title') {{ trans('lang.offer-details') }} @endsection

@section('content')
<?php
if(LaravelLocalization::getCurrentLocale() == 'ar'){
	$description   = 'description_ar';
}else{
	$description   = 'description_en';
}
?>
<div class="details-offer">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					@if(session('danger'))
						<div class="alert alert-danger" style="text-align:center">
							{{session('danger')}}
						</div>
					@endif
						<?php
						if($offer->category_id == 1){
							$category = trans('lang.entertainment');
						}elseif($offer->category_id == 2){
							$category = trans('lang.educational');
						}elseif($offer->category_id == 3){
							$category = trans('lang.sport') ;
						}else{
							$category = trans('lang.medical');
						}
					?>
					<h6> <a href="{{url('/')}}"> {{ trans('lang.main') }} </a> / <a href="#">{{$category}}</a> 
					/ <a href="#" class="active">{{ trans('lang.buy_trip') }}</a>
						</h6>
					<h3 class="mt-4">{{ trans('lang.trip_information') }}</h3>
				</div>
				<div class="col-md-12">
					<div class="desc-offer">
						<div class="row">
							<div class="col-md-4 border-right">
								<div class="first-desc">
									<div class="row">
										<div class="col-2">
											<span><img class="img-fluid" src="{{asset('img/flag.png')}}" alt="flag"> </span>
										</div>
										<div class="col-10">
											<h5> {{$offer->country->name}} - {{$offer->city->name}}</h5>
											<p> {{$offer->provider->first_name . ' ' . $offer->provider->last_name}}</p>
												<ul class="list-inline mt-2">
													{{$offer->rate }} / 10
												</ul>
												<p> {{ trans('lang.num_of_rates') }} : {{ $offer->rates_count}}</p>
											{{-- <small> 20-10-2018 </small> --}}
										</div>
									</div>
								</div>
							</div>
							<!--  -->
							<div class="col-md-5 border-right">
								<div class="tow-desc">
										<ul class="list-unstyled">
											<li> {{ trans('lang.hotel_level') }} : <span class="float-right">
												@for ($i = 0; $i < $offer->hotel_level; $i++)
													<li class="list-inline-item"><i class="far fa-star"></i></li>
												@endfor
											</li>
											<li>{{ trans('lang.num_persons') }} :<span class="float-right"> {{$offer->persons}} {{trans('lang.persons')}} </span> </li>
											<li>{{ trans('lang.transport') }} : <span class="float-right"> {{trans("lang.$offer->transport")}} </span></li>
										</ul>
								</div>
							</div>
							<!--  -->
							<div class="col-md-3 text-center">
								<div class="three-desc">
									<div class="row">
										{{-- <div class="col-2">
											<img class="img-fluid d-block mx-auto" src="{{asset('img/offers.png')}}" alt="offers">
										</div> --}}
										<div class="col-10">
												<p>{{trans('lang.from')}} {{$offer->from}}  - {{trans('lang.to')}} {{$offer->to}} </p>
												<p>{{trans('lang.end_at')}} {{$offer->end_at}}</p>
												<p>{{$offer->days}} {{trans('lang.days')}} - {{$offer->days - 1}} {{trans('lang.night')}}</p>
												<h6>{{ trans('lang.total') }}</h6>
												<h5>
													@if(auth('clients')->check())
														@if (auth('clients')->user()->currency == 'dollar')
															@if($offer->currency == 'sar')
																{{round($offer->price / 3.75)}} {{trans("lang.dollar")}}
															@else
																{{$offer->price}} {{trans("lang.dollar")}}
															@endif
														@else
															@if($offer->currency == 'sar')
																{{$offer->price}} {{trans("lang.sar")}}
															@else
																{{round($offer->price * 3.75)}} {{trans("lang.sar")}}
															@endif
														@endif
													@else
														{{$offer->price}} {{$offer->currency}}
													@endif
												</h5>
										</div>
									</div>
								</div>
							</div>
							<!--  -->
							<div class="col-md-12">
								<div class="slider-photo">
									<hr>
									<h6 class="mb-3"> {{ trans('lang.note') }}</h6>
									<div class="row">
										<div class="col-md-9">
											<p class="mt-2">{{$offer->$description}}</p>
										</div>
										@if($checkPayment == null)
											<div class="col-md-3">
												<button class="btn btn-success btn-block" onclick="location.href='{{url('/passenger/offers/'.$offer->id)}}';">{{ trans('lang.complate_payment') }}</button>
											</div>
										@endif

									</div>
								</div>
							</div>
							<!--  -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	{{-- <div class="payment-gateway">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="payment-complate">
					  <h4 class="text-center"> Payment gateway goes here </h4>
					</div>
				</div>
			</div>
		</div>
	</div> --}}

@endsection
 