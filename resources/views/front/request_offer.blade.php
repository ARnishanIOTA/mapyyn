@extends('layouts.front')

@section('title') {{ trans('lang.request_offer') }} @endsection

@section('content')

    <?php
    if(LaravelLocalization::getCurrentLocale() == 'ar'){
        $name = 'name_ar';
    }else{
        $name = 'name_en';
    }
    ?>

    <!-- start booking  -->
    <div class="booking ">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-5">
                    <h4 class="active text-center">{{ trans('lang.book_your_trip') }}</h4>
                </div>
                <div class="col-md-12">
                    @if (session('danger'))
                        <div class="alert alert-danger">{{session('danger')}}</div>
                    @endif
                    <div class="booking-content">
                        <form action="{{route('client-request-offer')}}" method="POST" id="modelForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6  mb-4">
                                    <div class="custome-input">
                                        <select name="from_country" class="form-control p-3" id="from_country">
                                            <option selected value="" disabled>{{ trans('lang.from_country') }}</option>
                                            @foreach ($countries as $country)
                                                <option value="{{$country->id}}" data-code="{{$country->sortname}}">{{$country->$name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6  mb-4" id="mapToggle" style="display:none">
                                    <div class="custome-input">
                                        <label>{{ trans('lang.from_city') }}</label>
                                        <input type="text"  class="form-control p-3" id="fromm_city">
                                        <input type="hidden" name="from_city" class="form-control p-3" id="from_city">
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row">
                                <div class="col-md-6  mb-4">
                                    <div class="custome-input">
                                        <select name="to_country" class="form-control p-3" id="to_country">
                                            <option selected value="" disabled>{{ trans('lang.to_country') }}</option>
                                            @foreach ($countries as $country)
                                                <option value="{{$country->id}}" data-code="{{$country->sortname}}">{{$country->$name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6  mb-4" id="mapToggle2" style="display:none">
                                    <div class="custome-input">
                                        <label>{{ trans('lang.to_city') }}</label>
                                        <input type="text" class="form-control p-3" id="too_city">
                                        <input type="hidden" name="to_city" class="form-control p-3" id="to_city">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6  mb-4">
                                    <div class="custome-input">
                                        <label>{{ trans('lang.go_date') }}</label>
                                        <input type="text" name="go_date"  class="form-control p-3" id="go_date">
                                    </div>
                                </div>
                                <div class="col-md-6  mb-4">
                                    <div class="custome-input">
                                        <label>{{ trans('lang.back_date') }}</label>
                                        <input type="txt" name="back_date" class="form-control p-3" id="back_date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12  mb-4">
                                <div class="">
                                    {{ trans('lang.change_date') }} <br>
                                    <input value="yes" name="change_date" type="radio">&nbsp;{{ trans('lang.yes') }} &nbsp;&nbsp;&nbsp;
                                    <input value="no" name="change_date" type="radio">&nbsp;{{ trans('lang.no') }}&nbsp;&nbsp;&nbsp;
                                </div>
                            </div>
                            <!--  -->
                            <div class="row">
                                <div class="col-md-6  mb-4">
                                    <div class="custome-input">
                                        <select name="hotel_level" class="form-control p-3" id="">
                                            <option selected value="" disabled>{{ trans('lang.hotel_level') }}</option>
                                            <option value="1">1 {{ trans('lang.star') }}</option>
                                            <option value="2">2 {{ trans('lang.star') }}</option>
                                            <option value="3">3 {{ trans('lang.star') }}</option>
                                            <option value="4">4 {{ trans('lang.star') }}</option>
                                            <option value="5">5 {{ trans('lang.star') }}</option>
                                            <option value="6">6 {{ trans('lang.star') }}</option>
                                            <option value="7">7 {{ trans('lang.star') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6  mb-4">
                                    <div class="custome-input">
                                        <select name="category_id" class="form-control p-3" id="category_id">
                                            <option selected value="" disabled>{{ trans('lang.categories') }}</option>
                                            <option value="1">{{ trans('lang.entertainment') }}</option>
                                            <option value="2">{{ trans('lang.educational') }}</option>
                                            <option value="3">{{ trans('lang.sport') }}</option>
                                            <option value="4">{{ trans('lang.medical') }}</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div id="leagues" style="display:none">
                                <h5 class="font-wight-blod mx-4 my-4">{{ trans('lang.leagues') }}</h5>
                                <div class="row">
                                    <div class="col-md-12  mb-4">
                                        <div class="custome-input">
                                            <select name="league" class="form-control p-3" id="league">
                                                <option selected value="" disabled>{{ trans('lang.leagues') }}</option>
                                                @foreach ($leagues as $league)
                                                    <option value="{{$league['id']}}">{{$league['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <div class="custome-input">
                                            <select name="event" class="form-control p-3" id="event">
                                                <option selected value="" disabled>{{ trans('lang.events') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h5 class="font-wight-blod mx-4 my-4 mb-4"> {{ trans('lang.number_of_individuals') }}</h5>
                            <!-- person-count -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="count-vistor mb-4">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <span>{{ trans('lang.adults') }}</span>
                                            </div>
                                            <div class="col-md-3 offset-md-3">
                                                <div class="row float-left ">
                                                    <div class="col-4">
                                                        <button type="button" class="btn btn-outline-info " id="menus_one" >-</button>
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="number" id="Adults" name="adult" class="form-control" value="1">
                                                    </div>
                                                    <div class="col-4">
                                                        <button type="button" class="btn btn-info " id="plus_one">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <span>{{ trans('lang.children') }}</span>
                                            </div>
                                            <div class="col-md-3 offset-md-3">
                                                <div class="row float-left ">
                                                    <div class="col-4">
                                                        <button type="button" class="btn btn-outline-info " id="menus_one_Children">-</button>
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="number" id="Children" name="children" class="form-control" value="0">
                                                    </div>
                                                    <div class="col-4">
                                                        <button type="button" class="btn btn-info" id="plus_one_Children">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-md-6">
                                                <span>{{ trans('lang.babies') }} </span>
                                            </div>
                                            <div class="col-md-3 offset-md-3">
                                                <div class="row float-left ">
                                                    <div class="col-4">
                                                        <button type="button" class="btn btn-outline-info " id="menus_one_Baby">-</button>
                                                    </div>
                                                    <div class="col-4">
                                                        <input type="number" id="Baby" name="babies" class="form-control" value="0">
                                                    </div>
                                                    <div class="col-4">
                                                        <button type="button" class="btn btn-info " id="plus_one_Baby">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!--  -->
                            <div class="row">
                                <div class="col-md-12  mb-4">
                                    <div class="">
                                        {{ trans('lang.price') }} <br>
                                        <input value="economy" name="price" type="radio">&nbsp;{{ trans('lang.economy') }} &nbsp;&nbsp;&nbsp;
                                        <input value="intermediate" name="price" type="radio">&nbsp;{{ trans('lang.intermediate') }}&nbsp;&nbsp;&nbsp;
                                        <input value="special" name="price" type="radio">&nbsp;{{ trans('lang.special') }}&nbsp;&nbsp;&nbsp;
                                        <input value="unlimited" name="price" type="radio">&nbsp;{{ trans('lang.unlimited') }}&nbsp;&nbsp;&nbsp;
                                    </div>
                                </div>



                                <div class="col-md-12  mb-4">
                                    <div class="">
                                        {{ trans('lang.interests') }} <br>
                                        <input value="sea_trips" name="interests[]" type="checkbox">&nbsp;{{ trans('lang.sea_trips') }} &nbsp;&nbsp;&nbsp;
                                        <input value="swim" name="interests[]" type="checkbox">&nbsp;{{ trans('lang.swim') }}&nbsp;&nbsp;&nbsp;
                                        <input value="cafes" name="interests[]" type="checkbox">&nbsp;{{ trans('lang.cafes') }}&nbsp;&nbsp;&nbsp;
                                        <input value="safari" name="interests[]" type="checkbox">&nbsp;{{ trans('lang.safari') }}&nbsp;&nbsp;&nbsp;
                                        <input value="party" name="interests[]" type="checkbox">&nbsp;{{ trans('lang.party') }} &nbsp;&nbsp;&nbsp;
                                        <input value="sports" name="interests[]" type="checkbox">&nbsp;{{ trans('lang.sports') }}&nbsp;&nbsp;&nbsp;
                                        <input value="attractions" name="interests[]" type="checkbox">&nbsp;{{ trans('attractions.special') }}&nbsp;&nbsp;&nbsp;
                                        <input value="nature" name="interests[]" type="checkbox">&nbsp;{{ trans('lang.nature') }} &nbsp;&nbsp;&nbsp;
                                    </div>
                                </div>

                            </div>
                            <!--  -->
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <div class="custome-input">
                                        <input type="text" name="reply_time" placeholder="{{trans('lang.reply_time')}}" class="form-control">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6  mb-4">
                                    <div class="custome-input">
                                        <select name="transport" class="form-control p-3" id="">
                                            <option selected value="" disabled>{{ trans('lang.transport') }}</option>
                                            <option value="home">{{ trans('lang.home') }}</option>
                                            <option value="way">{{ trans('lang.way') }}</option>
                                            <option value="both">{{ trans('lang.both') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6  mb-4">
                                    <div class="custome-input">
                                        {{ trans('lang.trip_stop') }} <br>
                                        <input value="yes" name="trip_stop" type="radio">&nbsp;{{ trans('lang.yes') }} &nbsp;&nbsp;&nbsp;
                                        <input value="no" name="trip_stop" type="radio">&nbsp;{{ trans('lang.no') }}&nbsp;&nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <!--  -->
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <div class="custome-input">
                                        <select name="help" class="form-control p-3">
                                            <option selected value="" disabled>{{ trans('lang.help') }}</option>
                                            <option value="visa">{{ trans('lang.visa') }}</option>
                                            <option value="guide">{{ trans('lang.guide') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12  ">
                                    <div class="custome-input full-row ">
                                        <div class="form-group">
                                            <textarea name="note" class="form-control" id="Textarea" placeholder="{{trans('lang.note')}}"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-6 col-md-4 offset-md-4 mt-5">
                                <button class="btn btn-info btn-block" id="button">{{trans('lang.send')}}</button>
                                <button type="reset" id="reset" style="display:none"></button>
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
    <script src="{{asset('js/date-time-picker.min.js')}}"></script>
    <script>
        $('#go_date').dateTimePicker();
        $('#back_date').dateTimePicker();
        @if(LaravelLocalization::getCurrentLocale() == 'ar')
            successMessage = 'تم ارسال رسالتك بنجاح , انتظر افض العروض من مزودي الخدمة قريبا'
        @else
            successMessage = 'Your request has been sent, wait best prices from our providers soon'
        @endif
        $('#category_id').change(function(){
            id = $(this).val();
            if(id == 3){
                $('#leagues').fadeIn();
            }else{
                $('#leagues').fadeOut();
            }
        });

        $('#league').change(function(){
            $("#event").find('option').remove();
            id = $(this).val()
            placeholder = '{{trans("lang.loading_events")}}'
            $('#event').append($('<option selected value="" disabled>'+placeholder+'</option>'));
            $.ajax({
                type : 'GET',
                url : '{{url("/client/get-events")}}' + '/' +id,
                success: function (response) {
                    if(response == 0){
                        $('#event').append($('<option selected value="" disabled>there are no result</option>'));
                    }else{
                        placeholder = '{{trans("lang.select")}}'
                        $('#event').append($('<option selected value="" disabled>'+placeholder+'</option>'));
                        $.each(response, function(key, value) {
                            $('#event').append($("<option></option>").attr("value",value.id).text(value.name + ' - ' + value.date));
                        });
                    }
                },
            });
        })

        // $('#from_country').change(function(){
        //     $("#from_city").find('option').remove();
        //     placeholder = '{{trans("lang.select")}}'
        //             $('#from_city').append($('<option selected value="" disabled>'+placeholder+'</option>'));
        //     id = $(this).val()
        //     $.ajax({
        //         type : 'GET',
        //         url : '{{url("get-cities")}}' + '/' +id,
        //         success: function (response) {
        //             $.each(response, function(key, value) {
        //                 $('#from_city').append($("<option></option>").attr("value",value.name).text(value.name));
        //             });
        //         },
        //     });
        // })

        // $('#to_country').change(function(){
        //     $("#to_city").find('option').remove();
        //     placeholder = '{{trans("lang.select")}}'
        //             $('#to_city').append($('<option selected value="" disabled>'+placeholder+'</option>'));
        //     id = $(this).val()
        //     $.ajax({
        //         type : 'GET',
        //         url : '{{url("get-cities")}}' + '/' +id,
        //         success: function (response) {
        //             $.each(response, function(key, value) {
        //                 $('#to_city').append($("<option></option>").attr("value",value.name).text(value.name));
        //             });
        //         },
        //     });
        // })


        function initMap() {

            $('#from_country').change(function(){
                var input = document.getElementById('fromm_city');

                country = $('#from_country').find(':selected').data('code');
                $('#mapToggle').show();
                var options = {
                    types: ['(cities)'],
                    componentRestrictions: {country: country}
                };

                var autocomplete = new google.maps.places.Autocomplete(input, options);
                var infowindow = new google.maps.InfoWindow();

                autocomplete.addListener('place_changed', function() {
                    infowindow.close();
                    var place = autocomplete.getPlace();
                    if (!place.geometry) {
                        document.getElementById("fromm_city").style.display = 'inline-block';
                        document.getElementById("fromm_city").innerHTML = "Cannot Locate '" + input.value + "' on map";
                        return;
                    }
                    document.getElementById('from_city').value = place.place_id; // place id

                });
            })

            $('#to_country').change(function(){
                var input = document.getElementById('too_city');
                country = $('#to_country').find(':selected').data('code');
                $('#mapToggle2').show();
                var options = {
                    types: ['(cities)'],
                    componentRestrictions: {country: country}
                };

                var autocomplete = new google.maps.places.Autocomplete(input, options);
                var infowindow = new google.maps.InfoWindow();

                autocomplete.addListener('place_changed', function() {
                    infowindow.close();
                    var place = autocomplete.getPlace();
                    if (!place.geometry) {
                        document.getElementById("too_city").style.display = 'inline-block';
                        document.getElementById("too_city").innerHTML = "Cannot Locate '" + input.value + "' on map";
                        return;
                    }
                    document.getElementById('to_city').value = place.place_id; // place id
                });
            })
        }
    </script>
    @if (LaravelLocalization::getCurrentLocale() == 'ar')
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCt9ApcngmV7Zj_XR8h5hoznS1EaYuPLhI&libraries=places&callback=initMap&language=ar" async defer></script>
    @else
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCt9ApcngmV7Zj_XR8h5hoznS1EaYuPLhI&libraries=places&callback=initMap&language=en" async defer></script>
    @endif
@endsection
