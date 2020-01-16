<!DOCTYPE html>
<html lang="en" >
	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>
			{{trans('lang.app_name')}} | @yield('title')
		</title>
		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="description" content="Latest updates and statistic charts">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<!--begin::Web font -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
			WebFont.load({
				google: {
					"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700", "Asap+Condensed:500"]
				},
				active: function() {
					sessionStorage.fonts = true;
				}
			});
		</script>

        @yield('css')

		@if(LaravelLocalization::getCurrentLocaleDirection() == 'ltr')
            <link href="{{asset('assets/css/vendors.bundle.css')}}" rel="stylesheet" type="text/css" />
            <link href="{{asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
        @else
            <link href="{{asset('assets/css/vendors.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
			<link href="{{asset('assets/css//style.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
			<style>
				@import url('https://fonts.googleapis.com/css?family=Cairo');
				*{
					font-family: 'Cairo', sans-serif !important;
				}
			</style>
		@endif
		<!--end::Base Styles -->
		<link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}" />
	</head>
	<!-- end::Head -->
    <!-- end::Body -->
	<body  style="background-image: url({{ asset('assets/images/bg-7.jpg') }})"  class="m-page--fluid m-page--loading-enabled m-page--loading m-header--fixed m-header--fixed-mobile m-footer--push m-aside--offcanvas-default"  >
		<!-- begin::Page loader -->
		<div class="m-page-loader m-page-loader--base">
			<div class="m-blockui">
				<span>
					{{ trans('lang.loading') }}
				</span>
				<span>
					<div class="m-loader m-loader--brand"></div>
				</span>
			</div>
		</div>
		<!-- end::Page Loader -->        
    	<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<!-- begin::Header -->
			<header id="m_header" class="m-grid__item m-header "  m-minimize="minimize" m-minimize-mobile="minimize" m-minimize-offset="10" m-minimize-mobile-offset="10" >
				<div class="m-header__top">
					<div class="m-container m-container--fluid m-container--full-height m-page__container">
						<div class="m-stack m-stack--ver m-stack--desktop">
							<!-- begin::Brand -->
							<div class="m-stack__item m-brand m-stack__item--left">
								<div class="m-stack m-stack--ver m-stack--general m-stack--inline">
									<div class="m-stack__item m-stack__item--middle m-brand__logo">
										<a href="#" class="m-brand__logo-wrapper">
											<img alt="" src="{{asset('assets/images/logo.png')}}" class="m-brand__logo-default"/>
											<img alt="" src="{{asset('assets/images/logo_inverse.png')}}" class="m-brand__logo-inverse"/>
										</a>
									</div>
									<div class="m-stack__item m-stack__item--middle m-brand__tools">
										<!-- begin::Responsive Header Menu Toggler-->
										<a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
											<span></span>
										</a>
										<!-- end::Responsive Header Menu Toggler-->
			<!-- begin::Topbar Toggler-->
										<a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
											<i class="flaticon-more"></i>
										</a>
										<!--end::Topbar Toggler-->
									</div>
								</div>
							</div>
							<!-- end::Brand -->		
						<?php $url = url()->current(); ?>
						@if(auth('providers')->check())
							@include('layouts.providers')
						@else
							@include('layouts.backend')
						@endif	
								
						</div>
					</div>
				</div>
			</header>
			<!-- end::Header -->		
		<!-- begin::Body -->
			<div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver-desktop m-grid--desktop m-page__container m-body">
				<div class="m-grid__item m-grid__item--fluid m-wrapper">
					@yield('content')
				</div>
			</div>
			<!-- end::Body -->
<!-- begin::Footer -->
			<footer class="m-grid__item m-footer ">
				<div class="m-container m-container--fluid m-container--full-height m-page__container">
					<div class="m-footer__wrapper">
						<div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
							<div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last">
								<span class="m-footer__copyright">
									2018 &copy; Recived To
									<a href="#" class="m-link">Mapyyn</a>
								</span>
							</div>
						</div>
					</div>
				</div>
			</footer>
			<!-- end::Footer -->
		</div>
		<!-- end:: Page -->
    	            	        
	    <!-- begin::Scroll Top -->
		<div id="m_scroll_top" class="m-scroll-top">
			<i class="la la-arrow-up"></i>
		</div>
    	<!--begin::Base Scripts -->
		<script src="{{ asset('assets/js/vendors.bundle.js')}}" type="text/javascript"></script>
		<script src="{{ asset('assets/js/scripts.bundle.js')}}" type="text/javascript"></script>
		<!--end::Base Scripts -->   
        <!--begin::Page Snippets -->
		<script>
            $(window).on('load', function() {
                $('body').removeClass('m-page--loading');         
            });
		</script>
		<!-- end::Page Loader -->

		@yield('js')
	</body>
	<!-- end::Body -->
</html>
