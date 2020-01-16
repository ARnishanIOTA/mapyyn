@extends('layouts.master')

@section('title') {{trans('lang.offers')}} @endsection

@section('css')
    @if(LaravelLocalization::getCurrentLocaleDirection() == 'ltr')  
        <link href="{{asset('assets/css/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
    @else
        <link href="{{asset('assets/css/datatables.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    @endif
    <link rel="stylesheet" href="{{asset('assets/sweetalert.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/lity.min.css')}}">
@endsection

@section('content')
<!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title ">{{ trans('lang.request-offers') }}</h3>
            </div>
        </div>
    </div>

    <!-- END: Subheader -->
    <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                    
                    <div class="m-portlet__body">

                        <!--begin: Datatable -->
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="m_table_1">
                            <thead>
                                <tr>
                                    <th>{{ trans('lang.order_id') }}</th>
                                    <th>{{ trans('lang.from_country') }}</th>
                                    <th>{{ trans('lang.to_country') }}</th>
                                    <th>{{ trans('lang.transport') }}</th>
                                    <th>{{ trans('lang.client') }}</th>
                                    <th>{{ trans('lang.provider') }}</th>
                                    <th>{{ trans('lang.category') }}</th>
                                    <th>{{ trans('lang.created_at') }}</th>
                                    <th>{{ trans('lang.status') }}</th>
                                    <th>{{ trans('lang.is_active') }}</th>
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
    <script src="{{asset('assets/js/datatables.bundle.js')}}" type="text/javascript"></script>
    {{--  <script src="{{asset('assets/backend/js/buttons.js')}}" type="text/javascript"></script>  --}}
    <script src="{{asset('assets/js/lity.min.js')}}"></script>
    @if(LaravelLocalization::getCurrentLocale() == 'ar')
        <script>let cancelButton = 'إلغاء';</script>
    @else
        <script>let cancelButton = 'Cancel';</script>
    @endif
    <script src="{{asset('assets/sweetalert.min.js')}}"></script>
    <script>
           
            $("#m_table_1").DataTable({
                responsive: !0,
                processing: true,
                serverSide: true,
                order: [[ 7, "desc" ]],
                ajax: '{!! route('requests-offer-data') !!}',
                columns: [
                    { data: 'id' },
                    { data: 'from_country' },
                    { data: 'to_country'},
                    { data: 'transport'},
                    { data: 'client_id'},
                    { data: 'provider',orderable: false, searchable: false},
                    { data: 'category_id', orderable: false, searchable: false},
                    { data: 'created_at'},
                    { data: 'status'},
                    { data: 'is_active'},
                    { data: 'action'}
                ],
                @if(LaravelLocalization::getCurrentLocale() == 'ar')
                    "language": {
                        "sZeroRecords": "لا يوجد بيانات",
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