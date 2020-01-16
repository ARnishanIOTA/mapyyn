@extends('layouts.master')

@section('title') {{ trans('lang.c.reate-chat') }}  @endsection

@section('content')

								<div class="m-portlet__body">
									<div style="display:none" class="alert alert-success" id="successMessage" role="alert"><strong>{{trans('lang.done')}}</strong>
                                        {{ trans('lang.start-message') }}
                                    </div>

									
								<!--end:: Widgets/Support chats -->
								<!--begin::Form-->
								<form class="m-form m-form--fit m-form--label-align-right m-form--state" method="POST" action="{{route('providers_chat_create')}}" id="modelForm">
									
									@csrf
									<div class="form-group m-form__group" id="client_idGroup">
										<label for="client_id">{{ trans('lang.clients') }}</label>
										<select name="client_id" class="form-control m-select2" style="width:100%" id="m_select2_2">
											<option></option>
											@foreach ($clients as $client)
												<option value="{{$client->id}}">{{ucfirst($client->name)}}</option>
											@endforeach
										</select>
										<div class="form-control-feedback" id="client_idMessage"></div>
										
									</div>
                                    <div class="m-portlet__body">
										<div class="form-group m-form__group" id="messageGroup">
											<label for="message">{{ trans('lang.message') }}</label>
											<textarea rows="10" name="message" class="form-control m-input m-input--square" id="message" placeholder="{{ trans('lang.message') }}"></textarea>
											<div class="form-control-feedback" id="messageMessage"></div>
										</div>
									</div>
				<br>
									<div class="m-portlet__foot m-portlet__foot--fit">
										<div class="m-form__actions">
											<button type="submit" class="btn btn-success" id="create">
												<span><i class="fa fa-calendar-check-o"></i><span>&nbsp;{{ trans('lang.create') }}</span></span>
                                            </button>
                                            <button id="reset" type="reset" style="display:none"></button>
										</div>
									</div>
								</form>
								<!--end::Form-->
							<!--end::Portlet-->
						</div>
@endsection


@section('js')
<script src="{{asset('assets/js/lity.min.js')}}"></script>
<script src="{{asset('assets/js/select2.js')}}"></script>
	<script>
		$(document).ready(function(){
			$('#modelForm').submit(function(e){
				e.preventDefault();
				url = $(this).attr('action');

				$.ajax({
					type : 'POST',
                    url : url,
                    data : new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
					beforeSend: function(){
						$('#create').addClass('m-loader m-loader--right m-loader--light').attr('disabled','disabled');
						$('.form-control-feedback').text('');
						$(".form-group").removeClass("has-danger");
						$('#successMessage').hide();
					},

					success: function(response){
						if(response == 'exists'){
							$('#client_idGroup').addClass('has-danger');
							$('#client_idMessage').text('chat already exists');
							$('#create').prop('disabled', false);
							$('#create').removeClass('m-loader m-loader--right m-loader--light');
							return ;
						}
						$("html, body").animate({ scrollTop: 0 }, "slow");
						$('#successMessage').show();
						$('#create').prop('disabled', false);
						$('#create').removeClass('m-loader m-loader--right m-loader--light');
						$('#reset').click();

						setTimeout(function(){
							window.location.href = '{{route("providers_chat")}}';
						},1500)

					},

					error: function(response){
						error = response.responseJSON.errors;
						if('client_id' in error){
							$('#client_idGroup').addClass('has-danger');
							$('#client_idMessage').text(error.client_id);
						}

						if('message' in error){
							$('#messageGroup').addClass('has-danger');
							$('#messageMessage').text(error.message);
						}


						$('#create').prop('disabled', false);
						$('#create').removeClass('m-loader m-loader--right m-loader--light');
					}
				})
			})
		});
	</script>
@endsection

@section('js')
	<script src="{{asset('assets/js/lity.min.js')}}"></script>	
@endsection