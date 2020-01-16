@extends('layouts.master')


@section('title')  {{ trans('lang.payments') }}  @endsection

@section('css')
    @if(LaravelLocalization::getCurrentLocaleDirection() == 'ltr')  
        <link href="{{asset('assets/css/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
    @else
        <link href="{{asset('assets/css/datatables.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    @endif
@endsection

@section('content')
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">{{trans('lang.payments')}}</h3>
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
                                                    <h3 class="m-widget1__title"><i class="flaticon-pie-chart"></i>&nbsp;{{trans('lang.total_payments')}}</h3>
                                                </div>
                                                <div class="col m--align-right">
                                                    <span class="m-widget1__number m--font-brand">{{$total_payments}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-widget1__item">
                                            <div class="row m-row--no-padding align-items-center">
                                                <div class="col">
                                                    <h3 class="m-widget1__title"><i class="flaticon-pie-chart"></i>&nbsp;{{trans('lang.total_payments_price')}}</h3>
                                                </div>
                                                <div class="col m--align-right">
                                                    <span class="m-widget1__number m--font-brand">{{$total_payments_price}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-widget1__item">
                                            <div class="row m-row--no-padding align-items-center">
                                                <div class="col">
                                                    <h3 class="m-widget1__title"><i class="flaticon-pie-chart"></i>&nbsp;{{trans('lang.month_payments')}}</h3>
                                                </div>
                                                <div class="col m--align-right">
                                                    <span class="m-widget1__number m--font-danger">{{$month_payments}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-widget1__item">
                                            <div class="row m-row--no-padding align-items-center">
                                                <div class="col">
                                                    <h3 class="m-widget1__title"><i class="flaticon-pie-chart"></i>&nbsp;{{trans('lang.month_payments_price')}}</h3>
                                                </div>
                                                <div class="col m--align-right">
                                                    <span class="m-widget1__number m--font-brand">{{$month_payments_price}}</span>
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
                                                    <h3 class="m-widget1__title"><i class="flaticon-pie-chart"></i>&nbsp;{{trans('lang.proccess_payments')}}</h3>
                                                </div>
                                                <div class="col m--align-right">
                                                    <span class="m-widget1__number m--font-accent">{{$proccess_payments}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-widget1__item">
                                            <div class="row m-row--no-padding align-items-center">
                                                <div class="col">
                                                    <h3 class="m-widget1__title"><i class="flaticon-pie-chart"></i>&nbsp;{{trans('lang.proccess_payments_price')}}</h3>
                                                </div>
                                                <div class="col m--align-right">
                                                    <span class="m-widget1__number m--font-info">{{$proccess_payments_price}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-widget1__item">
                                                <div class="row m-row--no-padding align-items-center">
                                                <div class="col">
                                                    <h3 class="m-widget1__title"><i class="flaticon-pie-chart"></i>&nbsp;{{trans('lang.done_payments')}}</h3>
                                                </div>
                                                <div class="col m--align-right">
                                                    <span class="m-widget1__number m--font-accent">{{$done_payments}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-widget1__item">
                                            <div class="row m-row--no-padding align-items-center">
                                                <div class="col">
                                                    <h3 class="m-widget1__title"><i class="flaticon-pie-chart"></i>&nbsp;{{trans('lang.done_payments_price')}}</h3>
                                                </div>
                                                <div class="col m--align-right">
                                                    <span class="m-widget1__number m--font-info">{{$done_payments_price}}</span>
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
                                                    <h3 class="m-widget1__title"><i class="flaticon-pie-chart"></i>&nbsp;{{trans('lang.hold_payments')}}</h3>
                                                </div>
                                                <div class="col m--align-right">
                                                    <span class="m-widget1__number m--font-success">{{$hold_payments}}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="m-widget1__item">
                                            <div class="row m-row--no-padding align-items-center">
                                                <div class="col">
                                                    <h3 class="m-widget1__title"><i class="flaticon-pie-chart"></i>&nbsp;{{trans('lang.hold_payments_price')}}</h3>
                                                </div>
                                                <div class="col m--align-right">
                                                    <span class="m-widget1__number m--font-success">{{$hold_payments_price}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-widget1__item">
                                            <div class="row m-row--no-padding align-items-center">
                                                <div class="col">
                                                    <h3 class="m-widget1__title"><i class="flaticon-pie-chart"></i>&nbsp;{{trans('lang.billed_payments')}}</h3>
                                                </div>
                                                <div class="col m--align-right">
                                                    <span class="m-widget1__number m--font-success">{{$billed_payments}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-widget1__item">
                                            <div class="row m-row--no-padding align-items-center">
                                                <div class="col">
                                                    <h3 class="m-widget1__title"><i class="flaticon-pie-chart"></i>&nbsp;{{trans('lang.billed_payments_price')}}</h3>
                                                </div>
                                                <div class="col m--align-right">
                                                    <span class="m-widget1__number m--font-success">{{$billed_payments_price}}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="m-widget1__item">
                                                <div class="row m-row--no-padding align-items-center">
                                                    <div class="col">
                                                        <h3 class="m-widget1__title"><i class="flaticon-pie-chart"></i>&nbsp;{{trans('lang.refund_payments')}}</h3>
                                                    </div>
                                                    <div class="col m--align-right">
                                                        <span class="m-widget1__number m--font-success">{{$refund_payments}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="m-widget1__item">
                                                <div class="row m-row--no-padding align-items-center">
                                                    <div class="col">
                                                        <h3 class="m-widget1__title"><i class="flaticon-pie-chart"></i>&nbsp;{{trans('lang.refund_payments_price')}}</h3>
                                                    </div>
                                                    <div class="col m--align-right">
                                                        <span class="m-widget1__number m--font-success">{{$refund_payments_price}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                    </div>
                                    <!--begin:: Widgets/Stats2-3 -->
                                </div>
                            </div>


                             <!--begin: Datatable -->
                        
                        </div>
                    </div>
                    

</div>
<div class="m-content">
        <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    <span>{{ trans('lang.payments') }}</span>
                                </h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <ul class="m-portlet__nav">
                                <li class="m-portlet__nav-item">
                                    <button onclick="window.location.href='{{route('admin.downloads.payments')}}'" id="export_data" class="btn btn-primary">
                                        <span>
                                            <span>{{ trans('lang.export_data') }}</span>
                                        </span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                <div class="m-portlet__body">
                <table class="table table-striped- table-bordered table-hover table-checkable" id="m_table_1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ trans('lang.created_at') }}</th>
                                <th>{{ trans('lang.tansaction_id') }}</th>
                                <th>{{ trans('lang.offer') }}</th>
                                <th>{{ trans('lang.client') }}</th>
                                <th>{{ trans('lang.price') }}</th>
                                <th>{{ trans('lang.provider') }}</th>
                                <th>{{ trans('lang.status') }}</th>
                                <th>{{ trans('lang.attachment') }}</th>
                                <th>{{ trans('lang.admin_status') }}</th>
                                <th>{{ trans('lang.notes') }}</th>
                                <th>{{ trans('lang.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script src="{{asset('assets/js/dashboard.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/js/datatables.bundle.js')}}" type="text/javascript"></script>
    {{--  <script src="{{asset('assets/backend/js/buttons.js')}}" type="text/javascript"></script>  --}}
    @if(LaravelLocalization::getCurrentLocale() == 'ar')
        <script>let cancelButton = 'إلغاء';</script>
    @else
        <script>let cancelButton = 'Cancel';</script>
    @endif
    <script>
           
            $("#m_table_1").DataTable({
                responsive: !0,
                processing: true,
                serverSide: true,
                order: [[1, "desc" ]],
                ajax: '{!! route('admin.payments.data') !!}',
                columns: [
                    { data: 'id' },
                    { data: 'created_at' },
                    { data: 'transaction_id'},
                    { data: 'offer' },
                    { data: 'client.first_name' },
                    { data: 'price' },
                    { data: 'provider.first_name' },
                    { data: 'status' },
                    { data: 'attachment' },
                    { data: 'admin_status' },
                    { data: 'notes' },
                    { data: 'action', orderable: false, searchable: false}
                ],
                @if(LaravelLocalization::getCurrentLocale() == 'ar')
                    "language": {
                        "emptyTable": "لا يوجد بيانات",
                        "search": "بحث ",
                        "processing": "جاري التحميل ..",
                        "lengthMenu":     "عرض _MENU_",
                        "info":           "عرض _START_ الى _END_ من _TOTAL_",
                        "infoEmpty":      "عرض 0 الى 0 من 0",
                        "paginate": {
                                "next": "التالي",
                                "previous":"رجوع"
                            }
                    }
                @endif
            })
    </script>
@endsection