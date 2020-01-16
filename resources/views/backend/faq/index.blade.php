@extends('layouts.master')

@section('title') {{trans('lang.faq')}} @endsection

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
                <h3 class="m-subheader__title ">{{ trans('lang.faq') }}</h3>
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
                                    <th>{{ trans('lang.title') }}</th>
                                    <th>{{ trans('lang.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                               
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
    <script>

         $("#m_table_1").DataTable({
                responsive: !0,
                processing: true,
                serverSide: true,
                ajax: '{!! route('faq-data') !!}',
                columns: [
                    { data: 'title_en'},
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
        $(document).on('click', '.delete-button', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            let btn = $(this)
            @if(LaravelLocalization::getCurrentLocale() == 'ar')
                swal({
                        title: "هل انت متاكد ؟",
                        type: "error",
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "نعم",
                        showCancelButton: true,
                    },
                    function() {
                        $.ajax({
                            type: "GET",
                            url: "{{url("/backend/faq/delete")}}" + "/" + id,
                        
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
                            url: "{{url("/backend/faq/delete")}}" + "/" + id,
                        
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