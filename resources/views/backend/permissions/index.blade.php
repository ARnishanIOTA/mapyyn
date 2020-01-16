@extends('layouts.master')
@section('title')
{{trans('lang.permissions')}}
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
            <h3 class="m-subheader__title ">{{trans('lang.permissions')}}</h3>
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
                            <th>{{trans('lang.super_admin')}}</th>
                            <th>{{trans('lang.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @foreach($permissions as $permission)
                            <tr id="{{$permission->id}}">
                                <td style="text-align: center;">{{$permission->name}}</td>
                                @if($permission->is_superadmin == 1)
                                    <td style="text-align: center;"><span class="m-badge m-badge--success m-badge--wide">Yes</span></td>
                                @else
                                    <td style="text-align: center;"><span class="m-badge m-badge--danger m-badge--wide">No</span></td>
                                @endif
                                <td style="text-align: center;">
                                    @if($permission->is_superadmin == 0)
                                        <a href="{{route('update-permission', ['id' => $permission->id])}}" class="btn btn-primary ">{{trans('lang.update')}}</a>
                                        <a href="" class="btn btn-danger delete-button" data-id="{{ $permission->id }}">Delete</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>{{trans('lang.name')}}</th>
                            <th>{{trans('lang.super_admin')}}</th>
                            <th>{{trans('lang.actions')}}</th>
                        </tr>
                    </tfoot>
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
                    'paging'      : true,
                    'lengthChange': true,
                    'searching'   : true,
                    'ordering'    : true,
                    'info'        : false,
                    'autoWidth'   : true,
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
                            url: "{{url("/backend/permissions/delete")}}" + "/" + id,
                        
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
                            url: "{{url("/backend/permissions/delete")}}" + "/" + id,
                        
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
