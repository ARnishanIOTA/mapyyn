@extends('layouts.master')

@section('title') {{ trans('lang.chat-details') }}  @endsection

@section('content')

								<div class="m-portlet__body">
									@if(session('success'))
										<div class="alert alert-success" id="successMessage" role="alert"><strong>{{trans('lang.done')}}</strong>
											{{session('success')}}
										</div>
									@endif
									<div class="m-widget3">
											<div style="display:none" class="alert alert-success" id="successMessage" role="alert"><strong>{{trans('lang.done')}}</strong>
												{{ trans('lang.reply-message') }}
											</div>
										@foreach ($messages as $message)
											<div class="m-widget3__item">
												<div class="m-widget3__header">
													<div class="m-widget3__user-img">
														@if($message->type == 3)
															<?php $image = auth()->user()->image != null ? auth()->user()->image : 'default.jpg'?>
															<img class="m-widget3__img" src='{{asset("uploads/$image")}}' alt="">
														@else
															<?php $image = optional($chat->provider)->logo != null ? optional($chat->provider)->logo : 'default.png'?>
															<img class="m-widget3__img" src='{{asset("uploads/$image")}}' alt="">
														@endif
													</div>
													<div class="m-widget3__info">
														<span class="m-widget3__username">
                                                            @if($message->type == 3)
                                                                {{auth()->user()->name}}
                                                            @else
                                                                {{optional($chat->provider)->first_name . ' ' . optional($chat->provider)->last_name}}
                                                            @endif
														</span>
														<br>
														<span class="m-widget3__time">
															{{$message->created_at}}
														</span>
													</div>
												</div>
												<div class="m-widget3__body">
													<p class="m-widget3__text">
														{{$message->message}}
													</p>
												</div>
											</div>
										@endforeach

									</div>
									
								<!--begin::Form-->
								<form class="m-form m-form--fit m-form--label-align-right m-form--state" method="POST" action="{{route('admin-add-message', ['id' => $provider->id])}}" id="modelForm">
									@csrf
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
										</div>
									</div>
								</form>
								<!--end::Form-->
							<!--end::Portlet-->
						</div>
@endsection


@section('js')
<script src="{{asset('assets/js/lity.min.js')}}"></script>
	<script>
		$(document).ready(function(){

			$('#close').click(function(){
				$('#close').addClass('m-loader m-loader--right m-loader--light').attr('disabled','disabled');
			})
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

					success: function(){
						$("html, body").animate({ scrollTop: 0 }, "slow");
						$('#successMessage').show();
						$('#create').prop('disabled', false);
						$('#create').removeClass('m-loader m-loader--right m-loader--light');
						$('#reset').click();

						setTimeout(function(){
							location.reload(true);
						},1500)

					},

					error: function(response){
						error = response.responseJSON.errors;

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