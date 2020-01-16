@extends('layouts.front')


@section('title') {{ trans('lang.offer-details') }} @endsection

@section('css')
	<link rel="stylesheet" href="{{asset('/swiper.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/lity.min.css')}}">
@endsection

@section('content')
<?php if(LaravelLocalization::getCurrentLocale() == 'ar'){
	$ads = 'ads_ar.jpg';
	$align = 'align_ar.jpg';
	$description   = 'description_ar';
}else{
	$ads = 'ads_en.jpg';
	$align = 'align_en.jpg';
	$description   = 'description_en';

}?>
<!--  details-offer -->
	<div class="details-offer">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					@if(session('success'))
						<div class="alert alert-success" style="text-align:center">
							{{session('success')}}
						</div>
					@endif
					<div class="row">
						<div class="col-md-6">
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
						<h6> <a href="{{url('/')}}"> {{ trans('lang.main') }} </a> / <a href="#" class="active">{{ trans('lang.offers') }} : {{$category}}</a> </h6>
						</div>
						<div class="col-md-6">
							<h3 class="float-right">  {{trans('lang.trip_to')}} : {{$offer->country->name}} - {{$offer->city->name}}</h3>
						</div>
					</div>
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
											@if($payment != null)
												@if ($payment->count() > 0)
													<h5> {{ trans('lang.continueData') }}</h5>
													<?php $p_count = 1; ?>
													@foreach($payment as $key => $p)
														<a href="{{asset("uploads/$p->file")}}" download >{{$p_count}} - {{ trans('lang.download') }}</a>
														<?php $p_count = $p_count + 1;?> 
													@endforeach
												@endif
											@endif
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
										</span> </li>
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
									@if ($offer->$description != null)
										<h6 class="mb-3">{{ trans('lang.note') }}</h6>
										<p>{{$offer->$description}}</p>
									@endif

									<div class="item-photo d-none d-sm-block  mt-5">
										<div class="swiper-container">
											<div class="swiper-wrapper">
												@foreach ($offer->images as $image)
													<div class="swiper-slide">
														<a href="{{asset("uploads/$image->image")}}" data-lity><img class="d-block w-100 h-100" src='{{asset("uploads/$image->image")}}' alt="First slide"></a>
													</div>
												@endforeach
											</div>
											<!-- Add Pagination -->
											<div class="swiper-pagination"></div>
										</div>
									</div>
								</div>
							</div>
							<!--  -->
						</div>
					</div>
					<div class="col-md-4 offset-md-4 mt-5">
						@if (auth('clients')->check())
							@if($checkPayment == null)
								<a href="{{route('client-buy-offer', ['id' => $offer->id])}}" class="btn btn-success btn-block">{{ trans('lang.buy') }}</a>
							@endif
						@else
							<a href="{{route('login-client')}}" class="btn btn-success btn-block">{{ trans('lang.must_login') }}</a>
						@endif
					</div>
					<div class="col-md-12 mt-3">
						<a href="{{route('client-request-offer')}}"><img class="img-fluid" src="{{asset("img/$align")}}" alt="travel"></a>
					</div>
					<!-- space ads -->
					<div class="col-md-12">
						<div class="space-ads">
							<div class="row">
								<div class="col-md-6">
									<div class="item">
											<?php $image = count($offerAds) > 0 ? $offerAds[0]['image'] : $ads ;?>
											<img class="img-fluid w-100 h-100" src='{{asset("uploads/$image")}}' alt="ads">
									</div>
								</div>
								<div class="col-md-6">
									<div class="item">
										<?php $image = count($offerAds) > 1 ? $offerAds[1]['image'] : $ads;?>
										<img class="img-fluid w-100 h-100" src='{{asset("uploads/$image")}}' alt="ads">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
<script src="{{asset('/js/swiper.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/lity.min.js')}}"></script>
<script>
		var swiper = new Swiper('.swiper-container', {
		  slidesPerView: 4,
		  spaceBetween: 30,
		  pagination: {
			el: '.swiper-pagination',
			clickable: true,
		  },
		});
	  </script>
@endsection