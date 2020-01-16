@extends('layouts.front')

@section('title') {{ trans('lang.main') }} @endsection


@section('css')
<link rel="stylesheet" type="text/css" href='{{asset("show-maps.css")}}'>
{{-- <link rel="stylesheet" href="{{asset('/swiper.min.css')}}"> --}}
<link rel="stylesheet" href="{{asset('assets/css/lity.min.css')}}">
{{-- <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> --}}
<style>
		.mySlides {display:none;}
		#tpcwl_iframe { min-height: 250px !important; }
</style>
@endsection


@section('content')
<?php if(LaravelLocalization::getCurrentLocale() == 'ar'){
	$name = 'name_ar';
	$of = 'اذهب الى العرض';
	$message = 'لا يوجد نتائج';
	$currency = 'ريال';
	$ads = 'ads_ar.jpg';
	$align = 'align_ar.jpg';

}else{
	$of = 'Go To Offer';
	$name = 'name_en';
	$message = 'there are no result';
	$currency = 'SAR';
	$ads = 'ads_en.jpg';
	$align = 'align_en.jpg';
}?>
    <!-- Start Header -->
	<header class="header">
        <div class="container">
            <div class="row">
                <!-- search-bar -->
                <div class="col-md-12">
                    @if(session('status'))
                        <div class="alert alert-success">
                            {{session('status')}}
                        </div>
                    @endif
                    <div class="search-bar">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="heading-bar">
                                    <h6>{{ trans('lang.search') }}</h6>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="content">

                                        <script charset="utf-8" src="//www.travelpayouts.com/widgets/4b648caa19429c236bdccbcf54520069.js?v=1896"></script>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- search-bar -->
            </div>
        </div>
    </header>
	<!-- End Header -->

	<!-- start space-ads -->
	<div class="space-ads">
		<div class="container">
			<div class="row">
			<div class="col-md-12">
				@foreach ($sliders as $slider)
						<a href="{{asset('uploads/$slider->image')}}" data-lity><img  src="{{asset('uploads/$slider->image')}}" class="mySlides" height="90" style="width:100%"></a>
				@endforeach
			</div>
					
			</div>
		</div>
	</div>


<div class="map">

		<div class="container">
			<div id="map" style="height: 500px;"></div>
		</div>
	</div>

		<div class="offers">
		<div class="container">
			
			<div class="row">
				<!-- start item -->
				<div class="col-md-3">
					<a href="{{route('all-offers', ['category' => 1])}}">
						<div class="item mb-3">
							<img class="img-fluid d-block mx-auto" src="/img/funy.png" alt="funy">
							<h6 class="text-center mt-5"> {{ trans('lang.entertainment') }} </h6>
						</div>
					</a>
				</div>
				<!-- End item -->
				<!-- start item -->
				<div class="col-md-3">
					<a href="{{route('all-offers', ['category' => 3])}}">
						<div class="item mb-3">
							<img class="img-fluid d-block mx-auto" src="/img/soprt.png" alt="funy">
							<h6 class="text-center mt-5"> {{ trans('lang.sport') }} </h6>
						</div>
					</a>
				</div>
				<!-- End item -->
				<!-- start item -->
				<div class="col-md-3">
					<a href="{{route('all-offers', ['category' => 2])}}">
						<div class="item mb-3">
							<img class="img-fluid d-block mx-auto" src="/img/education.png" alt="funy">
							<h6 class="text-center mt-5"> {{ trans('lang.education') }} </h6>
						</div>
					</a>

				</div>
				<!-- End item -->
				<!-- start item -->
				<div class="col-md-3">
					<a href="{{route('all-offers', ['category' => 4])}}">
						<div class="item mb-3">
							<img class="img-fluid d-block mx-auto" src="/img/healthy.png" alt="funy">
							<h6 class="text-center mt-5"> {{ trans('lang.medical') }} </h6>
						</div>
					</a>
				</div>
				<!-- End item -->
			</div>
		</div>
	</div>

	<div class="aline-ads">
		<div class="container">
			<div class="row">
			<a href="{{route('client-request-offer')}}"><img class="img-fluid" src="/img/{{$align}}" alt="aline-ads"></a>
			</div>
		</div>
	</div>

	<div class="best-offers" id="bestOffers">
		<div class="container">
			<h3 class="text-center">{{ trans('lang.best_offer') }} </h3>
			<div class="row">
				<!-- start item -->
					@foreach ($bestOffers as $bestOffer)
						<div class="col-md-6">
							<a href='{{route('client-offer-details', ['id' => $bestOffer->id])}}'>
								<div class="item mb-4">
									<div class="row">
										<div class="col-md-4">
										<?php 
											foreach ($bestOffer->images as $image) {
												
											}
										?>
										<img class="img-fluid w-100 h-100" src="{{asset("uploads/$image->image")}}" alt="Turkey">
										</div>
										<div class="col-md-8">
											<div class="desc">
												<div class="row">
													<div class="col-9">
															<h6> {{$bestOffer->country->name}} - {{$bestOffer->city->name}} </h6>
													<small>{{$bestOffer->days}}&nbsp; {{ trans('lang.day') }} &nbsp;{{$bestOffer->days - 1}}&nbsp; {{ trans('lang.night') }} </small>
														<ul class="list-inline mt-2">
															{{$bestOffer->rate }} / 10
														</ul>
														<?php
														if($bestOffer->category_id == 1){
															$category = trans('lang.entertainment');
														}else if($bestOffer->category_id == 2){
															$category = trans('lang.educational');
														}else if($bestOffer->category_id == 3){
															$category = trans('lang.sport');
														}else{
															$category = trans('lang.medical');
														}
													?>
													<h5> {{$category}} </h5>
													</div>
													<div class="col-3 text-center mt-2">
														{{-- <img class="img-fluid d-block mx-auto" src="img/offers.png" alt="offers"> --}}
														@if(auth('clients')->check()) 
															@if (auth('clients')->user()->currency == 'dollar')
																@if($bestOffer->currency == 'sar')
																	{{round($bestOffer->price / 3.75)}} {{trans("lang.dollar")}}
																@else
																	{{$bestOffer->price}} {{trans("lang.dollar")}}
																@endif
															@else
																@if($bestOffer->currency == 'sar')
																	{{$bestOffer->price}} {{trans("lang.sar")}}
																@else
																	{{round($bestOffer->price * 3.75)}} {{trans("lang.sar")}}
																@endif
															@endif 
														@else
																{{$bestOffer->price}} {{$bestOffer->currency}}
														@endif	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
					@endforeach
				
				<!-- start item -->
				<div class="col-md-4 offset-md-4 mt-5">
				<button class="btn btn-info btn-block" onclick="window.location.href='{{route('all-offers')}}'"> {{ trans('lang.more') }} </button>
				</div>
			</div>
		</div>
	</div>


	<div class="show-video">
		<div class="container">
			<h3 class="text-center">{{ trans('lang.knowus') }} </h3>
			<div class="row">
				<div class="col-md-10 offset-md-1">
					<div class="video embed-responsive embed-responsive-16by9">
						<video  id="video" controls="" poster="/uploads/videos/poster.png" >
							<source type="video/mp4" src="{{ '/uploads/videos/'.$Videos->video }}">
						</video>
					</div>
					
					
				</div>
				<div class="col-md-12">
					<div class="space-ads">
						<div class="row">
								<div class="col-md-6">
									<div class="item">
											<?php $image = count($adsBottom) > 0 ? $adsBottom[0]['image'] : $ads ;?>
											<img class="img-fluid ads_banner" src='{{asset("uploads/$image")}}' alt="ads">
									</div>
								</div>
								<div class="col-md-6">
									<div class="item">
										<?php $image = count($adsBottom) > 1 ? $adsBottom[1]['image'] : $ads;?>
										<img class="img-fluid ads_banner" src='{{asset("uploads/$image")}}' alt="ads">
									</div>
								</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="payment">
		<div class="container">
			<div class="row">
				<!-- download app -->
				<div class="col-md-6 text-center">
					<h5 class="mb-4">{{trans('lang.download_on_your_phone')}} </h5>
					<div class="row">
						<div class="col-6">
							<a href="#"> <img class="img-fluid w-100" src="/img/appStoer.png" alt="appStoer"> </a>
						</div>
						<div class="col-6">
							<a href="#"> <img class="img-fluid w-100" src="/img/googlePlay.png" alt="googlePlay"> </a>
						</div>
					</div>
				</div>
				<!-- download app -->
				<!-- paymet mothed -->
				<div class="col-md-6 text-center">
					<h5 class="mb-4 mb-4"> {{ trans('lang.payment_ways') }} </h5>
					<div class="row">
						<div class="col-md-4 col-4 mt-2">
							<img class="img-fluid" src="/img/mada.png" alt="mada">
						</div>
						<div class="col-md-4 col-4 mt-2">
							<img class="img-fluid" src="/img/sadad.png" alt="sadad">
						</div>
						<div class="col-md-4 col-4 mt-2">
							<img class="img-fluid" src="/img/visa.png" alt="visa">
						</div>
					</div>
				</div>
				<!-- payment mothed -->
			</div>
		</div>
	</div>


	@endsection



@section('images')
<div class="images">
	<img class="img-fluid top-right" src="{{asset('img/home-top-right.jpg')}}" alt="home-top-right">
	<img class="img-fluid top-left" src="{{asset('img/home-top-left.jpg')}}" alt="home-top-left">
	{{-- <img class="img-fluid bottom-left" src="{{asset('img/hoem-bottom-left.jpg')}}" alt="hoem-bottom-left"> --}}
</div>
@endsection

@section('js')
<script src="{{asset('/js/swiper.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/lity.min.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCt9ApcngmV7Zj_XR8h5hoznS1EaYuPLhI&libraries=places&callback=initMap" async defer></script>
<script>

@if($sliders->count() > 0)
var myIndex = 0;
carousel();

function carousel() {
  var i;
  var x = document.getElementsByClassName("mySlides");
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  myIndex++;
  if (myIndex > x.length) {myIndex = 1}    
  x[myIndex-1].style.display = "block";  
  setTimeout(carousel, 5000); // Change image every 2 seconds
}
    document.getElementById('playVid').onclick = function () {
        document.getElementById('video').play(),
            this.style.display = "none",
            document.getElementById("pauseVid").style.display = "block",
            document.getElementById("pauseVid").onclick = function () {
                document.getElementById('video').pause(),
                    this.style.display = "none",
                    document.getElementById('playVid').style.display = "block"

            }
    };
@endif

	@if(auth('clients')->check())
		currency = '{{auth('clients')->user()->currency}}'
	@else
		currency = 'none'
	@endif
	hotel_level_home = '{{trans('lang.hotel_level')}}'
function initMap() {
	
	var locations = []
	var cat = []
	offers = {!!json_encode($offers)!!}
	let title = '{{$of}}';
	if(offers != null){
		country = "{{request('country')}}"

		if(country !== ""){
			for(i in offers){

				data = offers[i]
				hotel_level = '';
				for(i=0; i < data.hotel_level; i++){
					hotel_level += '<li class="list-inline-item"><i class="far fa-star"></i></li>'
				}

				if (currency == 'dollar'){
					if(data.currency == 'sar'){
						price = data.price / 3.75 
						currenc = '{{trans("lang.dollar")}}'
					}else{
						price = data.price
						currenc = '{{trans("lang.dollar")}}'
					}
				}else if(currency == 'sar'){
					if(data.currency == 'sar'){
						price = data.price
						currenc = '{{trans("lang.sar")}}'
					}else{
						price = data.price * 3.75
						currenc = '{{trans("lang.sar")}}'
					}
				}else{
					price = data.price
					currenc = data.currency
				}

				url = '{{url("/offers")}}'+ '/' + data.id 

				if(data.category_id == 1){
					category = '{{trans('lang.entertainment')}}'
				}else if(data.category_id == 2){
					category = '{{trans('lang.educational')}}'
				}else if(data.category_id == 3){
					category = '{{trans('lang.sport')}}' 
				}else{
					category = '{{trans('lang.medical')}}'
				}
			
				let  html =
				' <div class="goole-maps-style" >' +
						'<div class="row">' +
							'<div class="col-md-8">' +
								'<a href="'+url+'">'+
								'<h4 style="font-size: 20px;">'+data.country.name +' - '+ data.city.name +'</h4>'+
								'<h5 style="font-size: 15px;">'+data.provider.first_name +'  '+ data.provider.last_name+'</h5>'+
								'<ul class="list-inline mt-2">'+
										data.rate + ' / 10 '+
								'</ul>'+
								'<h4 style="font-size: 15px;">'+data.days +' {{trans("lang.days")}} - '+ (Number(data.days) - 1) +' {{trans("lang.night")}}</h4><br>'+
								'<h6 style="font-size: 15px;">'+category+'</h6>'+

								'<h6 style="font-size: 15px;">'+hotel_level_home +'</h6>'+
								'<ul class="list-unstyled">'+hotel_level+'</ul>'+
								'<h4 style="font-size: 15px;">{{trans("lang.num_of_rates")}} : '+data.rates_count+'</h4><br>'+
								'</a>'+
							'</div>'+
							'<div class="col-md-4 mt-2">'+
								'<h6 class="text-center mt-3" style="font-size: 20px;">'+Math.round(price)+ ' ' + currenc +'</h6>'+
							'</div>'+
						'</div>'+
					'</div>'
				var tem = [html, data.lat, data.lng]
				locations.push(tem)
				cat.push(data.category_id)
			}
		}else{
			for(i in offers){
				data = offers[i]
				hotel_level = '';
				for(i=0; i < data.hotel_level; i++){
					hotel_level += '<li class="list-inline-item"><i class="far fa-star"></i></li>'
				}
				if (currency == 'dollar'){
					if(data.currency == 'sar'){
						price = data.price / 3.75 
						currenc = '{{trans("lang.dollar")}}'
					}else{
						price = data.price
						currenc = '{{trans("lang.dollar")}}'
					}
				}else if(currency == 'sar'){
					if(data.currency == 'sar'){
						price = data.price
						currenc = '{{trans("lang.sar")}}'
					}else{
						price = data.price * 3.75
						currenc = '{{trans("lang.sar")}}'
					}
				}else{
					price = data.price
					currenc = data.currency
				}

				if(data.category_id == 1){
					category = '{{trans('lang.entertainment')}}'
				}else if(data.category_id == 2){
					category = '{{trans('lang.educational')}}'
				}else if(data.category_id == 3){
					category = '{{trans('lang.sport')}}' 
				}else{
					category = '{{trans('lang.medical')}}'
				}
				url = '{{url("/offers")}}'+ '/' + data.id
				
				let  html =
				' <div class="goole-maps-style" >' +
						'<div class="row">' +
							'<div class="col-md-8">' +
								'<a href="'+url+'">'+
								'<h4 style="font-size: 20px;">'+data.country.name +' - '+ data.city.name +'</h4>'+
								'<h5 style="font-size: 15px;">'+data.provider.first_name +'  '+ data.provider.last_name +'</h5>'+
								'<ul class="list-inline mt-2">'+
										data.rate + ' / 10 '+
								'</ul>'+
								'<h4 style="font-size: 15px;">'+data.days +' {{trans("lang.days")}} - '+ (Number(data.days) - 1) +' {{trans("lang.night")}}</h4><br>'+
								'<h6 style="font-size: 15px;">'+category+'</h6>'+
								'<h6 style="font-size: 15px;">'+hotel_level_home +'</h6>'+
								'<ul class="list-unstyled">'+hotel_level+'</ul>'+
								'<h4 style="font-size: 15px;">{{trans("lang.num_of_rates")}} : '+data.rates_count+'</h4><br>'+
								'</a>'+
							'</div>'+
							'<div class="col-md-4 mt-2">'+
								
								'<h6 class="text-center mt-3" style="font-size: 20px;">'+Math.round(price)+ ' ' + currenc +'</h6>'+
							'</div>'+
						'</div>'+
					'</div>'
				var tem = [html, data.lat, data.lng]
				locations.push(tem)
				cat.push(data.category_id)
			}
		}
		
	}
	

	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 2,
		center: new google.maps.LatLng(1, 1),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});

	var infowindow = new google.maps.InfoWindow({});

	var marker, i;

	for (i = 0; i < locations.length; i++) {
		if(cat[i] == 1){
				icon_image = '{{asset("marker/enter_map.png")}}';
		}else if(cat[i] == 2){
				icon_image = '{{asset("marker/edu_map.png")}}';
		}else if(cat[i] == 3){
				icon_image = '{{asset("marker/sportive_map.png")}}';
		}else{
				icon_image = '{{asset("marker/medical.png")}}';
		}
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(locations[i][1], locations[i][2]),
			map: map,
			icon: icon_image
		});

		google.maps.event.addListener(marker, 'click', (function (marker, i) {
			return function () {
				infowindow.setContent(locations[i][0]);
				infowindow.open(map, marker);
			}
		})(marker, i));
	}
}
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

<style type='text/css'>

.mewtwo-flights{
  	font-size: 80px!important;
  }


  .TPWL-widget .TPWL-front-content {
    max-width: 948px;
    margin: auto;
    padding: 0;
    p {
      line-height: 25px;
      font-size: 14px;
      margin: 10px 0;
    }
    h1 {
      font-size: 24px;
      line-height: 1.29;
      font-weight: 400;
      margin-bottom: 20px;
    }
    a {
      color: #4285f4;
    }
    h2 {
      font-size: 20px;
      line-height: 1.29;
      font-weight: 400;
      margin: 30px 0 15px;
    }
    table {
      border: 1px solid #808080;
      border-collapse: collapse;
      border-spacing: 0;
      font-size: 14px;
      th {
        background-color: #f5f5f5;
        text-align: left;
        border: 1px solid #eee;
        padding: 4px 12px;
        vertical-align: top;
        font-weight: bold;
      }
      td {
        border: 1px solid #eee;
        padding: 4px 12px;
        vertical-align: top;
      }
      tr {
        background-color: #fafafa;
      }
      tr:nth-of-type(even) {
        background-color: #fff;
      }
    }
  }

  .TPWL-widget .TPWL-front-content .TPWL-front-content_attention {
    padding: 10px 10px;
    background-color: #f6f9ff;
    border: 1px solid #f8f6e6;
    color: #444;
    border-radius: 4px ;
    margin-bottom: 20px ;
    font-size: 16px;
    line-height: 25px;
    font-weight: 500;
  }
  .TPWL-widget .TPWL-front-content .TPWL-front-content_text-list {
    background-image: none;
    margin: 10px 10px 10px 30px;
    list-style-type: disc;
    color: #757575;
    font-size: 16px;
    line-height: 24px;
  }
  .TPWL-widget .TPWL-front-content-weedle-title,
  .TPWL-widget .TPWL-front-content-subscription-title,
  .TPWL-widget .TPWL-front-content-special_offers-title {
    text-align: center;
    font-size: 32px;
    margin-bottom: 36px;
    margin-top: 60px;
    font-weight: 300;
  }
  .TPWL-widget .TPWL-front-content-weedle {
    text-align: center;
    margin-right: -21px;
  }
  .TPWL-widget .TPWL-front-content-weedle-container {
    display: inline-block;
    width: 300px;
    margin: 10px 21px 10px 0;
  }
  .TPWL-widget .weedle-header__city {
    text-align: center;
  }

  .TPWL-footer-content {
    margin: 15px auto 40px;
    text-align: center;
    font-size: 14px;
  }
  .TPWL-footer-content__descrition {
    margin-top: 5px;
    opacity: .6;
  }
  .TPWL-header-content-wrapper {
    width: 100%;
    margin: 0 auto;
    max-width: 1024px;
  }
  .TPWL-header-content {
    padding-top: 45px;
    padding-bottom: 10px;
  }

  .TPWL-header-content .TPWL-header-content__descrition {
    padding-right: 15px;
    float: right;
    line-height: 23px;
    font-weight: 300;
    font-size: 14px;
  }

  .TPWL-header-content .TPWL-header-content__label {
    display: none;
    font-size: 36px;
    margin: 0 auto;
    max-width: 1024px;
    padding: 95px 0px 0px 15px;
  }
  .TPWL-widget--front_page .TPWL-header-content .TPWL-header-content__label {
    display: block;
  }

  .TPWL-header-content .TPWL-header-content__logo {
    padding-left: 15px;
    display: inline;
    line-height: 24px;
  }
  .TPWL-header-content .TPWL-header-content-logo-link {
    text-decoration: none;
  }
  .TPWL-header-content .TPWL-header-content-logo__text {
    padding-left: 40px;
    color: #FFFFFF;
    font-size: 24px;
    font-weight: 900;
    text-transform: uppercase;
  }
  .TPWL-header-content .TPWL-header-content-logo__img {
    position: absolute;
    width: 30px;
    height: 30px;
    background: no-repeat url("data:image/svg+xml, %3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2228%22%20height%3D%2227%22%20viewBox%3D%220%200%2028%2027%22%3E%3Cpath%20fill%3D%22%23FFF%22%20fill-rule%3D%22evenodd%22%20d%3D%22M15.903%2014.94l6.308%2011.614c.197.36.686.432.977.14l.494-.493c.12-.12.022-.322-.146-.3l-2.56-10.58s.762-.347%201.447-.837c.468-.335.53-1.016.114-1.413l-.03-.03c-.357-.327-.905-.325-1.26.005-.223.21-.473.47-.656.736l-.716-2.956c-.083-.343.014-.706.26-.96l5.91-6.15s.64-.9.834-1.476c.192-.578.29-1.7.065-1.924-.224-.224-1.346-.128-1.923.065-.577.19-1.475.833-1.475.833l-6.15%205.91c-.256.245-.618.343-.962.26l-2.957-.717c.267-.182.528-.433.736-.656.33-.354.33-.903.004-1.26l-.03-.03c-.396-.415-1.078-.354-1.412.114-.49.684-.837%201.448-.837%201.448L1.355%203.72c.02-.167-.182-.266-.3-.147l-.496.495c-.292.29-.22.78.14.976l11.614%206.308c.23.125.277.434.096.622L9.527%2014.97c-1.32%201.375-2.6%203.185-3.008%203.78-.084.12-.23.18-.374.156l-4.73-.822c.02-.144-.155-.23-.258-.13l-.377.38c-.356.356-.272.956.17%201.2l3.43%201.907c.17.095.24.302.162.48-.1.234-.18.445-.24.622-.086.254.156.496.41.41.178-.06.39-.138.623-.24.178-.076.385-.008.48.162l1.905%203.432c.245.44.845.525%201.202.168l.377-.378c.102-.103.015-.277-.13-.256l-.822-4.73c-.025-.146.037-.29.157-.374.595-.41%202.404-1.687%203.78-3.01%201.19-1.146%202.402-2.31%202.995-2.88.19-.183.498-.135.623.095%22%2F%3E%3C%2Fsvg%3E");
  }

  .TPWL-widget--front_page .TPWL-template-header {
    padding-bottom: 145px;
  }
  .TPWL-widget--front_page .TPWL-template-search-results {
    display: none;
  }

  .TPWL-widget--front_page .TPWL-template-header.TPWL-template-header--sticky {
    padding: 0;
  }

  

  @media (max-width: 1024px) {
    .TPWL-widget--front_page .TPWL-template-header.TPWL-template-header--sticky {
      display: none;
    }
  }
  @media (max-width: 1001px) {
    .TPWL-widget .TPWL-front-content {
      padding: 0 30px;
    }
    .TPWL-widget .TPWL-template-header-content {
      max-width: 1024px;
    }
    .TPWL-header-content .TPWL-header-content__descrition {
      padding-right: 10px;
    }
    .TPWL-header-content .TPWL-header-content__label {
      padding: 75px 0 0 15px;
    }
    .TPWL-header-content .TPWL-header-content__logo {
      padding-left: 10px;
    }
    .TPWL-widget--front_page .TPWL-template-header {
      padding-bottom: 90px;
    }
  }
  @media (max-width: 790px) {
    .TPWL-widget .TPWL-front-content {
      padding: 0 20px;
    }
    .TPWL-widget--front_page .TPWL-template-header {
      padding-bottom: 25px;
    }
    .TPWL-header-content .TPWL-header-content__label {
      font-size: 24px;
      padding-bottom: 0px;
      padding-top: 91px;
    }
    .TPWL-widget .TPWL-front-content-weedle {
      margin: 0px;
    }
  }
  @media (max-width: 460px) {
    .TPWL-widget .TPWL-front-content {
      padding: 0 10px;
    }
    .TPWL-header-content {
      text-align: center;
      padding-bottom: 0px;
      padding-top: 20px;
    }
    .TPWL-header-content .TPWL-header-content__descrition {
      padding-right: 0px;
      float: none;
      display: block;
    }
    .TPWL-header-content .TPWL-header-content__label {
      padding: 0;
      display: none;
    }
    .TPWL-header-content .TPWL-header-content__logo {
      padding-left: 0px;
      display: block;
    }
    .TPWL-widget--front_page .TPWL-template-header {
      padding-bottom: 0px;
    }
    .TPWL-widget .TPWL-front-content-weedle {
      margin: 0px;
    }

    .TPWL-widget .TPWL-front-content-weedle-container {
      display: inline-block;
      width: 250px;
      margin: 10px 0 10px 0;
    }
  }
  @media (max-width: 340px) {
    .TPWL-widget .ducklett-widget .ducklett-widget-wrapper--brickwork .ducklett-widget-wrapper-item {
      margin: 0 0 20px 0!important;
    }
    .TPWL-widget .ducklett-widget .ducklett-slider-wrapper {
      overflow: visible !important;
    }
  }
</style>
