@extends('layouts.front')


@section('title') {{ trans('lang.chat') }} @endsection

@section('content')
<!-- Start my-offers -->
<div class="details-offer my-offers message">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
			<h6> <a href="{{url('/')}}"> {{ trans('lang.main') }} </a> / <a href="#" class="active"> {{ trans('lang.messages') }} </a>
				</h6>
			</div>
			<div class="col-md-12 mt-4 mb-3">
				<div class="col-md-6">
					<h5 class="mt-5"> {{ trans('lang.messages') }} </h5>
				</div>
			</div>
			<div class="col-md-12">
				<div class="tab-content" id="pills-tabContent">
					<!--  first Tap -->
					<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
						<div class="col-md-12">
							<div class="desc-offer">
								@foreach ($chats['data'] as $chat)
									@if (count($chat['messages']) > 0)
										<div class="item-message pt-3 pb-3">
											<div class="row">
													<div class="col-md-4 border-right">
														<div class="first-desc ">
															<?php  $name = $chat['offer_type'] == 1 ? $chat['offer_id'] : $chat['request_offer_id'] ?>
														<div class="w-100 mt-3"><a style="color:white" href='{{url("chat/details/").'/'.$chat['id']}}'>
															{{$chat['provider_id'] == null ? trans('lang.app_name') : $chat['provider']['first_name']. ' ' .$chat['provider']['last_name']}} - {{ trans('lang.order_id') }}  {{$name}}
														 </a></div>
														</div>
													</div>
													<div class="col-md-6 text-center">
													<a href="{{url("chat/details/").'/'.$chat['id']}}">
														<div class="three-desc">
														<p>{{str_limit($chat['messages'][0]['message'], 200, '...')}}</p>
														</div>
													</a>
													</div>
												<div class="col-md-2 ">
													<div class="tow-desc text-center ">
														<h6 class="text-center"> {{ trans('lang.last_reply') }} </h6>
														<span>{{ date('Y-m-d',strtotime($chat['messages'][0]['created_at'])) }}</span>

														{{-- <button class="btn btn-danger btn-block" onclick="window.location.href='{{url('chat/delete/'.$chat['id'])}}'"> {{ trans('lang.remove') }} </button> --}}
													</div>
												</div>
											</div>
										</div>
										<!--  -->
										<hr>
									@endif
								@endforeach
								<div class="col-md-4 offset-md-4 mt-5">
										<nav aria-label="Page navigation example">
											<ul class="pagination justify-content-center">
												@if (($chats['last_page'] - $chats['current_page']) == 0)
													@if ($chats['current_page'] == 1)
														<li class="page-item disabled">
															<a class="page-link" href='#'>{{ trans('lang.next') }}</a>
														</li>
														<li class="page-item disabled">
															<a class="page-link" href='#'>{{ trans('lang.previous') }}</a>
														</li>
													@else
														<li class="page-item disabled">
															<a class="page-link" href='#'>{{ trans('lang.next') }}</a>
														</li>
														<a class="page-link" href='?page={{$chats['current_page'] - 1}}'>{{ trans('lang.previous') }}</a>
													@endif
													
												@elseif(($chats['current_page'] - 1) == 0)
														<a class="page-link" href='?page={{$chats['current_page'] + 1}}'>{{ trans('lang.next') }}</a>
														<li class="page-item disabled">
															<a class="page-link" href='#'>{{ trans('lang.previous') }}</a>
														</li>
												@else
													<a class="page-link" href='?page={{$chats['current_page'] + 1}}'>{{ trans('lang.next') }}</a>
													<a class="page-link" href='?page={{$chats['current_page'] - 1}}'>{{ trans('lang.previous') }}</a>
												@endif
												
											</ul>
										</nav>
									</div>
								</div>
								<!-- End Tow Tap -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


@endsection
