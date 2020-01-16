@extends('layouts.master')


@section('title') {{ trans('lang.create-offer') }} @endsection

@section('content')
@if(LaravelLocalization::getCurrentLocale() == 'ar')
<?php $name = 'name_ar' ?>
@else
<?php $name = 'name_en' ?>
@endif
<!-- BEGIN: Subheader -->
					<div class="m-subheader ">
						<div class="d-flex align-items-center">
							<div class="mr-auto">
								<h3 class="m-subheader__title ">
									{{ trans('lang.create-offer') }}
								</h3>
							</div>
						</div>
					</div>
					<!-- END: Subheader -->
					<div class="m-content">
						<!--begin::Form-->
						<form class="m-form m-form--fit m-form--label-align-right m-form--state" method="POST" action="{{route('create-offer')}}" id="modelForm" enctype="multipart/form-data">
							<div style="display:none" class="alert alert-success" id="successMessage" role="alert"><strong>{{ trans('lang.wellDone') }}</strong>
								{{ trans('lang.successMessage') }}
							</div>
							@csrf
							<div class="m-portlet__body">
								<div class="form-group m-form__group" id="category_idGroup">
									<label for="category_id">{{ trans('lang.categories') }}</label>
									<select name="category_id" class="form-control" id="category_id">
                                        <option value="">{{ trans('lang.select') }}</option>
                                        @foreach ($categories as $category)
                                            @if ($category == 1)
										        <option value="1">{{ trans('lang.entertainment') }}</option>
                                            @elseif($category == 2)
                                                <option value="2">{{ trans('lang.educational') }}</option>
                                            @elseif($category == 3)   
                                                <option value="3">{{ trans('lang.sport') }}</option>
                                            @else
                                                <option value="4">{{ trans('lang.medical') }}</option>
                                            @endif
                                        @endforeach
										
									</select>
									<div class="form-control-feedback" id="category_idMessage"></div>
								</div>

                                
                                @if(in_array(3, $categories))
                                    <div style="display:none" id="sport">
                                        <div class="form-group m-form__group" id="league_idGroup">
                                            <label for="league_id">{{ trans('lang.leagues') }}</label>
                                            <select name="league_id" class="form-control m-select2" style="width:100%" id="m_select2_1">
                                                <option></option>
                                                @foreach ($leagues as $league)
                                                    <option value="{{$league['id']}}">{{ucfirst($league['name'])}}</option>
                                                @endforeach
                                            </select>
                                            <div class="form-control-feedback" id="league_idMessage"></div>
                                        </div>
                                        
                                        <div class="form-group m-form__group" id="event_idGroup" style="display:none">
                                            <label for="event_id">{{ trans('lang.events') }}</label>
                                            <select name="event_id" class="form-control m-select2" style="width:100%" id="m_select2_4"></select>
                                            <div class="form-control-feedback" id="event_idMessage"></div>
                                        </div>
                                    </div>


                                    <div style="display:none" id="other">
                                        


                                        {{--  <div class="form-group m-form__group" id="event_dateGroup">
                                            <label for="event_date">{{ trans('lang.event_date') }}</label>
                                            <input type="date" placeholder="{{ trans('lang.event_date') }}" name="event_date"  class="form-control">
                                            <div class="form-control-feedback" id="event_dateMessage"></div>
                                        </div>  --}}
                                    </div>
                                @endif
                                
                                <div class="form-group m-form__group" id="country_idGroup">
                                    <label for="country_id">{{ trans('lang.countries') }}</label>
                                    <select name="country_id" class="form-control m-select2" style="width:100%" id="m_select2_5">
                                        <option></option>
                                        @foreach ($countries as $country)
                                            <option value="{{$country->id}}" data-code="{{$country->sortname}}">{{ucfirst($country->name)}}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-control-feedback" id="country_idMessage"></div>
								</div>
								
								{{--  <div class="form-group m-form__group" id="city_idGroup" style="display:none">
									<label for="city_id">{{ trans('lang.cities') }}</label>
									<select name="city_id" class="form-control m-select2" style="width:100%" id="m_select2_3"></select>
									<div class="form-control-feedback" id="city_idMessage"></div>
								</div>  --}}

                                <div id="mapToggle" style="display:none">
                                    <div class="form-group m-form__group" id="locationGroup">
                                        <label for="location">{{ trans('lang.location') }}</label>
                                        <input type="text" placeholder="{{ trans('lang.location') }}" name="location" id="pac-input"  class="form-control">
                                        <input type="hidden" name="lat" id="lat">
                                        <input type="hidden" name="lng" id="lng">
                                        <input type="hidden" name="city_id" id="city_id">
                                        <div class="form-control-feedback" id="location-error"></div>
                                    </div>
                                    
                                    <div class="form-group m-form__group">
                                        <div id="map" style="height: 500px"></div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group" id="description_arGroup">
                                    <label for="description_ar">{{ trans('lang.description_ar') }}</label>
                                    <textarea name="description_ar" class="form-control" rows="10" placeholder="{{ trans('lang.description_ar') }}"></textarea>
                                    <div class="form-control-feedback" id="description_arMessage"></div>
                                </div>

                                <div class="form-group m-form__group" id="description_enGroup">
                                    <label for="description_en">{{ trans('lang.description_en') }}</label>
                                    <textarea name="description_en" class="form-control" rows="10" placeholder="{{ trans('lang.description_en') }}"></textarea>
                                    <div class="form-control-feedback" id="description_enMessage"></div>
                                </div>

                                <div class="form-group m-form__group" id="hotel_levelGroup">
                                    <label for="hotel_level">{{ trans('lang.hotel_level') }}</label>
                                    <input type="number" min="1" placeholder="{{ trans('lang.hotel_level') }}" name="hotel_level"  class="form-control">
                                    <div class="form-control-feedback" id="hotel_levelMessage"></div>
                                </div>

                                <div class="form-group m-form__group" id="end_atGroup">
                                    <label for="end_at">{{ trans('lang.end_at') }}</label>
                                    <input type="text" id="end_at" placeholder="{{ trans('lang.end_at') }}" name="end_at"  class="form-control">
                                    <div class="form-control-feedback" id="end_atMessage"></div>
                                </div>

                                 <div class="form-group m-form__group" id="fromGroup">
                                    <label for="from">{{ trans('lang.from') }}</label>
                                    <input type="text" id="from" placeholder="{{ trans('lang.from') }}" name="from"  class="form-control">
                                    <div class="form-control-feedback" id="fromMessage"></div>
                                </div>

                                <div class="form-group m-form__group" id="toGroup">
                                    <label for="to">{{ trans('lang.to') }}</label>
                                    <input type="text" id="to" placeholder="{{ trans('lang.to') }}" name="to"  class="form-control">
                                    <div class="form-control-feedback" id="toMessage"></div>
                                </div> 

                                <div class="form-group m-form__group" id="daysGroup">
                                    <label for="days">{{ trans('lang.days') }}</label>
                                    <input type="number" min="1" id="days" placeholder="{{ trans('lang.days') }}" name="days"  class="form-control">
                                    <div class="form-control-feedback" id="daysMessage"></div>
                                </div> 

                                <div class="form-group m-form__group" id="personsGroup">
                                    <label for="persons">{{ trans('lang.persons') }}</label>
                                    <input type="number" min="1" placeholder="{{ trans('lang.persons') }}" name="persons"  class="form-control">
                                    <div class="form-control-feedback" id="personsMessage"></div>
                                </div>

                                <div class="form-group m-form__group" id="transportGroup">
                                    <label for="transport">{{ trans('lang.transport') }}</label>
                                    <select name="transport" class="form-control" style="width:100%" id="m_select2_2">
                                        <option value="">{{ trans('lang.select') }}</option>
                                        <option value="home">{{ trans('lang.home') }}</option>
                                        <option value="way">{{ trans('lang.way') }}</option>
                                        <option value="both">{{ trans('lang.both') }}</option>
                                    </select>
                                    <div class="form-control-feedback" id="transportMessage"></div>
                                </div>


                                <div class="form-group m-form__group" id="currencyGroup">
                                    <label for="currency">{{ trans('lang.currency') }}</label>
                                    <select name="currency" class="form-control" style="width:100%">
                                        <option value="">{{ trans('lang.select') }}</option>
                                        <option value="sar">{{ trans('lang.sar') }}</option>
                                        <option value="dollar">{{ trans('lang.dollar') }}</option>
                                    </select>
                                    <div class="form-control-feedback" id="currencyMessage"></div>
                                </div>

                                <div class="form-group m-form__group" id="imagesGroup">
                                    <label for="images">{{ trans('lang.images') }}</label>
                                    <input type="file" multiple accept="image/*" max="5" name="images[]"  class="form-control">
                                    <div class="form-control-feedback" id="imagesMessage"></div>
                                </div>

                                <div class="form-group m-form__group" id="priceGroup">
                                    <label for="price">{{ trans('lang.price') }}</label>
                                    <input type="text" placeholder="{{ trans('lang.price') }}" name="price"  class="form-control">
                                    <div class="form-control-feedback" id="priceMessage"></div>
                                </div>

							</div>
							<div class="m-portlet__foot m-portlet__foot--fit">
								<div class="m-form__actions">
									<button type="submit" class="btn btn-success" id="create">
										<span><i class="fa fa-calendar-check-o"></i><span>&nbsp;{{ trans('lang.create') }}</span></span>
                                    </button>
                                    
                                    <button type="reset" id="reset" style="display:none"></button>
								</div>
							</div>
						</form>
								<!--end::Form-->
							<!--end::Portlet-->
                    </div>
@endsection


@section('js')
<script src="{{asset('js/date-time-picker.min.js')}}"></script>
<script src="{{asset('assets/js/select2.js')}}"></script>
	<script>

		$(document).ready(function(){
            $('#end_at').dateTimePicker();
            $('#from').dateTimePicker();
            $('#to').dateTimePicker();
            
			$('#category_id').change(function(){
				id = $(this).val();
				if(id == 3){
					$('#sport').fadeIn();
					$('#other').fadeOut();
				}else{
					$('#sport').fadeOut();
					$('#other').fadeIn();
                    $('#event_idGroup').fadeOut();
				}
			});
			
            $('#m_select2_1').change(function(){
                $('body').addClass('m-page--loading');
                $("#m_select2_4").find('option').remove();
                id = $(this).val()
                $.ajax({
                    type : 'GET',
                    url : '{{url("providers/get-events")}}' + '/' +id,
                    success: function (response) {
                        $('body').removeClass('m-page--loading');
                        $('#event_idGroup').fadeIn();
                        $('#m_select2_4').append($("<option></option>")); 
                        $.each(response, function(key, value) {
                            $('#m_select2_4').append($("<option></option>").attr("value",value.id).text(value.name + ' - ' + value.date)); 
                        });
                    }, 
                });
            })

			// $('#m_select2_5').change(function(){
            //     $('body').addClass('m-page--loading');
            //     $("#m_select2_3").find('option').remove();
            //     id = $(this).val()
            //     $.ajax({
            //         type : 'GET',
            //         url : '{{url("providers/get-cities")}}' + '/' +id,
            //         success: function (response) {
            //             $('body').removeClass('m-page--loading');
            //             $('#city_idGroup').fadeIn();
            //             $('#m_select2_3').append($("<option></option>")); 
            //             $.each(response, function(key, value) {
            //                 $('#m_select2_3').append($("<option></option>").attr("value",value.id).text(value.name)); 
            //             });
            //         }, 
            //     });
            // })
			
			$('#modelForm').submit(function(e){
				e.preventDefault();
				url = $(this).attr('action');

				$.ajax({
					type : 'POST',
					url: url,
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
						// if(response == 'invalid'){
						// 	$('#fileGroup').addClass('has-danger');
						// 	$('#fileMessage').text('format error.. please enter valid file');
						// 	$('#create').prop('disabled', false);
						// 	$('#create').removeClass('m-loader m-loader--right m-loader--light');
						// 	$('#reset').click();
						// 	return;
						// }

                        
						// if(response == 'groupInvalid'){
						// 	$('#group_idGroup').addClass('has-danger');
						// 	$('#group_idMessage').text('Invalid Group');
						// 	$('#create').prop('disabled', false);
						// 	$('#create').removeClass('m-loader m-loader--right m-loader--light');
						// 	// $('#reset').click();
						// 	return;
						// }

                        if(response == 'invalid_address'){
							$('#locationGroup').addClass('has-danger');
							$('#location-error').text('Invalid Address');
							$('#create').prop('disabled', false);
							$('#create').removeClass('m-loader m-loader--right m-loader--light');
							// $('#reset').click();
							return;
						}

						$("html, body").animate({ scrollTop: 0 }, "slow");
						$('#successMessage').show();
						$('#create').prop('disabled', false);
						$('#create').removeClass('m-loader m-loader--right m-loader--light');
						// $('#reset').click();

					},

					error: function(response){
						error = response.responseJSON.errors;
						if('type' in error){
							$('#typeGroup').addClass('has-danger');
							$('#typeMessage').text(error.type);
						}

						if('category_id' in error){
							$('#category_idGroup').addClass('has-danger');
							$('#category_idMessage').text(error.category_id);
						}


                        if('currency' in error){
							$('#currencyGroup').addClass('has-danger');
							$('#currencyMessage').text(error.currency);
						}


						if('country_id' in error){
							$('#country_idGroup').addClass('has-danger');
							$('#country_idMessage').text(error.country_id);
						}

						// if('city_id' in error){
						// 	$('#city_idGroup').addClass('has-danger');
						// 	$('#city_idMessage').text(error.city_id);
						// }

                        if('event_date' in error){
							$('#event_dateGroup').addClass('has-danger');
							$('#event_dateMessage').text(error.event_date);
						}


                        if('end_at' in error){
							$('#end_atGroup').addClass('has-danger');
							$('#end_atMessage').text(error.end_at);
						}

						if('league_id' in error){
							$('#league_idGroup').addClass('has-danger');
							$('#league_idMessage').text(error.league_id);
						}

						if('event_id' in error){
							$('#event_idGroup').addClass('has-danger');
							$('#event_idMessage').text(error.event_id);
						}

						// if('lat' in error){
						// 	$('#locationGroup').addClass('has-danger');
						// 	$('#locationMessage').text("{{trans('lang.latMessage')}}");
						// }

                        // if('lng' in error){
						// 	$('#locationGroup').addClass('has-danger');
						// 	$('#locationMessage').text("{{trans('lang.lngMessage')}}");
						// }

						if('description_ar' in error){
							$('#description_arGroup').addClass('has-danger');
							$('#description_arMessage').text(error.description_ar);
						}

                        if('description_en' in error){
							$('#description_enGroup').addClass('has-danger');
							$('#description_enMessage').text(error.description_en);
						}


						if('hotel_level' in error){
							$('#hotel_levelGroup').addClass('has-danger');
							$('#hotel_levelMessage').text(error.hotel_level);
						}


                        if('persons' in error){
							$('#personsGroup').addClass('has-danger');
							$('#personsMessage').text(error.persons);
						}

                        if('price' in error){
							$('#priceGroup').addClass('has-danger');
							$('#priceMessage').text(error.price);
						}

                        if('from' in error){
							$('#fromGroup').addClass('has-danger');
							$('#fromMessage').text(error.from);
						}

                        if('location' in error){
							$('#locationGroup').addClass('has-danger');
							$('#locationMessage').text(error.location);
						}

                        if('lat' in error){
							$('#locationGroup').addClass('has-danger');
							$('#locationMessage').text('{{trans("lang.locationMessage")}}');
						}

                        if('lng' in error){
							$('#locationGroup').addClass('has-danger');
							$('#locationMessage').text('{{trans("lang.locationMessage")}}');
						}

                        if('to' in error){
							$('#toGroup').addClass('has-danger');
							$('#toMessage').text(error.to);
						}

                        if('transport' in error){
							$('#transportGroup').addClass('has-danger');
							$('#transportMessage').text(error.transport);
						}

                        if('images' in error){
							$('#imagesGroup').addClass('has-danger');
							$('#imagesMessage').text(error.images);
						}
							
						$('#create').prop('disabled', false);
						$('#create').removeClass('m-loader m-loader--right m-loader--light');
					}
				})
			})
		});


        function initMap() {
            var centerCoordinates = new google.maps.LatLng(24.599032454662108, 46.7893363769565);
            var card = document.getElementById('pac-card');
            var input = document.getElementById('pac-input');

            var map = new google.maps.Map(document.getElementById('map'), {
                center: centerCoordinates,
                zoom: 4
            });

            var marker = new google.maps.Marker({
                map: map
            });
                var options = {};
                $('#m_select2_5').change(function(){
                    $('#mapToggle').show();
                    country = $('#m_select2_5').find(':selected').data('code');
                    var options = {
                        types: ['(cities)'],
                        componentRestrictions: {country: country}
                    };
                    

                    var autocomplete = new google.maps.places.Autocomplete(input, options);
                    var infowindow = new google.maps.InfoWindow();
                    
                    

                    autocomplete.addListener('place_changed', function() {
                    document.getElementById("location-error").style.display = 'none';
                    infowindow.close();
                    marker.setVisible(false);
                        var place = autocomplete.getPlace();
                        if (!place.geometry) {
                            document.getElementById("location-error").style.display = 'inline-block';
                            document.getElementById("location-error").innerHTML = "Cannot Locate '" + input.value + "' on map";
                            return;
                        }
                        document.getElementById('lat').value = place.geometry.location.lat(); //latitude
                        document.getElementById('lng').value = place.geometry.location.lng(); //longitude
                        document.getElementById('city_id').value = place.place_id; // place id

                        map.fitBounds(place.geometry.viewport);
                        marker.setPosition(place.geometry.location);
                        marker.setVisible(true);
                            
                        // infowindowContent.children['place-icon'].src = place.icon;
                        // infowindowContent.children['place-name'].textContent = place.name;
                        // infowindowContent.children['place-address'].textContent = input.value;
                        // infowindow.open(map, marker);
                    });
                })

                
        //Listen for any clicks on the map.
        // google.maps.event.addListener(map, 'click', function(event) {                
        //     //Get the location that the user clicked.
            
        //     var clickedLocation = event.latLng;
            
        //     //If the marker hasn't been added.
        //     if(marker === false){
        //         //Create the marker.
        //         marker = new google.maps.Marker({
        //             position: clickedLocation,
        //             map: map,
        //             draggable: true //make it draggable
        //         });

               
        //         //Listen for drag events!
        //         google.maps.event.addListener(marker, 'dragend', function(event){
        //             markerLocation();
        //         });
        //     } else{
        //         //Marker has already been added, so just change its location.
        //         marker.setPosition(clickedLocation);
        //     }
        //     //Get the marker's location.
        //     document.getElementById('lat').value = event.latLng.lat(); //latitude
        //     document.getElementById('lng').value = event.latLng.lng(); //longitude
        //     markerLocation();
           
        // });
    }

    // function markerLocation(){
    //     //Get location.
    //     var currentLocation = marker.getPosition();
    //     //Add lat and lng values to a field that we can save.
    // }
    </script>
    @if (LaravelLocalization::getCurrentLocale() == 'ar')
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCt9ApcngmV7Zj_XR8h5hoznS1EaYuPLhI&libraries=places&callback=initMap&language=ar" async defer></script>
    @else
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCt9ApcngmV7Zj_XR8h5hoznS1EaYuPLhI&libraries=places&callback=initMap&language=en" async defer></script>
    @endif
@endsection