@extends('layouts.master')

@section('title') {{trans('lang.continueData')}} @endsection

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
                <h3 class="m-subheader__title ">{{ trans('lang.continueData') }}</h3>
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
                                    <th>{{ trans('lang.name') }}</th>
                                    <th>{{ trans('lang.first_name') }}</th>
                                    <th>{{ trans('lang.last_name') }}</th>
                                    <th>{{ trans('lang.passportEndDate') }}</th>
                                    <th>{{ trans('lang.birthdate') }}</th>
                                    <th>{{ trans('lang.passport_country') }}</th>
                                    <th>{{ trans('lang.passport_number') }}</th>
                                    <th>{{ trans('lang.nationality') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($passengers as $passenger)
                                    <tr>
                                        <td>{{$passenger->name}}</td>
                                        <td>{{$passenger->first_name}}</td>
                                        <td>{{$passenger->last_name}}</td>
                                        <td>{{$passenger->passport_end_date}}</td>
                                        <td>{{$passenger->birthdate}}</td>
                                        <td>{{$passenger->passport_country}}</td>
                                        <td>{{$passenger->passport_number}}</td>
                                        <td>{{$passenger->nationality}}</td>
                                    </tr>
                                @endforeach
                               
                            </tbody>
                            
                        </table>
                    </div>
                </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('assets/js/datatables.bundle.js')}}" type="text/javascript"></script>
    {{--  <script src="{{asset('assets/backend/js/buttons.js')}}" type="text/javascript"></script>  --}}
    @if(LaravelLocalization::getCurrentLocale() == 'ar')
        <script>let cancelButton = 'إلغاء';</script>
    @else
        <script>let cancelButton = 'Cancel';</script>
    @endif
    <script src="{{asset('assets/sweetalert.min.js')}}"></script>
    <script src="{{asset('assets/js/lity.min.js')}}"></script>
    <script>

         $("#m_table_1").DataTable({
                responsive: !0,
                processing: true,
                serverSide: false,
                order: [[ 3, "desc" ]],
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