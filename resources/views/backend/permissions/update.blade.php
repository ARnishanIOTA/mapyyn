@extends('layouts.master')

@section('title')
{{trans('lang.update-permission')}}
@endsection

@section('css')
    @if(LaravelLocalization::getCurrentLocaleDirection() == 'ltr')  
        <link href="{{asset('assets/css/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
    @else
        <link href="{{asset('assets/css/datatables.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    @endif
@endsection

@section('content')

    <!-- END: Subheader -->
    <div class="m-content">
            <form method="POST" id="modelForm" action="{{route('update-permission', ['id' => $permission->id])}}">
                    @csrf
                    @method('PATCH')
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
                                  <?php $names = []; ?>
                                      @foreach($permission->roles as $role)
                                        <tr>
                                            <td>{{$role->page}}</td>
                                            <td>
                                                <label class="m-checkbox m-checkbox--square">
                                                    <input type="checkbox" name='roles[{{$role->page}}][is_create]' value='1' {{$role->is_create == 1 ? 'checked=checked' : ''}}>
                                                    <span></span>
                                                    <br>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="m-checkbox m-checkbox--square">
                                                    <input type="checkbox" name='roles[{{$role->page}}][is_read]' value='1' {{$role->is_read == 1 ? 'checked=checked' : ''}}>
                                                    <span></span>
                                                    <br>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="m-checkbox m-checkbox--square">
                                                    <input type="checkbox" name='roles[{{$role->page}}][is_update]' value='1' {{$role->is_update == 1 ? 'checked=checked' : ''}}>
                                                    <span></span>
                                                    <br>
                                                </label>
                                            </td>
                                            <td>
                                                <label class="m-checkbox m-checkbox--square">
                                                    <input type="checkbox" name='roles[{{$role->page}}][is_delete]' value='1' {{$role->is_delete == 1 ? 'checked=checked' : ''}}>
                                                    <span></span>
                                                    <br>
                                                </label>
                                            </td>
                                        </tr>
                                        <?php array_push($names, $role->page);  ?>
                                      @endforeach

                                      <?php $pages = $pageObject->whereNotIn('name', $names)->get() ?>
                                      @foreach($pages as $page)
                                        <tr>
                                            <td>{{$page->name}}</td>
                                            <td>
                                                <label class="m-checkbox m-checkbox--square">
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
                        <input type="text" name="name" class="form-control m-input m-input--square" value="{{$permission->name}}" id="name" placeholder="{{trans('lang.name')}}">
                                <div class="form-control-feedback" id="nameMessage"></div>
                            </div>
                            <div class="form-group m-form__group">
                                <div class="m-form__actions">
                                    <button type="submit" id="update" class="btn btn-success">{{trans('lang.update')}}</button>
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
						$('#update').addClass('m-loader m-loader--right m-loader--light').attr('disabled','disabled');
						$('.form-control-feedback').text('');
						$(".form-group").removeClass("has-danger");
						$('#successMessage').hide();
                    }, 

                    success : function(){
                        $("html, body").animate({ scrollTop: 0 }, "slow");
						$('#successMessage').show();
						$('#update').prop('disabled', false);
						$('#update').removeClass('m-loader m-loader--right m-loader--light');
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
                        
						$('#update').prop('disabled', false);
						$('#update').removeClass('m-loader m-loader--right m-loader--light');
                    }
                });
            })
        })   
        
    </script>
@endsection