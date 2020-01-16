@extends('layouts.front')


@section('title') {{$title}} @endsection

@section('content')
    <!-- start booking  -->
	<div class="booking ">
			<div class="container">
					
					@if ($type == 'faq')
						@foreach ($faqs as $faq)
						<?php 
							$title = 'title_'.LaravelLocalization::getCurrentLocale();  
							$description = 'description_'.LaravelLocalization::getCurrentLocale();
						?>
							<div class="row">
								<div class="col-md-12">
									<div class="booking-content">
											<h4 class="active text-center"> {{$faq->$title}} </h4>

										{{$faq->$description}}
									</div>
								</div>
							</div>
							<br>
						@endforeach
					@else
						<div class="row">
							<div class="col-md-12 mb-5">
								<h4 class="active text-center"> {{$title}} </h4>
							</div>
							<div class="col-md-12">
								<div class="booking-content">
									@if ($page == 'terms')
										{{$setting->terms}}
									@else
										{{$setting->about}}
									@endif
								</div>
							</div>
						</div>
					@endif
					
				</div>
			</div>
		</div>
		<!-- End booking  -->
@endsection
