@extends('layouts.master')


@section('title')  {{ trans('lang.statistics') }}  @endsection

@section('content')
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">{{trans('lang.statistics')}}</h3>
        </div>
    </div>
</div>

<!-- END: Subheader -->
<div class="m-content">
   <!--Begin::Section-->
                    <div class="m-portlet">
                        <div class="m-portlet__body m-portlet__body--no-padding">
                            <div class="row m-row--no-padding m-row--col-separator-xl">
                                <div class="col-md-12 col-lg-12 col-xl-4">

                                    <!--begin:: Widgets/Stats2-1 -->
                                    <div class="m-widget1">
                                        <div class="m-widget1__item">
                                            <div class="row m-row--no-padding align-items-center">
                                                <div class="col">
                                                    <h3 class="m-widget1__title"><i class="flaticon-pie-chart"></i>&nbsp;{{trans('lang.clients')}}</h3>
                                                </div>
                                                <div class="col m--align-right">
                                                    <span class="m-widget1__number m--font-brand">{{$clients}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-widget1__item">
                                            <div class="row m-row--no-padding align-items-center">
                                                <div class="col">
                                                    <h3 class="m-widget1__title"><i class="flaticon-pie-chart"></i>&nbsp;{{trans('lang.payments_transaction')}}</h3>
                                                </div>
                                                <div class="col m--align-right">
                                                    <span class="m-widget1__number m--font-danger">{{$payments}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <!--end:: Widgets/Stats2-1 -->
                                </div>
                                <div class="col-md-12 col-lg-12 col-xl-4">

                                    <!--begin:: Widgets/Stats2-2 -->
                                    <div class="m-widget1">
                                        <div class="m-widget1__item">
                                            <div class="row m-row--no-padding align-items-center">
                                                <div class="col">
                                                    <h3 class="m-widget1__title"><i class="flaticon-pie-chart"></i>&nbsp;{{trans('lang.offers')}}</h3>
                                                </div>
                                                <div class="col m--align-right">
                                                    <span class="m-widget1__number m--font-accent">{{$offers}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-widget1__item">
                                            <div class="row m-row--no-padding align-items-center">
                                                <div class="col">
                                                    <h3 class="m-widget1__title"><i class="flaticon-pie-chart"></i>&nbsp;{{trans('lang.request_offers')}}</h3>
                                                </div>
                                                <div class="col m--align-right">
                                                    <span class="m-widget1__number m--font-info">{{$request_offers}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <!--begin:: Widgets/Stats2-2 -->
                                </div>
                                <div class="col-md-12 col-lg-12 col-xl-4">

                                    <!--begin:: Widgets/Stats2-3 -->
                                    <div class="m-widget1">
                                        <div class="m-widget1__item">
                                            <div class="row m-row--no-padding align-items-center">
                                                <div class="col">
                                                    <h3 class="m-widget1__title"><i class="flaticon-pie-chart"></i>&nbsp;{{trans('lang.providers')}}</h3>
                                                </div>
                                                <div class="col m--align-right">
                                                    <span class="m-widget1__number m--font-success">{{$providers}}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="m-widget1__item">
                                            <div class="row m-row--no-padding align-items-center">
                                                <div class="col">
                                                    <h3 class="m-widget1__title"><i class="flaticon-pie-chart"></i>&nbsp;{{trans('lang.messages')}}</h3>
                                                </div>
                                                <div class="col m--align-right">
                                                    <span class="m-widget1__number m--font-success">{{$messages}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <!--begin:: Widgets/Stats2-3 -->
                                </div>
                            </div>
                        </div>
                    </div>

</div>
@endsection

@section('js')
    <script src="{{asset('assets/js/dashboard.js')}}" type="text/javascript"></script>
@endsection