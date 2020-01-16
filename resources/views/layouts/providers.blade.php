<!-- begin::Topbar -->
<?php $image = auth('providers')->user()->logo == null ? 'default.png' : auth('providers')->user()->logo ?>
<div class="m-stack__item m-stack__item--right m-header-head" id="m_header_nav">
    <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
        <div class="m-stack__item m-topbar__nav-wrapper">
            <ul class="m-topbar__nav m-nav m-nav--inline">
                <li class="m-nav__item m-topbar__user-profile  m-dropdown m-dropdown--medium m-dropdown--arrow  m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light"
                    m-dropdown-toggle="click">
                    <a href="#" class="m-nav__link m-dropdown__toggle">
                        <span class="m-topbar__userpic">
                            <img src='{{asset("uploads/$image")}}' class="m--img-rounded m--marginless m--img-centered"
                                alt="" />
                        </span>
                        <span class="m-nav__link-icon m-topbar__usericon  m--hide">
                            <span class="m-nav__link-icon-wrapper">
                                <i class="flaticon-user-ok"></i>
                            </span>
                        </span>
                        <span class="m-topbar__username m--hide">
                            Nick
                        </span>
                    </a>
                    <div class="m-dropdown__wrapper">
                        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                        <div class="m-dropdown__inner">
                            <div class="m-dropdown__header m--align-center">
                                <div class="m-card-user m-card-user--skin-light">
                                    <div class="m-card-user__pic">
                                        <img src='{{asset("uploads/$image")}}' class="m--img-rounded m--marginless"
                                            alt="" />
                                    </div>
                                    <div class="m-card-user__details">
                                        <span class="m-card-user__name m--font-weight-500">
                                            {{auth('providers')->user()->first_name . ' ' . auth('providers')->user()->last_name}}
                                        </span>
                                        <a href="#" class="m-card-user__email m--font-weight-300 m-link">
                                            {{auth('providers')->user()->email}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="m-dropdown__body">
                                <div class="m-dropdown__content">
                                    <ul class="m-nav m-nav--skin-light">
                                        
                                        <li class="m-nav__item">
                                            <a href="{{route('update-provider')}}" class="m-nav__link">
                                                <i class="m-nav__link-icon flaticon-profile-1"></i>
                                                <span class="m-nav__link-title">
                                                    <span class="m-nav__link-wrap">
                                                        <span class="m-nav__link-text">
                                                            {{ trans('lang.my-profile') }}
                                                        </span>
                                                    </span>
                                                </span>
                                            </a>
                                        </li>

                                        <?php $lang = LaravelLocalization::getCurrentLocale() == 'ar' ? 'en' : 'ar' ?>
																<li class="m-nav__item">
																<a rel="alternate" hreflang="{{$lang}}" href="{{ LaravelLocalization::getLocalizedURL($lang, null, [], true) }}" class="m-nav__link">
																		<i class="m-nav__link-icon flaticon-profile-1"></i>
																		<span class="m-nav__link-title">
																			<span class="m-nav__link-wrap">
																			<span class="m-nav__link-text">{{$lang == 'ar' ? 'AR' : 'EN'}}</span>
																			</span>
																		</span>
																	</a>
																</li>
                                        
                                        <li class="m-nav__item">

                                            <a href="{{ route('logout-provider') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                                                class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
                                                {{ trans('lang.logout') }}
                                            </a>
                                            <form id="logout-form" action="{{ route('logout-provider') }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- end::Topbar -->
</div>
</div>
</div>
<div class="m-header__bottom">
    <div class="m-container m-container--fluid m-container--full-height m-page__container">
        <div class="m-stack m-stack--ver m-stack--desktop">
            <!-- begin::Horizontal Menu -->
            <div class="m-stack__item m-stack__item--fluid m-header-menu-wrapper">
                <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-light " id="m_aside_header_menu_mobile_close_btn">
                    <i class="la la-close"></i>
                </button>
                <div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-dark m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-light m-aside-header-menu-mobile--submenu-skin-light ">
                    

                    <ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
                            <li class="m-menu__item m-menu__item--active {{strpos($url, 'statistics') ? 'm-menu__item--active-tab' : ''}}  m-menu__item--submenu m-menu__item--tabs"
                            m-menu-submenu-toggle="tab" aria-haspopup="true">
                            <a href="{{url('/providers/statistics')}}" class="m-menu__link">
                                <span class="m-menu__link-text">
                                    {{ trans('lang.dashboard') }}
                                </span>
                                <i class="m-menu__hor-arrow la la-angle-down"></i>
                                <i class="m-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
                                <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                <ul class="m-menu__subnav">
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true">
                                        <a href="{{url('/providers/statistics')}}" class="m-menu__link ">
                                            <i class="m-menu__link-icon flaticon-support"></i>
                                            <span class="m-menu__link-text">
                                                {{ trans('lang.statistics') }}
                                            </span>
                                        </a>
                                    </li>
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true">
                                        <a href="{{route('update-provider')}}" class="m-menu__link ">
                                            <i class="m-menu__link-icon flaticon-support"></i>
                                            <span class="m-menu__link-text">
                                                {{ trans('lang.my-profile') }}
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>   

                        <li class="m-menu__item {{strpos($url, 'providers/offers') ? 'm-menu__item--active-tab' : ''}} m-menu__item--submenu m-menu__item--tabs"
                            m-menu-submenu-toggle="tab" aria-haspopup="true">
                            <a href="{{route('provider-offers')}}" class="m-menu__link">
                                <span class="m-menu__link-text">
                                    {{trans('lang.offers')}}
                                </span>
                                <i class="m-menu__hor-arrow la la-angle-down"></i>
                                <i class="m-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
                                <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                <ul class="m-menu__subnav">
                                    <li class="m-menu__item {{strpos($url, 'offers') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                        <a href="{{route('provider-offers')}}" class="m-menu__link ">
                                            <i class="m-menu__link-icon flaticon-users"></i>
                                            <span class="m-menu__link-text">
                                                {{trans('lang.offers')}}
                                            </span>
                                        </a>
                                    </li>

                                    <li class="m-menu__item  m-menu__item--actions" aria-haspopup="true">
                                        <div class="m-menu__link m-menu__link--toggle-skip">
                                            <a href="{{route('create-offer')}}" class="btn btn-focus m-btn m-btn--icon m-btn--pill">
                                                <span>
                                                    <i class="flaticon-plus"></i>
                                                    <span>
                                                        {{ trans('lang.create') }}
                                                    </span>
                                                </span>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>


                        <li class="m-menu__item {{strpos($url, 'providers/requests_offers') ? 'm-menu__item--active-tab' : ''}} m-menu__item--submenu m-menu__item--tabs"
                            m-menu-submenu-toggle="tab" aria-haspopup="true">
                            <a href="{{route('provider-requests-offers')}}" class="m-menu__link">
                                <span class="m-menu__link-text">
                                    {{trans('lang.requests_offers')}}
                                </span>
                                <i class="m-menu__hor-arrow la la-angle-down"></i>
                                <i class="m-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
                                <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                <ul class="m-menu__subnav">
                                    <li class="m-menu__item {{strpos($url, 'requests_offers') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                        <a href="{{route('provider-requests-offers')}}" class="m-menu__link ">
                                            <i class="m-menu__link-icon flaticon-users"></i>
                                            <span class="m-menu__link-text">
                                                {{trans('lang.requests_offers')}}
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="m-menu__item {{strpos($url, 'providers/chat') ? 'm-menu__item--active-tab' : ''}} m-menu__item--submenu m-menu__item--tabs"
                            m-menu-submenu-toggle="tab" aria-haspopup="true">
                            <a href="{{route('providers_chat')}}" class="m-menu__link">
                                <span class="m-menu__link-text">
                                    {{trans('lang.chat')}}
                                </span>
                                <i class="m-menu__hor-arrow la la-angle-down"></i>
                                <i class="m-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
                                <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                <ul class="m-menu__subnav">
                                    <li class="m-menu__item" m-menu-link-redirect="1" aria-haspopup="true">
                                        <a href="{{route('providers_chat')}}" class="m-menu__link ">
                                            <i class="m-menu__link-icon flaticon-users"></i>
                                            <span class="m-menu__link-text">
                                                {{trans('lang.admin_chat')}}
                                            </span>
                                        </a>
                                    </li>

                                    <li class="m-menu__item" m-menu-link-redirect="1" aria-haspopup="true">
                                        <a href="{{route('providers_chat_clients')}}" class="m-menu__link ">
                                            <i class="m-menu__link-icon flaticon-users"></i>
                                            <span class="m-menu__link-text">
                                                {{trans('lang.client_chat')}}
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                           
                        </li>

                        <li class="m-menu__item {{strpos($url, 'payments') ? 'm-menu__item--active-tab' : ''}} m-menu__item--submenu m-menu__item--tabs"
                            m-menu-submenu-toggle="tab" aria-haspopup="true">
                            <a href="{{route('payments')}}" class="m-menu__link">
                                <span class="m-menu__link-text">
                                    {{trans('lang.payments')}}
                                </span>
                                <i class="m-menu__hor-arrow la la-angle-down"></i>
                                <i class="m-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
                                <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                <ul class="m-menu__subnav">
                                    <li class="m-menu__item {{strpos($url, 'payments') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                        <a href="{{route('payments')}}" class="m-menu__link ">
                                            <i class="m-menu__link-icon flaticon-users"></i>
                                            <span class="m-menu__link-text">
                                                {{trans('lang.payments')}}
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>


                        <li class="m-menu__item {{strpos($url, 'notifications') ? 'm-menu__item--active-tab' : ''}} m-menu__item--submenu m-menu__item--tabs"
                            m-menu-submenu-toggle="tab" aria-haspopup="true">
                            <a href="{{route('notifications')}}" class="m-menu__link">
                                <span class="m-menu__link-text">
                                    {{trans('lang.notifications')}}
                                    <span class="m-badge m-badge--success"> {{$notificationsCount}} </span>
                                </span>
                                <i class="m-menu__hor-arrow la la-angle-down"></i>
                                <i class="m-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
                                <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                <ul class="m-menu__subnav">
                                    <li class="m-menu__item {{strpos($url, 'notifications') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                        <a href="{{route('notifications')}}" class="m-menu__link ">
                                            <i class="m-menu__link-icon flaticon-users"></i>
                                            <span class="m-menu__link-text">
                                                {{trans('lang.notifications')}}
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        

                        {{-- <li class="m-menu__item {{strpos($url, 'clients_tickets') ? 'm-menu__item--active-tab' : ''}} m-menu__item--submenu m-menu__item--tabs"
                            m-menu-submenu-toggle="tab" aria-haspopup="true">
                            <a href="{{route('clients_tickets')}}" class="m-menu__link">
                                <span class="m-menu__link-text">
                                    {{trans('lang.clients_tickets')}}
                                </span>
                                <i class="m-menu__hor-arrow la la-angle-down"></i>
                                <i class="m-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
                                <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                <ul class="m-menu__subnav">
                                    <li class="m-menu__item {{strpos($url, 'clients_tickets') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                        <a href="{{route('clients_tickets')}}" class="m-menu__link ">
                                            <i class="m-menu__link-icon flaticon-users"></i>
                                            <span class="m-menu__link-text">
                                                {{trans('lang.clients_tickets')}}
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>


                        <li class="m-menu__item {{strpos($url, 'admin_tickets') ? 'm-menu__item--active-tab' : ''}} m-menu__item--submenu m-menu__item--tabs"
                            m-menu-submenu-toggle="tab" aria-haspopup="true">
                            <a href="{{route('admin_tickets')}}" class="m-menu__link">
                                <span class="m-menu__link-text">
                                    {{trans('lang.admin_tickets')}}
                                </span>
                                <i class="m-menu__hor-arrow la la-angle-down"></i>
                                <i class="m-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
                                <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                <ul class="m-menu__subnav">
                                    <li class="m-menu__item {{strpos($url, 'admin_tickets') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                        <a href="{{route('admin_tickets')}}" class="m-menu__link ">
                                            <i class="m-menu__link-icon flaticon-users"></i>
                                            <span class="m-menu__link-text">
                                                {{trans('lang.admin_tickets')}}
                                            </span>
                                        </a>
                                    </li>

                                    <li class="m-menu__item  m-menu__item--actions" aria-haspopup="true">
                                        <div class="m-menu__link m-menu__link--toggle-skip">
                                            <a href="{{route('create-ticket')}}" class="btn btn-focus m-btn m-btn--icon m-btn--pill">
                                                <span>
                                                    <i class="flaticon-plus"></i>
                                                    <span>
                                                        {{ trans('lang.create') }}
                                                    </span>
                                                </span>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li> --}}
                    </ul>
                </div>
            </div>
            <!-- end::Horizontal Menu -->
