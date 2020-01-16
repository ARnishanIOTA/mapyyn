@extends('layouts.front')


@section('title') 404 @endsection

@section('content')
<div class="pageNotFound">
		<div class="container">
			<div class="row">
				<div class="col-md-8 offset-md-2 ">
					<img class="img-fluid" src="{{asset('img/404-error.gif')}}" alt="error 404">
				</div>
			</div>
		</div>
	</div>
@endsection
