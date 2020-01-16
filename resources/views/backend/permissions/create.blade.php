@extends('layouts.master')

@section('title')
{{trans('lang.create-permission')}}
@endsection

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
            <h3 class="m-subheader__title ">{{trans('lang.create-permission')}}</h3>
            </div>
        </div>
    </div>

    <!-- END: Subheader -->
    <div class="m-content">
            <form id="modelForm" method="POST" action="{{route('create-permission')}}">
                    @csrf
                <div class="m-portlet m-portlet--mobile">
                         
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group" id="rolesGroup">
                            <div style="display:none" class="alert alert-success" id="successMessage" role="alert"><strong>{{ trans('lang.wellDone') }}</strong>
                                {{ trans('lang.successMessage') }}
                            </div>

                            <div class="form-control-feedback " id="rolesMessage"></div>
                        </div>
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="m_table_1">
                            <thead>
                                <tr>
                                    <th>{{trans('lang.page')}}</th>
                                    <th>{{trans('lang.create')}}</th>
                                    <th>{{trans('lang.read')}}</th>
                                    <th>{{trans('lang.update')}}</th>
                                    <th>{{trans('lang.delete')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pages as $page)
                                    <tr>
                                        <td>{{$page->name}}</td>
                                        <td align="center">
                                            <label class="m-checkbox">
                                                <input type="checkbox" name='roles[{{$page->name}}][is_create]' value='1'>
                                                <span></span>
                                                <br>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="m-checkbox m-checkbox--square">
                                                <input type="checkbox" name='roles[{{$page->name}}][is_read]' value='1'>
                                                <span></span>
                                                <br>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="m-checkbox m-checkbox--square">
                                                <input type="checkbox" name='roles[{{$page->name}}][is_update]' value='1'>
                                                <span></span>
                                                <br>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="m-checkbox m-checkbox--square">
                                                <input type="checkbox" name='roles[{{$page->name}}][is_delete]' value='1'>
                                                <span></span>
                                                <br>
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="form-group m-form__group" id="nameGroup">
                            <label for="name">{{ trans('lang.name') }}</label>
                            <input type="text" name="name" class="form-control m-input m-input--square" id="name" placeholder="{{trans('lang.name')}}">
                            <div class="form-control-feedback" id="nameMessage"></div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="m-form__actions">
                                <button type="submit" id="create" class="btn btn-success">{{trans('lang.create')}}</button>
                                <button type="reset" style="display:none" id="reset"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
    </div>
@endsection



@section('js')
    <!-- DataTables -->
    <script src="{{asset('assets/js/datatables.bundle.js')}}" type="text/javascript"></script>
    <script>
            $(function () {
                $('#m_table_1').DataTable({
                'paging'      : true,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : true,
                'info'        : false,
                'autoWidth'   : true
                })
            })
        $(document).ready(function(){
            $('#modelForm').submit(function(e){
                e.preventDefault();
                url = $(this).attr('action');
                data = $(this).serialize();
                $.ajax({
                    type : 'POST',
                    url : url,
                    data : data,
                    beforeSend : function(){
						$('#create').addClass('m-loader m-loader--right m-loader--light').attr('disabled','disabled');
						$('.form-control-feedback').text('');
						$(".form-group").removeClass("has-danger");
						$('#successMessage').hide();
                    }, 

                    success : function(){
                        $("html, body").animate({ scrollTop: 0 }, "slow");
						$('#successMessage').show();
						$('#create').prop('disabled', false);
						$('#create').removeClass('m-loader m-loader--right m-loader--light');
                        $('#reset').click();
                    },

                    error : function(response){
                        error = response.responseJSON.errors;
						if('name' in error){
							$('#nameGroup').addClass('has-danger');
							$('#nameMessage').text(error.name);
                        }

                        if('roles' in error){
                            $('#rolesGroup').addClass('has-danger');
							$('#rolesMessage').html('<strong>'+error.roles+'</strong>');
                        }
                        
						$('#create').prop('disabled', false);
						$('#create').removeClass('m-loader m-loader--right m-loader--light');
                    }
                });
            })
        })   
        
    </script>
@endsection