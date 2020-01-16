@extends('layouts.front')


@section('title') {{ trans('lang.my_profile') }} @endsection

@section('content')
    <!-- start booking  -->
	<div class="booking ">
			<div class="container">
				<div class="row">
					<div class="col-md-12 mb-5">
						<h4 class="active text-center"> {{ trans('lang.my_profile') }} </h4>
					</div>
					<div class="col-md-12">
						<div class="booking-content">
							<form method="POST" action="{{route('client_my_profile')}}" id="modelFormProfile">
								@csrf
								@method('PATCH')
								<div class="row">

									<div class="col-md-6">
										<div class="form-group mb-4">
											<input name="first_name" type="text" value="{{auth('clients')->user()->first_name}}" class="form-control" placeholder="{{ trans('lang.first_name') }}">
										</div>

										<div class="form-group mb-4">
											<input name="last_name" type="text" value="{{auth('clients')->user()->last_name}}" class="form-control" placeholder="{{ trans('lang.last_name') }}">
										</div>

										<div class="form-group mb-4">
											<div class="row">
												<div class="col-md-3">
														<input type="text" id="phone" class="form-control" value="{{auth('clients')->user()->code}}" disabled placeholder="{{trans('lang.code')}}">
														<input type="hidden" id="code" name="code"  value="{{auth('clients')->user()->code}}">
												</div>
												<div class="col-md-9">
													<input name="phone" type="text" value="{{auth('clients')->user()->phone}}" class="form-control" placeholder="{{ trans('lang.phone') }}">
												</div>
											</div>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group mb-4">
											<input name="email" type="text" value="{{auth('clients')->user()->email}}" class="form-control" placeholder="{{ trans('lang.email') }}">
										</div>

										<div class="form-group mb-4">
											<input name="password" type="password" class="form-control" placeholder="{{ trans('lang.password') }}">
										</div>
									</div>
								</div>
									
								<div class="row">
									<div class="col-6 col-md-4 offset-md-4 mt-5">
										<button class="btn btn-info btn-block" id="button">{{ trans('lang.update') }}</button>
									</div>
								</div>	
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End booking  -->
@endsection
@section('js')
<script>
	@if(LaravelLocalization::getCurrentLocale() == 'ar')
		successMessage = 'تم تحديث الملف الشخصي بنجاح';
	@else
		successMessage = 'Your profile has been updated';
	@endif
	$('#country').change(function(){
            code = $('option:selected', this).attr('code');
            $('#phone').val(code)
            $('#code').val(code)
		});
		
$(document).ready(function(){
    $('#modelFormProfile').submit(function(e){
        e.preventDefault();
        url = $(this).attr('action');
        data = $(this).serialize();
        $.ajax({
            type : 'POST',
            url : url,
            data : data,
            beforeSend : function(){
                $('#button').attr('disabled','disabled');
                $('#flash').fadeOut();
            },

            success : function(response){
                if(response.invalid != null){
                    swal("", invalidMessage + ' ' + response.invalid + ' ' + time, "error");
                    $('#button').prop('disabled', false);
                    return ;
                }
                swal("", successMessage, "success");
                $('#button').prop('disabled', false);
				$('#reset').click();
				setTimeout(function(){
					location.reload();
				}, 2000)
            },

            error : function(response){
                if(response.responseJSON.password != null){
                    $("#flash").find('div').remove();
                    $('#flash').append("<div class='alert alert-danger'><li>"+response.responseJSON.password+"</li></div>").fadeIn()
                    $('#button').prop('disabled', false);
                    setTimeout(function(){
                        $('#flash').fadeOut();
                    }, 4000)
                }else{
                    errors = response.responseJSON.errors;
                    $("#flash").find('div').remove();
                    $.each(errors, function(key, value) { 
                        $('#flash').append("<div class='alert alert-danger'><li>"+value+"</li></div>").fadeIn()
                    });
                    $('#button').prop('disabled', false);
                    setTimeout(function(){
                        $('#flash').fadeOut();
                    }, 4000) 
    
                    if(response.responseJSON.danger != null){
                        $('#flash').append("<div class='alert alert-danger'><li>"+response.responseJSON.danger+"</li></div>").fadeIn()
                        $('#button').prop('disabled', false);
                        setTimeout(function(){
                            $('#flash').fadeOut();
                        }, 4000)
                    }
                }
                
            }
        });
    })
})  
</script>
@endsection