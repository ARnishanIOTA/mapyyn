<ul class="navbar-nav mr-auto">
    <li class="nav-item {{url('/') == $url ? 'active' : ''}}">
    <a class="nav-link" href="{{url('/')}}"> <i class="fas fa-home"></i>{{ trans('lang.main') }} <span class="sr-only">(current)</span></a>
    </li>
    <li class="nav-item {{strpos($url, '/request-offer') ? 'active' : ''}}">
        <a class="nav-link" href="{{route('client-request-offer')}}">{{ trans('lang.plan_your_trip') }}</a>
    </li>

    <li class="nav-item {{strpos($url, '/contact-us') ? 'active' : ''}}">
        <a class="nav-link" href="{{url('/contact-us')}}"> {{ trans('lang.contact_us') }} </a>
    </li>

    <li class="nav-item {{strpos($url, '/my-offers') ? 'active' : ''}}">
        <a class="nav-link" href="{{url('/my-offers')}}">{{ trans('lang.my_offer') }}</a>
    </li>

    <li class="nav-item {{strpos($url, '/messages') ? 'active' : ''}}">
        <a class="nav-link" href="{{url('/messages')}}">{{ trans('lang.my_messages') }}</a>
    </li>

    <li class="nav-item {{strpos($url, '/notifications') ? 'active' : ''}}">
        <a class="nav-link" href="{{url('/notifications')}}">{{ trans('lang.notifications') }}  {{$notificationsCount}}</a>
    </li>

    <li class="nav-item {{strpos($url, '/profile') ? 'active' : ''}}">
        <a class="nav-link" href="{{url('profile')}}">{{ trans('lang.my_account') }}</a>
    </li>
    
    <li class="nav-item ">
        <a class="nav-link" style="cursor:pointer" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
          {{ trans('lang.logout') }}
        </a>
        <form id="logout-form" action="{{ route('logout-client') }}" method="POST"
            style="display: none;">
            @csrf
        </form>
    </li>
</ul>