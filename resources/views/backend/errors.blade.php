
<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en" >
	<!-- begin::Head -->
	<head>
            <meta charset="utf-8" />
            <title>
                {{ trans('lang.app_name') }}
            </title>
            <meta name="description" content="Latest updates and statistic charts">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <!--begin::Web font -->
            <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
            <script>
                WebFont.load({
                  google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
                  active: function() {
                      sessionStorage.fonts = true;
                  }
                });
              </script>
            <!--end::Web font -->
            <!--begin::Base Styles -->
            <link href="{{asset('assets/css/vendors.bundle.css')}}" rel="stylesheet" type="text/css" />
			<link href="{{asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
			<!--end::Base Styles -->
			<link rel="shortcut icon" href="{{asset('assets/images/icon.png')}}" />
        </head>
	<!-- end::Head -->
    <!-- end::Body -->
	<body  class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<div class="m-grid__item m-grid__item--fluid m-grid  m-error-5" style="background-image: url({{asset('assets/images/bg5.jpg')}});">
				<div class="m-error_container">
					<span class="m-error_title">
						<h1>
							Oops!
						</h1>
					</span>
					<p class="m-error_subtitle">
						You Don't have permission to view this page
					</p>
					<p class="m-error_description">
						if you want to view this page please call admin
					</p>
				</div>
			</div>
		</div>
	</body>
	<!-- end::Body -->
</html>
