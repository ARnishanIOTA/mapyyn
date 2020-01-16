@extends('layouts.master')

@section('title')  @endsection

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
    {{-- <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title ">{{ trans('lang.admins') }}</h3>
            </div>
        </div>
    </div> --}}

    <!-- END: Subheader -->
    <div class="m-menu__link m-menu__link--toggle-skip">
                                                <a href="{{route('videos.create')}}" class="btn btn-focus m-btn m-btn--icon m-btn--pill" style="float: right;">
                                                    <span>
                                                        <i class="flaticon-plus"></i>
                                                        <span>
                                                            {{ trans('lang.update') }}
                                                        </span>
                                                    </span>
                                                </a>&nbsp;
                                            </div>
    <div class="m-content">


   

            <div class="m-portlet m-portlet--mobile">
                    
                    <div class="m-portlet__body">

                        <!--begin: Datatable -->
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="m_table_1">
                            <thead>

                                <tr>
                                    <th>Id</th>
                                    <th>videos</th>
                                    
                                </tr>
                            </thead>
                            @foreach($Videos as $video)
                                   <tbody>
                                
                           
                                    <tr>
                                       <td>
                                       {{ $loop -> index+1 }}
                                        </td>

                                        <td>{{ $video->video }}</td>

                             
                                    </tr>
                          
                               
                            </tbody>
                            @endforeach
                            
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