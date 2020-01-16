@extends('layouts.master')

@section('title') {{ trans('lang.ticket-details') }}  @endsection

@section('content')
<!-- BEGIN: Subheader -->
					<div class="m-subheader ">
						<div class="d-flex align-items-center">
							<div class="mr-auto">
								<h3 class="m-subheader__title ">
									{{ trans('lang.ticket') }} : {{$ticket->title}}
								</h3>
							</div>
						</div>
					</div>
				<br>
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
										@foreach ($ticket->comments as $comment)
											<div class="m-widget3__item">
												<div class="m-widget3__header">
													<div class="m-widget3__user-img">
														@if($comment->user_id != null)
															<img class="m-widget3__img" src='{{asset("uploads/auth()->user()->image")}}' alt="">
														@elseif($comment->provider_id != null)
															<?php $image = $comment->provider->image?>
															<img class="m-widget3__img" src='{{asset("uploads/$image")}}' alt="">
														@else
															<?php $image = $comment->client->image?>
															<img class="m-widget3__img" src='{{asset("uploads/$image")}}' alt="">
														@endif
													</div>
													<div class="m-widget3__info">
														<span class="m-widget3__username">
																@if($comment->user_id != null)
																	{{auth()->user()->name}}
																@elseif($comment->provider_id != null)
																	{{$comment->provider->first_name . ' ' . $comment->provider->last_name}}
																@else
																	{{$comment->client->first_name . ' ' . $comment->client->last_name}}
																@endif
														</span>
														<br>
														<span class="m-widget3__time">
															{{$comment->created_at}}
														</span>
													</div>
												</div>
												<div class="m-widget3__body">
													<p class="m-widget3__text">
														{{$comment->comment}}
													</p>
												</div>
											</div>
										@endforeach

									</div>
									<?php $url = url()->current(); ?>
									@if (strpos($url, 'backend'))
										<?php $route = 'update-user-ticket';?>
									@elseif(strpos($url, 'providers'))
										<?php $route = 'update-providers-ticket';?>
									@else
										<?php $route = 'update-clients-ticket';?>
									@endif
							@if($ticket->status != 'closed')
								<!--end:: Widgets/Support Tickets -->
								<!--begin::Form-->
								<form class="m-form m-form--fit m-form--label-align-right m-form--state" method="POST" action="{{route('update-user-ticket', ['id' => $ticket->id])}}" id="modelForm">
									
									@csrf
                                    @method('PATCH')
                                    <div class="m-portlet__body">
										<div class="form-group m-form__group" id="commentGroup">
											<label for="comment">{{ trans('lang.comment') }}</label>
											<textarea rows="10" name="comment" class="form-control m-input m-input--square" id="comment" placeholder="{{ trans('lang.comment') }}"></textarea>
											<div class="form-control-feedback" id="commentMessage"></div>
										</div>
									</div>
				<br>
									
									@if(auth()->guard()->check())
										<div class="m-portlet__body">
											<div class="form-group m-form__group" id="statusGroup">
												<label for="status">Status</label>
												<select name="status" class="form-control m-input m-input--square" id="status">
													<option value="opened" {{$ticket->status == 'opened' ? 'selected' : ''}}>Open</option>
													<option value="closed" {{$ticket->status == 'closed' ? 'selected' : ''}}>Closed</option>
												</select>
												<div class="form-control-feedback" id="statusMessage"></div>
											</div>
										</div>
									@endif
									<br>

									<div class="m-portlet__foot m-portlet__foot--fit">
										<div class="m-form__actions">
											<button type="submit" class="btn btn-success" id="create">
												<span><i class="fa fa-calendar-check-o"></i><span>&nbsp;Create</span></span>
											</button>
										</div>
									</div>
								</form>
								<!--end::Form-->
							@endif
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
						if('title' in error){
							$('#titleGroup').addClass('has-danger');
							$('#titleMessage').text(error.title);
						}

						if('comment' in error){
							$('#commentGroup').addClass('has-danger');
							$('#commentMessage').text(error.comment);
						}

						if('status' in error){
							$('#statusGroup').addClass('has-danger');
							$('#statusMessage').text(error.status);
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