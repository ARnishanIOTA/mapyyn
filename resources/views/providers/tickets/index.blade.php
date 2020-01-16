@extends('layouts.master')


@section('title')  {{ trans('lang.tickets') }}  @endsection



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
								<h3 class="m-subheader__title ">
                                    {{ trans('lang.tickets') }}
								</h3>
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
                                                    <th>{{ trans('lang.provider') }}</th>
                                                    <th>{{ trans('lang.client') }}</th>
                                                    <th>{{ trans('lang.status') }}</th>
                                                    <th>{{ trans('lang.created_at') }}</th>
                                                    <th>{{ trans('lang.actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               
                                            </tbody>
                                            
                                        </table>
                                    </div>
                                </div>
                                <!-- END EXAMPLE TABLE PORTLET-->
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
    <script>

         $("#m_table_1").DataTable({
                responsive: !0,
                processing: true,
                serverSide: true,
                ajax: '{!! route('ticket_data') !!}',
                columns: [
                    { data: 'title'},
                    { data: 'provider'},
                    { data: 'client'},
                    { data: 'status'},
                    { data: 'created_at'},
                    { data: 'action'},
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