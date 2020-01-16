@extends('layouts.master')
@section('title')
{{trans('lang.providerRegisterRequest')}}
@endsection

@section('css')
    @if(LaravelLocalization::getCurrentLocaleDirection() == 'ltr')  
        <link href="{{asset('assets/css/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
    @else
        <link href="{{asset('assets/css/datatables.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    @endif
    <link rel="stylesheet" href="{{asset('assets/sweetalert.css')}}">
@endsection

@section('content')
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
            <h3 class="m-subheader__title ">{{trans('lang.providerRegisterRequest')}}</h3>
            </div>
        </div>
    </div>

    <!-- END: Subheader -->
    <div class="m-content">
                <div class="m-portlet m-portlet--mobile">
                         
                    <div class="m-portlet__body">
                  <table class="table table-striped- table-bordered table-hover table-checkable" id="m_table_1">
                    <thead>
                        <tr>
                            <th>{{trans('lang.name')}}</th>
                            <th>{{trans('lang.email')}}</th>
                            <th>{{trans('lang.phone')}}</th>
                            <th>{{trans('lang.address')}}</th>
                            <th>{{trans('lang.created_at')}}</th>
                            <th>{{trans('lang.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($providers as $provider)
                            <tr>
                                <td style="text-align: center;">{{$provider->name}}</td>
                                <td style="text-align: center;">{{$provider->email}}</td>
                                <td style="text-align: center;">{{$provider->code.$provider->phone}}</td>
                                <td style="text-align: center;">{{$provider->address}}</td>
                                <td style="text-align: center;">{{date('Y-m-d',strtotime($provider->created_at))}}</td>
                                <td style="text-align: center;">
                                        <a href="{{route('create-provider')}}" class="btn btn-primary ">{{trans('lang.create')}}</a>
                                        <a href="" class="btn btn-danger delete-button" data-id="{{ $provider->id }}">{{ trans('lang.delete') }}</a>
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
    <!-- DataTables -->
    <script src="{{asset('assets/js/datatables.bundle.js')}}" type="text/javascript"></script>
    @if(LaravelLocalization::getCurrentLocale() == 'ar')
        <script>let cancelButton = 'إلغاء';</script>
    @else
        <script>let cancelButton = 'Cancel';</script>
    @endif
    <script src="{{asset('assets/sweetalert.min.js')}}"></script>
    <script>
            $(function () {
            
                $('#m_table_1').DataTable({
                    paging       : true,
                    lengthChange : true,
                    searching    : true,
                    ordering     : true,
                    info         : false,
                    autoWidth    : true,
                    order: [[ 4, "desc" ]],
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
            })

        $(document).on('click', '.delete-button', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            btn = $(this);
            @if(LaravelLocalization::getCurrentLocale() == 'ar')
                swal({
                        title: "هل انت متاكد ?",
                        type: "error",
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "نعم",
                        showCancelButton: true,
                    },
                    function() {
                        $.ajax({
                            type: "GET",
                            url: "{{url("/backend/providers/register/requests/delete")}}" + "/" + id,
                        
                            success: function () {
                                swal("تم المسح", "تم مسح العنصر بنجاح", "success");
                                    btn.parent().parent().fadeOut(700, function() { btn.remove(); });
                                },
                            error: function(){
                                swal("Danger", "ليس لديك صلاحية مسح هذا العنصر", "error");
                            }       
                        });
                });

            @else
                swal({
                        title: "Are You Sure ?",
                        type: "error",
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes",
                        showCancelButton: true,
                    },
                    function() {
                        $.ajax({
                            type: "GET",
                            url: "{{url("/backend/providers/register/requests/delete")}}" + "/" + id,
                        
                            success: function () {
                                swal("Deleted", "Item deleted successfully", "success");
                                    btn.parent().parent().fadeOut(700, function() { btn.remove(); });
                                },
                            error: function(){
                                swal("Danger", "You not have permission to remove", "error");
                            }       
                        });
                });

            @endif
        });
    </script>
@endsection
