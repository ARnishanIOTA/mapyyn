
<ul class="navbar-nav mr-auto">
    
    <li class="nav-item {{url("/$lang") == $url ? 'active' : ''}}">
    <a class="nav-link" href="{{url('/')}}"> <i class="fas fa-home"></i> {{ trans('lang.main') }} <span class="sr-only">(current)</span></a>
    </li>
    <li class="nav-item {{strpos($url, '/all-offers') ? 'active' : ''}}">
        <a class="nav-link" href="{{route('all-offers')}}">{{ trans('lang.offers') }}</a>
    </li>

    <li class="nav-item {{strpos($url, '/contact-us') ? 'active' : ''}}">
        <a class="nav-link" href="{{route('contact_us')}}"> {{ trans('lang.contact_us') }} </a>
    </li>

    <li class="nav-item {{strpos($url, '/register') ? 'active' : ''}}">
        <a class="nav-link" href="{{route('client-register')}}"> {{ trans('lang.register') }} </a>
    </li>
    

    <li class="nav-item {{strpos($url, '/login') ? 'active' : ''}}">
        <a class="nav-link" href="{{route('login-client')}}"> {{trans('lang.login')}} </a>
    </li>

    {{--  <li class="nav-item">
        <a class="nav-link" href="{{url('providers/login')}}"> {{trans('lang.provider_login')}} </a>
    </li>  --}}
</ul>
