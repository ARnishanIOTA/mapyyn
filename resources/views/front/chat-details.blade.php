@extends('layouts.front')


@section('title') {{ trans('lang.chat_details') }} @endsection

@section('content')

<!-- start content message -->
<div class="content-message">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					
					<div class="text-here">
						@foreach ($chat->messages->toArray() as $message)
							<div class="message-wite mt-5">
								<span class="user">
									@if ($message['type'] == 1)
										{{auth('clients')->user()->first_name . ' ' . auth('clients')->user()->last_name}}
									@elseif($message['type'] == 2)
										{{optional($chat->provider)->first_name . ' ' . optional($chat->provider)->last_name}}
									@else
										{{trans('lang.app_name')}}
									@endif
								</span>
								<small class="float-right mt-1"> {{$message['created_at']}} </small>
								<p class="mt-3">{{$message['message']}}</p>
							</div>
						@endforeach
						<form method="POST" action='{{route('client_store_chat', $chat->id)}}' class="multiForm">
							@csrf
							<div class="form-group mt-3">
							<textarea class="form-control" name="message" placeholder="{{trans('lang.chatting')}}" required rows="4"></textarea>
							</div>
							<div class="row">
								<div class="col-md-3 offset-md-9">
									<button class=" btn btn-success btn-block  "> {{ trans('lang.send') }}  </button>
								</div>
							</div>
						</form>
					</div>
				</div>	
			</div>
		</div>
	</div>		
	<!-- End content message -->

@endsection
