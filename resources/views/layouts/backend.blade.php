<!-- begin::Topbar -->
<?php $image = auth()->user()->image == null ? 'default.png' : auth()->user()->image ?>
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
                                            {{auth()->user()->name}}
                                        </span>
                                        <a href="#" class="m-card-user__email m--font-weight-300 m-link">
                                            {{auth()->user()->email}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="m-dropdown__body">
                                <div class="m-dropdown__content">
                                    <ul class="m-nav m-nav--skin-light">
                                        <li class="m-nav__item">
                                        <a href="{{route('update-admin', ['id' => auth()->id()])}}" class="m-nav__link">
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

                                            <a href="{{ route('logout-backend') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                                                class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
                                                {{ trans('lang.logout') }}
                                            </a>
                                            <form id="logout-form" action="{{ route('logout-backend') }}" method="POST"
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
                        <li class="m-menu__item m-menu__item--active {{strpos($url, 'dashboard') || strpos($url, 'subscribes') || strpos($url, 'payments') || strpos($url, 'faq') || strpos($url, 'contact_us') || strpos($url, 'admin-offers')? 'm-menu__item--active-tab' : ''}}  m-menu__item--submenu m-menu__item--tabs"
                            m-menu-submenu-toggle="tab" aria-haspopup="true">
                            <a href="{{url('/backend/dashboard/statistics')}}" class="m-menu__link">
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
                                        <a href="{{url('/backend/dashboard/statistics')}}" class="m-menu__link ">
                                            <i class="m-menu__link-icon flaticon-support"></i>
                                            <span class="m-menu__link-text">
                                                {{ trans('lang.statistics') }}
                                            </span>
                                        </a>
                                    </li>

                                    <li class="m-menu__item {{strpos($url, 'faq') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                            <a href="{{route('faq')}}" class="m-menu__link ">
                                                <i class="m-menu__link-icon flaticon-users"></i>
                                                <span class="m-menu__link-text">
                                                    {{trans('lang.faq')}}
                                                </span>
                                            </a>
                                        </li>

                                        <li class="m-menu__item {{strpos($url, 'contact_us') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                                <a href="{{route('contactus')}}" class="m-menu__link ">
                                                    <i class="m-menu__link-icon flaticon-users"></i>
                                                    <span class="m-menu__link-text">
                                                        {{trans('lang.contact_us')}}
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="m-menu__item {{strpos($url, 'subscribes') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                                    <a href="{{route('subscribes')}}" class="m-menu__link ">
                                                        <i class="m-menu__link-icon flaticon-users"></i>
                                                        <span class="m-menu__link-text">
                                                            {{trans('lang.subscribe')}}
                                                        </span>
                                                    </a>
                                                </li>

                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true">
                                        <a href="{{url('/backend/dashboard/settings')}}" class="m-menu__link ">
                                            <i class="m-menu__link-icon flaticon-support"></i>
                                            <span class="m-menu__link-text">
                                                {{ trans('lang.settings') }}
                                            </span>
                                        </a>
                                    </li>


                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true">
                                        <a href="{{url('/backend/admin-offers')}}" class="m-menu__link ">
                                            <i class="m-menu__link-icon flaticon-support"></i>
                                            <span class="m-menu__link-text">
                                                {{ trans('lang.admin-offers') }}
                                            </span>
                                        </a>
                                    </li>

                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true">
                                        <a href="{{route('admin.payments.index')}}" class="m-menu__link ">
                                            <i class="m-menu__link-icon flaticon-support"></i>
                                            <span class="m-menu__link-text">
                                                {{ trans('lang.payments') }}
                                            </span>
                                        </a>
                                    </li>

                                    <li class="m-menu__item  m-menu__item--actions" aria-haspopup="true">
                                            <div class="m-menu__link m-menu__link--toggle-skip">
                                                <a href="{{route('create-faq')}}" class="btn btn-focus m-btn m-btn--icon m-btn--pill">
                                                    <span>
                                                        <i class="flaticon-plus"></i>
                                                        <span>
                                                            {{ trans('lang.faq') }}
                                                        </span>
                                                    </span>
                                                </a>&nbsp;
                                                <a href="{{route('create-admin-offer')}}" class="btn btn-focus m-btn m-btn--icon m-btn--pill">
                                                    <span>
                                                        <i class="flaticon-plus"></i>
                                                        <span>
                                                            {{ trans('lang.admin-offers') }}
                                                        </span>
                                                    </span>
                                                </a>
                                            </div>
                                        </li>
                                </ul>
                            </div>
                        </li>   

                        @if(in_array('clients', $roles) || auth()->user()->permission->is_superadmin == 1)
                            <li class="m-menu__item {{strpos($url, 'clients') ? 'm-menu__item--active-tab' : ''}} m-menu__item--submenu m-menu__item--tabs"
                                m-menu-submenu-toggle="tab" aria-haspopup="true">
                                <a href="{{route('clients')}}" class="m-menu__link">
                                    <span class="m-menu__link-text">
                                        {{trans('lang.clients')}}
                                    </span>
                                    <i class="m-menu__hor-arrow la la-angle-down"></i>
                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
                                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                    <ul class="m-menu__subnav">
                                        <li class="m-menu__item {{strpos($url, 'clients') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                            <a href="{{route('clients')}}" class="m-menu__link ">
                                                <i class="m-menu__link-icon flaticon-users"></i>
                                                <span class="m-menu__link-text">
                                                    {{trans('lang.clients')}}
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif


                        @if(in_array('providers', $roles) || auth()->user()->permission->is_superadmin == 1)
                            <li class="m-menu__item {{strpos($url, 'providers') ? 'm-menu__item--active-tab' : ''}} m-menu__item--submenu m-menu__item--tabs"
                                m-menu-submenu-toggle="tab" aria-haspopup="true">
                                <a href="{{route('providers')}}" class="m-menu__link">
                                    <span class="m-menu__link-text">
                                        {{trans('lang.providers')}}
                                    </span>
                                    <i class="m-menu__hor-arrow la la-angle-down"></i>
                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
                                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                    <ul class="m-menu__subnav">
                                        <li class="m-menu__item {{strpos($url, 'providers') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                            <a href="{{route('providers')}}" class="m-menu__link ">
                                                <i class="m-menu__link-icon flaticon-users"></i>
                                                <span class="m-menu__link-text">
                                                    {{trans('lang.providers')}}
                                                </span>
                                            </a>
                                        </li>


                                        <li class="m-menu__item {{strpos($url, 'providers') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                                <a href="{{route('providers_register_request')}}" class="m-menu__link ">
                                                    <i class="m-menu__link-icon flaticon-users"></i>
                                                    <span class="m-menu__link-text">
                                                        {{trans('lang.providerRegisterRequest')}}
                                                    </span>
                                                </a>
                                            </li>

                                        <li class="m-menu__item  m-menu__item--actions" aria-haspopup="true">
                                            <div class="m-menu__link m-menu__link--toggle-skip">
                                                <a href="{{route('create-provider')}}" class="btn btn-focus m-btn m-btn--icon m-btn--pill">
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
                        @endif


                        @if(in_array('cities', $roles) || auth()->user()->permission->is_superadmin == 1)
                            <li class="m-menu__item {{strpos($url, 'cities') ? 'm-menu__item--active-tab' : ''}} m-menu__item--submenu m-menu__item--tabs"
                                m-menu-submenu-toggle="tab" aria-haspopup="true">
                                <a href="{{route('cities')}}" class="m-menu__link">
                                    <span class="m-menu__link-text">
                                        {{trans('lang.cities')}}
                                    </span>
                                    <i class="m-menu__hor-arrow la la-angle-down"></i>
                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
                                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                    <ul class="m-menu__subnav">
                                        <li class="m-menu__item {{strpos($url, 'cities') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                            <a href="{{route('cities')}}" class="m-menu__link ">
                                                <i class="m-menu__link-icon flaticon-users"></i>
                                                <span class="m-menu__link-text">
                                                    {{trans('lang.cities')}}
                                                </span>
                                            </a>
                                        </li>

                                        <li class="m-menu__item  m-menu__item--actions" aria-haspopup="true">
                                            <div class="m-menu__link m-menu__link--toggle-skip">
                                                <a href="{{route('create-city')}}" class="btn btn-focus m-btn m-btn--icon m-btn--pill">
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
                        @endif

                        @if(in_array('chats', $roles) || auth()->user()->permission->is_superadmin == 1)
                            <li class="m-menu__item {{strpos($url, 'chats') ? 'm-menu__item--active-tab' : ''}} m-menu__item--submenu m-menu__item--tabs"
                                m-menu-submenu-toggle="tab" aria-haspopup="true">
                                <a href="{{url('backend/chats')}}" class="m-menu__link">
                                    <span class="m-menu__link-text">
                                        {{trans('lang.chats')}}
                                    </span>
                                    <i class="m-menu__hor-arrow la la-angle-down"></i>
                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
                                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                    <ul class="m-menu__subnav">
                                        <li class="m-menu__item {{strpos($url, 'chats') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                            <a href="{{url('backend/chats')}}" class="m-menu__link ">
                                                <i class="m-menu__link-icon flaticon-users"></i>
                                                <span class="m-menu__link-text">
                                                    {{trans('lang.chats')}}
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                        @if(in_array('request_offers', $roles) || auth()->user()->permission->is_superadmin == 1)
                            <li class="m-menu__item {{strpos($url, 'backend/request_offers') ? 'm-menu__item--active-tab' : ''}} m-menu__item--submenu m-menu__item--tabs"
                                m-menu-submenu-toggle="tab" aria-haspopup="true">
                                <a href="{{route('requests-offer')}}" class="m-menu__link">
                                    <span class="m-menu__link-text">
                                        {{trans('lang.request_offers')}}
                                    </span>
                                    <i class="m-menu__hor-arrow la la-angle-down"></i>
                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
                                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                    <ul class="m-menu__subnav">
                                        <li class="m-menu__item {{strpos($url, 'request_offers') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                            <a href="{{route('requests-offer')}}" class="m-menu__link ">
                                                <i class="m-menu__link-icon flaticon-users"></i>
                                                <span class="m-menu__link-text">
                                                    {{trans('lang.request_offers')}}
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif
                       

                        @if(in_array('ads', $roles) || auth()->user()->permission->is_superadmin == 1)
                            <li class="m-menu__item {{strpos($url, 'ads') ? 'm-menu__item--active-tab' : ''}} m-menu__item--submenu m-menu__item--tabs"
                                m-menu-submenu-toggle="tab" aria-haspopup="true">
                                <a href="{{route('ads')}}" class="m-menu__link">
                                    <span class="m-menu__link-text">
                                        {{trans('lang.ads')}}
                                    </span>
                                    <i class="m-menu__hor-arrow la la-angle-down"></i>
                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
                                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                    <ul class="m-menu__subnav">
                                        <li class="m-menu__item {{strpos($url, 'ads') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                            <a href="{{route('ads')}}" class="m-menu__link ">
                                                <i class="m-menu__link-icon flaticon-users"></i>
                                                <span class="m-menu__link-text">
                                                    {{trans('lang.ads')}}
                                                </span>
                                            </a>
                                        </li>

                                        <li class="m-menu__item  m-menu__item--actions" aria-haspopup="true">
                                                <div class="m-menu__link m-menu__link--toggle-skip">
                                                    <a href="{{route('create-ads')}}" class="btn btn-focus m-btn m-btn--icon m-btn--pill">
                                                        <span>
                                                            <i class="flaticon-plus"></i>
                                                            <span>
                                                                {{ trans('lang.slider') }}
                                                            </span>
                                                        </span>
                                                    </a>
                                                </div>
                                            </li>
                                    </ul>
                                </div>
                            </li>
                        @endif


                        @if(in_array('admin_notifications', $roles) || auth()->user()->permission->is_superadmin == 1)
                            <li class="m-menu__item {{strpos($url, 'admin_notifications') || strpos($url, 'send_notifications') ? 'm-menu__item--active-tab' : ''}} m-menu__item--submenu m-menu__item--tabs"
                                m-menu-submenu-toggle="tab" aria-haspopup="true">
                                <a href="{{route('admin_notifications')}}" class="m-menu__link">
                                    <span class="m-menu__link-text">
                                        {{trans('lang.admin_notifications')}}
                                        <span class="m-badge m-badge--success"> {{$notificationsCount}} </span>
                                    </span>
                                    <i class="m-menu__hor-arrow la la-angle-down"></i>
                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
                                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                    <ul class="m-menu__subnav">
                                        <li class="m-menu__item {{strpos($url, 'send_notifications') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                            <a href="{{route('send-notifications')}}" class="m-menu__link ">
                                                <i class="m-menu__link-icon flaticon-users"></i>
                                                <span class="m-menu__link-text">
                                                    {{trans('lang.send_notifications')}}
                                                </span>
                                            </a>
                                        </li>

                                        <li class="m-menu__item {{strpos($url, 'admin_notifications') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                            <a href="{{route('admin_notifications')}}" class="m-menu__link ">
                                                <i class="m-menu__link-icon flaticon-users"></i>
                                                <span class="m-menu__link-text">
                                                    {{trans('lang.admin_notifications')}}
                                                </span>
                                            </a>
                                        </li>

                                        <li class="m-menu__item  m-menu__item--actions" aria-haspopup="true">
                                            <div class="m-menu__link m-menu__link--toggle-skip">
                                                <a href="{{route('send-notifications-create')}}" class="btn btn-focus m-btn m-btn--icon m-btn--pill">
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
                        @endif


                        @if(in_array('permissions', $roles) || auth()->user()->permission->is_superadmin == 1)
                            <li class="m-menu__item {{strpos($url, 'permissions') ? 'm-menu__item--active-tab' : ''}} m-menu__item--submenu m-menu__item--tabs"
                                m-menu-submenu-toggle="tab" aria-haspopup="true">
                                <a href="{{route('permissions')}}" class="m-menu__link">
                                    <span class="m-menu__link-text">
                                        {{trans('lang.permissions')}}
                                    </span>
                                    <i class="m-menu__hor-arrow la la-angle-down"></i>
                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
                                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                    <ul class="m-menu__subnav">
                                        <li class="m-menu__item {{strpos($url, 'permissions') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                            <a href="{{route('permissions')}}" class="m-menu__link ">
                                                <i class="m-menu__link-icon flaticon-users"></i>
                                                <span class="m-menu__link-text">
                                                    {{trans('lang.permissions')}}
                                                </span>
                                            </a>
                                        </li>

                                        <li class="m-menu__item  m-menu__item--actions" aria-haspopup="true">
                                            <div class="m-menu__link m-menu__link--toggle-skip">
                                                <a href="{{route('create-permission')}}" class="btn btn-focus m-btn m-btn--icon m-btn--pill">
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
                        @endif



                       
                            <li class="m-menu__item m-menu__item--submenu m-menu__item--tabs"
                                m-menu-submenu-toggle="tab" aria-haspopup="true">
                                <a href="{{route('videos')}}" class="m-menu__link">
                                    <span class="m-menu__link-text">
                                        video
                                    </span>
                                    <i class="m-menu__hor-arrow la la-angle-down"></i>
                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
                                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                    <ul class="m-menu__subnav">
                                    
                                    
                                    </ul>
                                </div>
                            </li>
                       


                        @if(in_array('edit_profile_request', $roles) || auth()->user()->permission->is_superadmin == 1)
                            <li class="m-menu__item {{strpos($url, 'edit_profile_request') ? 'm-menu__item--active-tab' : ''}} m-menu__item--submenu m-menu__item--tabs"
                                m-menu-submenu-toggle="tab" aria-haspopup="true">
                                <a href="{{route('edit_profile_request')}}" class="m-menu__link">
                                    <span class="m-menu__link-text">
                                        {{trans('lang.edit_profile_request')}}
                                    </span>
                                    <i class="m-menu__hor-arrow la la-angle-down"></i>
                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
                                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                    <ul class="m-menu__subnav">
                                        <li class="m-menu__item {{strpos($url, 'edit_profile_request') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                            <a href="{{route('edit_profile_request')}}" class="m-menu__link ">
                                                <i class="m-menu__link-icon flaticon-users"></i>
                                                <span class="m-menu__link-text">
                                                    {{trans('lang.edit_profile_request')}}
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif


                        @if(in_array('admins', $roles) || auth()->user()->permission->is_superadmin == 1)
                            <li class="m-menu__item {{strpos($url, 'admins') ? 'm-menu__item--active-tab' : ''}} m-menu__item--submenu m-menu__item--tabs"
                                m-menu-submenu-toggle="tab" aria-haspopup="true">
                                <a href="{{route('admins')}}" class="m-menu__link">
                                    <span class="m-menu__link-text">
                                        {{trans('lang.admins')}}
                                    </span>
                                    <i class="m-menu__hor-arrow la la-angle-down"></i>
                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
                                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                    <ul class="m-menu__subnav">
                                        <li class="m-menu__item {{strpos($url, 'admins') ? 'm-menu__item--active' : ''}}" m-menu-link-redirect="1" aria-haspopup="true">
                                            <a href="{{route('admins')}}" class="m-menu__link ">
                                                <i class="m-menu__link-icon flaticon-users"></i>
                                                <span class="m-menu__link-text">
                                                    {{trans('lang.admins')}}
                                                </span>
                                            </a>
                                        </li>

                                        <li class="m-menu__item  m-menu__item--actions" aria-haspopup="true">
                                            <div class="m-menu__link m-menu__link--toggle-skip">
                                                <a href="{{route('create-admin')}}" class="btn btn-focus m-btn m-btn--icon m-btn--pill">
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
                        @endif
                    </ul>
                </div>
            </div>
            <!-- end::Horizontal Menu -->
