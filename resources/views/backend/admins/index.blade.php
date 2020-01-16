@extends('layouts.master')

@section('title') {{trans('lang.admins')}} @endsection

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
                <h3 class="m-subheader__title ">{{ trans('lang.admins') }}</h3>
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
                                    <th>{{ trans('lang.email') }}</th>
                                    <th>{{ trans('lang.permission') }}</th>
                                    <th>{{ trans('lang.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                               @foreach ($admins as $admin)
                                    <?php $image = $admin->image == null ? 'default.png' : $admin->image ?>
                                    <tr>
                                       <td>
                                            <a href='{{asset("uploads/$image")}}' data-lity><img src='{{asset("uploads/$image")}}' style="border-radius:50%" width="40" hieght="50"></a>
                                            {{$admin->name}}
                                        </td>

                                        <td>{{$admin->email}}</td>
                                        <td>{{$admin->permission->name}}</td>
                                        <td>
                                                <a href="{{route('update-admin', ['id' => $admin->id])}}" class="btn btn-success m-btn m-btn--icon">
                                                    <span><i class="flaticon-edit"></i><span>{{ trans('lang.edit') }}</span></span>
                                                </a>
                                            @if($admin->id != 1)
                                                <a href="" data-id="{{ $admin->id }}" class="btn btn-danger delete-button m-btn m-btn--icon">
                                                    <span><i class="flaticon-delete-2"></i><span>{{ trans('lang.remove') }}</span></span>
                                                </a>
                                            @endif
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
                            url: "{{url("/backend/admins/delete")}}" + "/" + id,
                        
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
                            url: "{{url("/backend/categories/delete")}}" + "/" + id,
                        
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

        var loadFile = function(event) {
                var output = document.getElementById('output');
                output.src = URL.createObjectURL(event.target.files[0]);
            };
    </script>
@endsection