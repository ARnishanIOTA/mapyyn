@extends('layouts.front')


@section('title') {{ trans('lang.notifications') }} @endsection

@section('content')
<div class="details-offer alert ">
		<div class="container">
			<div class="row">
				<div class="col-md-12 mb-4">
					<div class="row">
						<div class="col-md-6">
						<h6> <a href="{{url('/')}}"> {{ trans('lang.main') }} </a> / <a href="#" class="active"> {{ trans('lang.notifications') }} </a> </h6>
						</div>
					</div>
					<div class="row">
						@if ($notifications->count() > 0)
							<div class="col-md-2">
								<button class="btn btn-danger btn-block" onclick="window.location.href='{{url('notifications/delete')}}'">{{ trans('lang.delete') }}</button>
							</div>
						@endif
					</div>
				</div>

				
				@foreach ($notifications as $notification)
					<!-- start item -->
				<div class="col-md-12">
						<div class="desc-offer">
							<div class="row">
								<div class="col-md-8 border-right">
									<div class="first-desc pb-4">
										<div class="row">
											<div class="col-2">
												<span><img class="img-fluid" src="{{asset('img/flag.png')}}" alt="flag"> </span>
											</div>
											<div class="col-10">
												<h5 class="mt-4 ">{{$notification->message}}</h5>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4 text-center">
									<div class="three-desc">
										@if ($notification->type == 'price')
											<button class="btn btn-success btn-block" onclick="window.location.href='{{url('my-offers')}}'">{{ trans('lang.details') }}</button>
											<button class="btn btn-danger btn-block" onclick="window.location.href='{{url('notifications/destroy/').'/'.$notification->id }}'">{{ trans('lang.remove') }}</button>
										@elseif($notification->type == 'all')
											<button class="btn btn-danger btn-block" onclick="window.location.href='{{url('notifications/destroy/').'/'.$notification->id }}'">{{ trans('lang.remove') }}</button>
										@elseif($notification->type == 'payment_offer')
											<button class="btn btn-success btn-block" onclick="window.location.href='{{url('my-offers')}}'">{{ trans('lang.details') }}</button>
											<button class="btn btn-danger btn-block" onclick="window.location.href='{{url('notifications/destroy/').'/'.$notification->id }}'">{{ trans('lang.remove') }}</button>
										@elseif($notification->type == 'payment_request_offer')
											<button class="btn btn-success btn-block" onclick="window.location.href='{{url('my-offers')}}'">{{ trans('lang.details') }}</button>
											<button class="btn btn-danger btn-block" onclick="window.location.href='{{url('notifications/destroy/').'/'.$notification->id }}'">{{ trans('lang.remove') }}</button>
										@else
											<button class="btn btn-success btn-block" onclick="window.location.href='{{url('chat/details').'/'.$notification->chat_id }}'">{{ trans('lang.details') }}</button>
											<button class="btn btn-danger btn-block" onclick="window.location.href='{{url('notifications/destroy/').'/'.$notification->id }}'">{{ trans('lang.remove') }}</button>
										@endif
									</div>
								</div>
							</div>
						</div>
					</div>
				@endforeach

				<div class="col-md-4 offset-md-4 mt-5">
						<nav aria-label="Page navigation example">
							<ul class="pagination justify-content-center">
								@if (($notifications->lastPage() - $notifications->currentPage()) == 0)
									@if ($notifications->currentPage() == 1)
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
										<a class="page-link" href='?page={{$notifications->currentPage() - 1}}'>{{ trans('lang.previous') }}</a>
									@endif
									
								@elseif(($notifications->currentPage() - 1) == 0)
										<a class="page-link" href='?page={{$notifications->currentPage() + 1}}'>{{ trans('lang.next') }}</a>
										<li class="page-item disabled">
											<a class="page-link" href='#'>{{ trans('lang.previous') }}</a>
										</li>
								@else
									<a class="page-link" href='?page={{$notifications->currentPage() + 1}}'>{{ trans('lang.next') }}</a>
									<a class="page-link" href='?page={{$notifications->currentPage() - 1}}'>{{ trans('lang.previous') }}</a>
								@endif
								
							</ul>
						</nav>
					</div>
				</div>
				
				<!-- End item -->
			</div>
		</div>
	</div>
@endsection
