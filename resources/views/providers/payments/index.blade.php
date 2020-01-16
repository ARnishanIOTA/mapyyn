@extends('layouts.master')

@section('title') {{trans('lang.payments')}} @endsection

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
                <h3 class="m-subheader__title ">{{ trans('lang.payments') }}</h3>
            </div>
        </div>
    </div>
    @if(LaravelLocalization::getCurrentLocale() == 'ar')
    <?php 
        $name = 'name_ar'; 
    ?>
    @else
    <?php 
        $name = 'name_en'; 
    ?>
@endif
    <!-- END: Subheader -->
    <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                    
                    <div class="m-portlet__body">

                        <!--begin: Datatable -->
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="m_table_1">
                            <thead>
                                <tr>
                                    <th>{{ trans('lang.client') }}</th>
                                    <th>{{ trans('lang.email') }}</th>
                                    <th>{{ trans('lang.phone') }}</th>
                                    <th>{{ trans('lang.go_date') }}</th>
                                    <th>{{ trans('lang.tansaction_id') }}</th>
                                    <th>{{ trans('lang.offer') }}</th>
                                    <th>{{ trans('lang.created_at') }}</th>
                                    <th>{{ trans('lang.status') }}</th>
                                    <th>{{ trans('lang.attachment') }}</th>
                                    <th>{{ trans('lang.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                <tr id="{{$payment->id}}">
                                     <td>{{$payment->client->first_name . ' ' . $payment->client->last_name}}</td>
                                     <td>{{$payment->client->email}}</td>
                                     <td>{{$payment->client->phone}}</td>
                                     <td>{{$payment->go_date}}</td>
                                     <td>{{$payment->transaction_id}}</td>
                                     @if ($payment->request_offer_id != null)
                                        <td><a href='{{url("providers/requests_offers/details/$payment->request_offer_id")}}' target="_blank">{{ trans('request_offer') }}</a></td>
                                     @else
                                        <td><a href='{{url("providers/offers/$payment->offer_id")}}' target="_blank">{{ trans('offer') }}</a></td>
                                     @endif
                                     <td>{{date_format($payment->created_at,"Y-m-d")}}</td>

                                     <td>
                                        @if ($payment->status == 'pending')
                                            <label class="m-badge m-badge--danger m-badge--wide">{{trans("lang.$payment->status")}}</td></label>
                                        @elseif(($payment->status == 'closed'))
                                            <label class="m-badge m-badge--success m-badge--wide">{{trans("lang.$payment->status")}}</td></label>
                                        @else
                                            <label class="m-badge m-badge--primary m-badge--wide">{{trans("lang.$payment->status")}}</td></label>
                                        @endif 
                                    </td>
                                    <td>
                                        @forelse ($payment->files as $item)
                                            <a href="{{asset("uploads/$item->file")}}" download >
                                                <span><span>{{ trans('lang.download') }}</span></span></a>
                                            </a>&nbsp;&nbsp;
                                        @empty
                                            ----  
                                        @endforelse
                                    </td>
                                     <td align="center">
                                            @if ($payment->status != 'paid')
                                                <a href="{{route('update-payment', ['id' => $payment->id])}}" class="btn btn-success m-btn m-btn--icon">
                                                    <span><span>{{ trans('lang.update') }}</span></span></a>
                                                </a>
                                            @endif
                                         
                                         <a href="{{route('payment.passenger', ['id' => $payment->id])}}" class="btn btn-primary m-btn m-btn--icon">
                                                <span><span>{{ trans('lang.continueData') }}</span></span></a>
                                            </a>
                                     </td>
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
                order: [[ 6, "desc" ]],
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