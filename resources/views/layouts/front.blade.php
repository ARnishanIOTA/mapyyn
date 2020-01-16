<!DOCTYPE html>
<html lang="ar" dir="rtl">
	<?php
	if(LaravelLocalization::getCurrentLocale() == 'ar'){
		$about = 'about_ar';
		$dir   = 'lang_ar';
	}else{
		$about = 'about_en';
		$dir   = 'lang_en';
	}
?>
<head>
	<!-- Google -->
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="mappyn.com">
	<meta name="copyright" content="© All rights reserved, 2018@mappyn.com">
	<meta name="application-name" content="Mappyn">
	<!-- end Google-->

	<!-- Facebook-->
	<meta property="og:url" content="https://mappyn.com/">
	<meta property="og:type" content="website">
	<meta property="og:title" content="">
	<meta property="og:description" content="">
	<meta property="og:image" content="">
	<meta property="og:image:alt" content="mappyn">
	<meta property="fb:app_id" content="">
	<!-- end Facebook-->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- Twitter -->
	<meta name="twitter:card" content="">
	<meta name="twitter:title" content="">
	<meta name="twitter:description" content="">
	<meta name="twitter:image" content="">
	<!-- end Twitter-->

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
	<meta name="generator" content="2018.0.0.379" />

	<!-- Bootstrap CSS -->

	<link rel="stylesheet" type="text/css" href='{{asset("$dir/css/bootstrap.min.css")}}'>
	<!-- fontawesome CSS -->
	<link rel="stylesheet" type="text/css" href='{{asset("$dir/css/fontawesome-all.min.css")}}'>
	<!-- Style Main CSS -->
	
	<link rel="stylesheet" href="{{asset('assets/sweetalert.css')}}">
	<!-- favicon -->
	<link rel="icon" type="image/png" href="{{asset('img/logo-nav.png')}}">
	<title> {{ trans('lang.app_name') }} | @yield('title')</title>

	@yield('rate')
	<link rel="stylesheet" type="text/css" href='{{asset("$dir/css/style.css")}}'>
	@yield('css')

	@if(LaravelLocalization::getCurrentLocale() == 'en')
		<link rel="stylesheet" type="text/css" href='{{asset("$dir/css/style_en.css")}}'>
	@endif
	<style>
		#flash {
			position: fixed;
			right: 25px;
			bottom: 50px;
			display: block;
		}
	</style>
</head>

<body>

		<?php
			$url = url()->current(); 
    		$lang = LaravelLocalization::getCurrentLocale();
			if(LaravelLocalization::getCurrentLocale() == 'ar'){
				$about = 'about_ar';
			}else{
				$about = 'about_en';
			}
		?>
	<div class="line-top"></div>
	<!-- Start Navbar -->
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<div class="container">
		<a class="navbar-brand" href="{{url('/')}}"><img class="img-fluid" src="{{asset("uploads/$setting->logo")}}" alt="Logo"></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
			 aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				@if (auth('clients')->check())
                    @include('layouts.login')
                @else
                    @include('layouts.guest')
                @endif
				<ul class="navbar-nav ml-auto">
                    <?php $lang = LaravelLocalization::getCurrentLocale() == 'ar' ? 'en' : 'ar' ?>
                    <li class="nav-item">
                        <a rel="alternate" hreflang="{{$lang}}" href="{{ LaravelLocalization::getLocalizedURL($lang, null, [], true) }}" class="nav-link text-center">
                            <img class="img-fluid" src="{{$lang == 'ar' ? asset('img/saudi-arabia.png') :  asset('img/usa.png')}}" alt="saudi-arabia">
                            <br/>
                            {{$lang == 'ar' ? 'AR' : 'EN'}}
                        </a>
					</li>
					
					@if (auth('clients')->check())
						<li class="nav-item">
							<a  href="{{ url('change/currency') }}" class="nav-link text-center">
								<img class="img-fluid" src="{{auth('clients')->user()->currency == 'sar' ? asset('img/saudi-arabia.png') :  asset('img/usa.png')}} " alt="saudi-arabia">
								<br/>
								{{auth('clients')->user()->currency == 'sar' ? 'SAR' : 'USD'}}
							</a>
					@endif
					
				</ul>
			</div> 
		</div>
	</nav>
    <!-- End Navbar -->

    @yield('content')

    <footer class="footer">
		<div class="container">
			<div class="row">
				<ul class="list-inline mx-auto site-map">
					<li class="list-inline-item"><a href="{{url('faq')}}" class="{{strpos($url, '/faq') ? 'active' : ''}}">{{trans('lang.faq')}}</a> </li>
					<li class="list-inline-item"><a href="{{route('contact_us')}}" class="{{strpos($url, '/contact-us') ? 'active' : ''}}"> {{ trans('lang.contact_us') }} </a> </li>
					<li class="list-inline-item"><a href="{{url('pages/terms')}}" class="{{strpos($url, '/pages/terms') ? 'active' : ''}}"> {{ trans('lang.terms_conditions') }} </a> </li>
					<li class="list-inline-item"><a href="{{url('pages/about')}}" class="{{strpos($url, '/pages/about') ? 'active' : ''}}"> {{ trans('lang.about_us') }} </a> </li>
					@if (!auth('providers')->check())
						<li class="list-inline-item"><a href="{{url('/providers/registration')}}" class="{{strpos($url, '/providers/registration') ? 'active' : ''}}"> {{ trans('lang.providerRegistration') }} </a> </li>
						<li class="list-inline-item"><a href="{{url('providers/login')}}"> {{ trans('lang.partner_login') }} </a> </li>
					@endif

				</ul>
				<div class="content-footer mt-5 col-lg-12 content-footer mt-5">
					<div class="row">
						<!-- about	 -->
						<div class="col-md-6 border-right ">
							<div class="about-footer">
								<h6 class="mb-4"> {{trans('lang.about')}} </h6>
								<div class="row">
									<div class="col-md-3">
										<img class="img-fluid d-block" src="{{asset("uploads/$setting->logo")}}" alt="LOGO">
									</div>
									<div class="col-md-8">
										<p>{{$setting->$about}}</p>
									</div>
								</div>
							</div>
						</div>
						<!-- subscrip -->
						<div class="col-md-5 offset-md-1  ">
							<h6 class="mb-3">{{trans('lang.subscribes')}}</h6>
								<form action="{{route('subscribe-front')}}" method="POST" id="modelFormSubscribe">
									@csrf
									<button type="submit" class="btn btn-info" id="buttonSubscribe"> {{trans('lang.subscribe')}} </button>
									<button type="reset" id="resetSubscribe" style="display:none"></button>
									<input type="email" required name="email" class="form-control" placeholder="{{trans('lang.email')}}" aria-label="Username"
										aria-describedby="basic-addon1">
								</form>
							<ul class="list-inline mt-4">
								<li title="facebook" class="list-inline-item"><a href="{{$setting->fb}}"> <i class="fab fa-facebook-f fa-2x"></i> </a></li>
								<li title="twitter" class="list-inline-item"><a href="{{$setting->tw}}"> <i class="fab fa-twitter fa-2x"></i> </a></li>
								<li title="instagram" class="list-inline-item"><a href="{{$setting->instagram}}"> <i class="fab fa-instagram fa-2x"></i> </a></li>
								
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</footer>

	<div id="flash"></div>

	<div class="copyright">
		<div class="container">
			<h6 class="text-center">{{ trans('lang.copyright') }}</h6>
		</div>
	</div>

	@yield('images')
	{{--  <script src="js/jquery-3.2.1.slim.min.js"></script>  --}}
	<script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
	{{-- <script src="{{asset('js/popper.min.js')}}"></script> --}}
	<script src="{{asset('js/bootstrap.min.js')}}"></script>
		
		<script>successMessage = '{{trans("lang.successMessage")}}'</script>
	@yield('js')
	<script>
		// successMessage = '{{trans("lang.successMessage")}}'
		invalidMessage = '{{trans("lang.invalidMessage")}}'
		time = '{{trans("lang.time")}}'
		cancelButton = '{{trans("lang.cancelButton")}}'
		okButton = '{{trans("lang.okButton")}}'

       $("#forms").submit(function(){
           $(this).find('button')
			.append(' <i class="fa fa-spinner"></i> ')
			.attr('disabled','disabled');
	   });
	   
	   $(".multiForm").submit(function(){
           $(this).find('button')
			.append(' <i class="fa fa-spinner"></i> ')
			.attr('disabled','disabled');
	   });
	   $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
	
 
});

</script>
	<script src="{{asset('js/model.js')}}"></script>
	<script src="{{asset('js/main.js')}}"></script>
	<script src="{{asset('js/sweetalert.min.js')}}"></script>
</body>

</html>
    

