@extends('layouts.master')
@section('title')
{{trans('lang.edit_profile_request')}}
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
            <h3 class="m-subheader__title ">{{trans('lang.edit_profile_request')}}</h3>
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
                            <th>{{trans('lang.first_name')}}</th>
                            <th>{{trans('lang.last_name')}}</th>
                            <th>{{trans('lang.categories')}}</th>
                            <th>{{trans('lang.email')}}</th>
                            <th>{{trans('lang.phone')}}</th>
                            <th>{{trans('lang.country')}}</th>
                            <th>{{trans('lang.city')}}</th>
                            <th>{{trans('lang.address')}}</th>
                            <th>{{trans('lang.provider')}}</th>
                            <th>{{trans('lang.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @foreach($profiles as $profile)
                            <tr>
                                <td>{{$profile->first_name}}</td>
                                <td>{{$profile->last_name}}</td>
                                <td>
                                    @if($profile->provider->editCategories()->count() > 0)
                                        <?php 
                                        $label = '';
                                            foreach($profile->provider->editCategories as $category) {
                                                if($category->category_id == 1){
                                                    $label .= '<span class="m-badge m-badge--default m-badge--wide">'.trans('lang.entertainment').'</span>';
                                                }elseif($category->category_id == 2){
                                                    $label .= '<span class="m-badge m-badge--default m-badge--wide">'.trans('lang.educational').'</span>';
                                                }elseif($category->category_id == 3){
                                                    $label .= '<span class="m-badge m-badge--default m-badge--wide">'.trans('lang.sport').'</span>';
                                                }else{
                                                    $label .= '<span class="m-badge m-badge--default m-badge--wide">'.trans('lang.medical').'</span>';
                                                }
                            
                                            }

                                        ?>
                                        {!!$label!!}
                                    @else
                                    ---
                                    @endif
                                </td>
                                <td>{{$profile->email}}</td>
                                <td>{{$profile->code.$profile->phone}}</td>
                                <td>{{$profile->country}}</td>
                                <td>{{$profile->city}}</td>
                                <td>{{$profile->address}}</td>
                                <td>{{$profile->provider->first_name . ' ' . $profile->provider->last_name}}</td>
                                <td style="text-align: center;">
                                    <a href="{{route('edit_profile_request_update', ['id' => $profile->id, 'status' => 1])}}" class="btn btn-primary ">{{trans('lang.accept')}}</a>
                                    <a href="{{route('edit_profile_request_update', ['id' => $profile->id, 'status' => 0])}}" class="btn btn-danger ">{{trans('lang.reject')}}</a>
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

        
    </script>
@endsection
