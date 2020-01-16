@extends('layouts.front')


@section('title') {{ trans('lang.my_offers') }} @endsection
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/lity.min.css')}}">
@endsection
@section('rate')
    <?php
    if(LaravelLocalization::getCurrentLocale() == 'ar'){
        $dir   = 'lang_ar';
        $to_city   = 'to_city_ar';
        $from_country = 'from_country_ar';
        $to_country   = 'to_country_ar';
    }else{
        $dir   = 'lang_en';
        $to_city   = 'to_city_en';
        $from_country = 'from_country_en';
        $to_country   = 'to_country_en';
    }
    ?>
@endsection
@section('content')

    <!-- Start my-offers -->
    <div class="details-offer my-offers">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h6> <a href="{{url('/')}}"> {{ trans('lang.main') }} </a> / <a href="#" class="active"> {{
                        trans('lang.my_offers') }} </a>
                    </h6>
                </div>
                <div class="col-md-12 mt-4 mb-3">
                    <div class="col-md-6">
                        <div class="main-navs">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item w-50 ">
                                    <a class="nav-link text-center  " id="pills-home-tab" data-toggle="pill" href="#pills-home"
                                       role="tab" aria-controls="pills-home" aria-selected="true">{{ trans('lang.offers')
                                    }}</a>
                                </li>
                                <li class="nav-item w-50">
                                    <a class="nav-link active text-center" id="pills-profile-tab" data-toggle="pill" href="#pills-profile"
                                       role="tab" aria-controls="pills-profile" aria-selected="false">{{
                                    trans('lang.request_offers') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    @if (session('success'))
                        <div class="alert alert-success">
                            @if (LaravelLocalization::getCurrentLocale() == 'ar')
                                تم بنجاح
                            @else
                                Successfully done
                            @endif
                        </div>
                    @endif
                    <div class="tab-content" id="pills-tabContent">
                        <!--  first Tap -->
                        <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                            @foreach ($offers as $offer)
                                <div class="col-md-12">
                                    <div class="desc-offer">
                                        <div class="row">

                                            <div class="col-md-4 border-right">
                                                <div class="first-desc ">
                                                    <a href='{{route('client-offer-details', ['id' => $offer->id])}}'>
                                                        <div class="row">
                                                            <div class="col-2 mt-1 ">
                                                    <span><img class="img-fluid" src="{{asset('img/flag.png')}}" alt="flag">
                                                    </span>
                                                            </div>
                                                            <div class="col-10 ">
                                                                <h6> {{ trans('lang.order_id') }} - {{$offer->id}}</h6>
                                                                <h5> {{$offer->country->name}} - {{$offer->city->name}} </h5>
                                                                <p> {{$offer->provider->first_name . ' ' . $offer->provider->last_name}} </p>
                                                                <ul class="list-inline mt-2">
                                                                    {{$offer->rate }} / 10
                                                                </ul>
                                                                {{-- <small> 20-10-2018 </small> --}}
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-center border-right">
                                                <div class="three-desc">
                                                    <div class="row">
                                                        <div class="col-md-8 col-10 ">
                                                            <p> {{$offer->days}} {{trans('lang.days')}} - {{$offer->days - 1}}
                                                                {{trans('lang.night')}} </p>
                                                            <h6> {{ trans('lang.total') }} </h6>

                                                            <h5>
                                                                @if(auth('clients')->check())
                                                                    @if (auth('clients')->user()->currency == 'dollar')
                                                                        @if($offer->currency == 'sar')
                                                                            {{round($offer->price / 3.75)}} {{trans("lang.dollar")}}
                                                                        @else
                                                                            {{$offer->price}} {{trans("lang.dollar")}}
                                                                        @endif
                                                                    @else
                                                                        @if($offer->currency == 'sar')
                                                                            {{$offer->price}} {{trans("lang.sar")}}
                                                                        @else
                                                                            {{round($offer->price * 3.75)}} {{trans("lang.sar")}}
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            </h5>
                                                            <?php $s = $offer->pivot->status; ?>
                                                            <h6>{{trans("lang.$s")}}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4 ">
                                                <div class="tow-desc ">
                                                    <p class="text-center">{{ trans("lang.rate") }}</p>


                                                    <?php $rr = $offer->rates()->where('client_id', auth('clients')->id())->first() ?>
                                                    @if ($rr == null)
                                                        <form action="">
                                                            <input type="text" name="RATING" class="rate-input" value="">
                                                            <ul class="list-inline  text-center rate-code" id="rate-code_rate">
                                                                <li class="list-inline-item" data-id="{{$offer->id}}" value="1" ><i class="far fa-star active"></i></li>
                                                                <li class="list-inline-item" data-id="{{$offer->id}}" value="2"><i class="far fa-star"></i></li>
                                                                <li class="list-inline-item" data-id="{{$offer->id}}" value="3"><i class="far fa-star"></i></li>
                                                                <li class="list-inline-item" data-id="{{$offer->id}}" value="4"><i class="far fa-star"></i></li>
                                                                <li class="list-inline-item" data-id="{{$offer->id}}" value="5"><i class="far fa-star"></i></li>
                                                                <li class="list-inline-item" data-id="{{$offer->id}}" value="6"><i class="far fa-star"></i></li>
                                                                <li class="list-inline-item" data-id="{{$offer->id}}" value="7"><i class="far fa-star"></i></li>
                                                                <li class="list-inline-item" data-id="{{$offer->id}}" value="8"><i class="far fa-star"></i></li>
                                                                <li class="list-inline-item" data-id="{{$offer->id}}" value="9"><i class="far fa-star"></i></li>
                                                                <li class="list-inline-item" data-id="{{$offer->id}}" value="10"><i class="far fa-star"></i></li>
                                                            </ul>
                                                        </form>

                                                    @else
                                                        <ul class="list-inline mt-2 text-center" align="center">
                                                            {{$rr->rate }} / 10
                                                        </ul>
                                                    @endif
                                                    <form class="multiForm" action="{{route('client_start_chat')}}" method="POST">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <input type="text" required name="message" class="form-control"
                                                                       placeholder="{{trans('lang.chatting')}}"  />
                                                                <input type="hidden" value="1" name="offer_type">
                                                                <input type="hidden" value="1" name="type">
                                                                <input type="hidden" value="{{$offer->id}}" name="offer_id">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <button type="submit" class="btn btn-success btn-block"> {{
                                                            trans('lang.admin_chat') }} </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <br>
                                                    <form class="multiForm" action="{{route('client_start_chat')}}" method="POST">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <input type="text" required name="message" class="form-control"
                                                                       placeholder="{{trans('lang.chatting')}}"  />
                                                                <input type="hidden" value="1" name="offer_type">
                                                                <input type="hidden" value="2" name="type">
                                                                <input type="hidden" value="{{$offer->id}}" name="offer_id">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <button type="submit" class="btn btn-success btn-block"> {{
                                                            trans('lang.provider_chat') }} </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End item -->
                        @endforeach
                        <!-- start item -->
                            <div class="col-md-4 offset-md-4 mt-5">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-center">
                                        @if (($offers->lastPage() - $offers->currentPage()) == 0)
                                            @if ($offers->currentPage() == 1)
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
                                                <a class="page-link" href='?page={{$offers->currentPage() - 1}}'>{{ trans('lang.previous') }}</a>
                                            @endif

                                        @elseif(($offers->currentPage() - 1) == 0)
                                            <a class="page-link" href='?page={{$offers->currentPage() + 1}}'>{{ trans('lang.next') }}</a>
                                            <li class="page-item disabled">
                                                <a class="page-link" href='#'>{{ trans('lang.previous') }}</a>
                                            </li>
                                        @else
                                            <a class="page-link" href='?page={{$offers->currentPage() + 1}}'>{{ trans('lang.next') }}</a>
                                            <a class="page-link" href='?page={{$offers->currentPage() - 1}}'>{{ trans('lang.previous') }}</a>
                                        @endif

                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <!-- End first Tap -->
                        <!-- start Tow Tap -->
                        <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

                        @foreach ($requestOffers as $requestOffer)
                            <!-- start item -->
                                <div class="col-md-12">
                                    <div class="desc-offer slide_item" data-id="{{$requestOffer->id}}">
                                        {{-- <a href="{{url("/request-offer/show/$requestOffer->id")}}"> --}}
                                        <div class="row">
                                            <div class="col-md-4 border-right">
                                                <div class="first-desc ">
                                                    <div class="row">
                                                        <div class="col-2 mt-1 ">
                                                            <span><img class="img-fluid" src="{{asset('img/flag.png')}}" alt="flag"> </span>
                                                        </div>
                                                        <div class="col-10 ">
                                                            <h6> {{ trans('lang.order_id') }} - {{$requestOffer->id}}</h6>
                                                            <h5>{{$requestOffer->$to_country}} - {{$requestOffer->$to_city}}</h5>
                                                            <h6>
                                                                @if ($requestOffer->category_id == 1)
                                                                    {{ trans('lang.entertainment') }}
                                                                @elseif($requestOffer->category_id == 2)
                                                                    {{ trans('lang.educational') }}
                                                                @elseif($requestOffer->category_id == 3)
                                                                    {{ trans('lang.sport') }}
                                                                @else
                                                                    {{ trans('lang.medical') }}
                                                                @endif
                                                            </h6>
                                                            <p>{{$requestOffer->days}} {{trans('lang.days')}} - {{$requestOffer->days - 1}}
                                                                {{trans('lang.night')}}</p>
                                                            {{-- <small> 20-10-2018 </small> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8 ">
                                                @if ($requestOffer->provider_id != null)
                                                    <div class="tow-desc ">
                                                        <a href="{{route("client_accept_offer", ['id' => $requestOffer->id])}}?provider_id={{$requestOffer->provider_id}}">
                                                            <p class="text-center"><?php $status = $requestOffer->status; ?>
                                                            <p class="text-center">{{ trans("lang.$status") }}</p></p>
                                                            <i class="fas fa-arrow-down float-right"></i>
                                                        </a>
                                                    </div>

                                                    {{-- <form class="multiForm" action="{{url("/chat/send-message")}}" method="GET">
                                                        <div class="row">
                                                            <div class="col-md-8 offset-md-1">
                                                                <div class="col-md-8">
                                                                    <input type="text" required name="message" class="form-control"
                                                                        placeholder="{{trans('lang.chatting')}}"  />
                                                                        <input type="hidden" value="request_offer" name="offer_type">
                                                                        <input type="hidden" value="{{$requestOffer->id}}" name="request_offer_id">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <button type="submit" class="btn btn-success btn-block"> {{
                                                                    trans('lang.send') }} </button>
                                                            </div>
                                                        </div>
                                                    </form> --}}
                                                @else
                                                    <div class="tow-desc ">
                                                        @if ($requestOffer->provider_id == null)
                                                            <div class="col-md-4">
                                                                <button class="btn btn-danger btn-block" onclick="window.location.href='{{route('cancel_request_offer',  $requestOffer->id)}}'">
                                                                    {{ trans('lang.cancel') }} </button>
                                                            </div>
                                                        @endif

                                                        <p class="text-center"><?php $status = $requestOffer->status; ?>
                                                        <p class="text-center">{{ trans("lang.$status") }}</p></p>
                                                        <i class="fas fa-arrow-down float-right"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        {{-- </a> --}}
                                    <!-- slide -->
                                        @if ($requestOffer->providerOffer != null)

                                            <div class="parent-slide" id="desc-my-offer-{{$requestOffer->id}}" style="display:none">
                                                <hr>
                                                @foreach ($requestOffer->providerOffer as $providerOffer)
                                                    @if ($requestOffer->status == 'pending')
                                                        <div class="row">
                                                            <div class="col-md-4 border-right mt-3  "  >
                                                                <div class="first-desc ">
                                                                    <div class="row">
                                                                        <div class="col-2 mt-1 ">
                                                                            <span><img class="img-fluid" src="{{asset('img/flag.png')}}" alt="flag"> </span>
                                                                        </div>
                                                                        <div class="col-10 ">
                                                                            <p> {{$providerOffer->provider->first_name . ' ' . $providerOffer->provider->last_name}} </p>
                                                                            <ul class="list-inline mt-2">
                                                                                {{$providerOffer->provider->rate }} / 10
                                                                            </ul>
                                                                            {{-- <small> 20-10-2018 </small> --}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 text-center ">
                                                                <div class="three-desc">
                                                                    <div class="row">
                                                                        {{-- <div class="col-2">
                                                                            <img class="img-fluid d-block ml-auto mt-2" src="{{asset('img/offers.png')}}" alt="offers">
                                                                        </div> --}}
                                                                        <div class="col-10 ">
                                                                            <h6> {{ trans('lang.prices') }} </h6>
                                                                            <h5> {{$providerOffer->price}} </h5>
                                                                        </div>
                                                                    </div><br>
                                                                    <div class="row">
                                                                        {{-- <div class="col-2">
                                                                            <img class="img-fluid d-block ml-auto mt-2" src="{{asset('img/offers.png')}}" alt="offers">
                                                                        </div> --}}
                                                                        <div class="col-12 ">
                                                                            <h6> {{ trans('lang.description') }} </h6>
                                                                            <p> {{$providerOffer->description}} </p>
                                                                        </div>
                                                                    </div>
                                                                    @if ($providerOffer->image != null)
                                                                        <div class="row">
                                                                            <div class="col-10 ">
                                                                                <h6> {{ trans('lang.imageOffer') }} </h6>
                                                                                <a href="{{asset("uploads/$providerOffer->image")}}" data-lity><img width="100" height="100" src="{{asset("uploads/$providerOffer->image")}}" alt="offers"></a>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                </div>
                                                            </div>

                                                            @if ($providerOffer->status == 'reject')
                                                                <div class="col-md-5 ">
                                                                    <div class="tow-desc ">
                                                                        <p class="text-center">{{ trans('lang.rejected') }}</p>
                                                                    </div>
                                                                </div>
                                                            @elseif ($providerOffer->status == 'closed')
                                                                <div class="col-md-5 ">
                                                                    <div class="tow-desc ">
                                                                        <p class="text-center">{{ trans('lang.closed') }}</p>
                                                                    </div>
                                                                </div>
                                                            @elseif ($providerOffer->status == 'processing')
                                                                <div class="col-md-5 ">
                                                                    <div class="tow-desc ">
                                                                        <p class="text-center">{{ trans('lang.processing') }}</p>
                                                                    </div>
                                                                </div>
                                                            @elseif ($providerOffer->status == 'paid')
                                                                <div class="col-md-5 ">
                                                                    <div class="tow-desc ">
                                                                        <p class="text-center">{{ trans('lang.paid') }}</p>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="col-md-4 mt-2 ">
                                                                    <div class="row" >
                                                                        <div class="col-md-4">
                                                                            <form class="multiForm" method="GET" action="{{route("client_accept_offer", ['id' => $requestOffer->id])}}">
                                                                                <input type="hidden" name="provider_id" value="{{$providerOffer->provider_id}}">
                                                                                <button type="submit" class="btn btn-success btn-block"> {{
                                                                            trans('lang.accept') }} </button>
                                                                            </form>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <button type="button" class="btn btn-danger btn-block show_input_reject" data-id="{{$providerOffer->id}}">
                                                                                {{ trans('lang.reject') }}
                                                                            </button>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <button type="button" class="btn btn-warning btn-block show_input_chat" data-id="{{$providerOffer->id}}">
                                                                                {{ trans('lang.chat') }} </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="row "  >
                                                            <div class="col-md-11 mt-3 ">
                                                                <form class="multiForm" action="{{route('client_start_chat')}}"
                                                                      method="POST">
                                                                    @csrf
                                                                    <div class="row" style="display:none" id="Veiw_input_show_chatting-{{ $providerOffer->id }}" >
                                                                        <div class="col-md-9">
                                                                            <input type="text" required name="message" class="form-control"
                                                                                   placeholder="{{trans('lang.chatting')}}"  />
                                                                            <input type="hidden" value="2" name="offer_type">
                                                                            <input type="hidden" value="1" name="type">
                                                                            <input type="hidden" value="{{$requestOffer->id}}" name="request_offer_id">
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <button type="submit" class="btn btn-success btn-block"> {{
                                                            trans('lang.admin_chat') }} </button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                                <br>
                                                                <form class="multiForm" action="{{route('client_start_chat')}}" method="POST">
                                                                    @csrf
                                                                    <div class="row" style="display:none" id="Veiw_input_show_chatting-{{ $providerOffer->id }}-{{ $providerOffer->id }}" >
                                                                        <div class="col-md-9">
                                                                            <input type="text" required name="message" class="form-control"
                                                                                   placeholder="{{trans('lang.chatting')}}"  />
                                                                            <input type="hidden" value="2" name="offer_type">
                                                                            <input type="hidden" value="2" name="type">
                                                                            <input type="hidden" value="{{$providerOffer->provider_id}}" name="provider_id">
                                                                            <input type="hidden" value="{{$requestOffer->id}}" name="request_offer_id">

                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <button type="submit" class="btn btn-success btn-block"> {{
                                                            trans('lang.provider_chat') }} </button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                                <form class="multiForm" method="POST" action='{{route("client_reject_offer", ['id' => $requestOffer->id])}}'>
                                                                    @csrf
                                                                    <div class="row" style="display:none" id="Veiw_input_show_input-{{ $providerOffer->id }}">
                                                                        <div class="col-md-8 offset-md-1">
                                                                            <div class="col-md-8">
                                                                                <input type="hidden" name="provider_id" value="{{$providerOffer->provider_id}}">
                                                                                <input type="text" name="reason" required class="form-control"
                                                                                       placeholder="{{trans('lang.reject_reason')}}" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <button class="btn btn-success btn-block"> {{trans('lang.send')
                                                            }} </button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                    @else
                                                        @if ($requestOffer->provider_id == $providerOffer->provider_id)
                                                            <div class="row">
                                                                <div class="col-md-4 border-right mt-3  "  >
                                                                    <div class="first-desc ">
                                                                        <div class="row">
                                                                            <div class="col-2 mt-1 ">
                                                                                <span><img class="img-fluid" src="{{asset('img/flag.png')}}" alt="flag"> </span>
                                                                            </div>
                                                                            <div class="col-10 ">
                                                                                <h5>{{$requestOffer->to_country}} - {{$requestOffer->$to_city}}</h5>
                                                                                <p> {{$providerOffer->provider->first_name . ' ' . $providerOffer->provider->last_name}} </p>
                                                                                <ul class="list-inline mt-2">
                                                                                    {{$providerOffer->provider->rate }} / 10
                                                                                </ul>
                                                                                {{-- <small> 20-10-2018 </small> --}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3 text-center ">
                                                                    <div class="three-desc">
                                                                        <div class="row">
                                                                            <div class="col-2">
                                                                                <img class="img-fluid d-block ml-auto mt-2" src="{{asset('img/offers.png')}}" alt="offers">
                                                                            </div>
                                                                            <div class="col-10 ">
                                                                                <h6> {{ trans('lang.prices') }} </h6>
                                                                                <h5> {{$providerOffer->price}} </h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4 mt-2 ">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            {{ trans("lang.$requestOffer->status") }}


                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endforeach

                                            </div>
                                        @endif

                                    </div>
                                </div>
                        @endforeach
                        <!-- End item -->
                            <div class="col-md-4 offset-md-4 mt-5">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-center">
                                        @if (($requestOffers->lastPage() - $requestOffers->currentPage()) == 0)
                                            @if ($requestOffers->currentPage() == 1)
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
                                                <a class="page-link" href='?p={{$requestOffers->currentPage() - 1}}'>{{ trans('lang.previous') }}</a>
                                            @endif

                                        @elseif(($requestOffers->currentPage() - 1) == 0)
                                            <a class="page-link" href='?p={{$requestOffers->currentPage() + 1}}'>{{ trans('lang.next') }}</a>
                                            <li class="page-item disabled">
                                                <a class="page-link" href='#'>{{ trans('lang.previous') }}</a>
                                            </li>
                                        @else
                                            <a class="page-link" href='?p={{$requestOffers->currentPage() + 1}}'>{{ trans('lang.next') }}</a>
                                            <a class="page-link" href='?p={{$requestOffers->currentPage() - 1}}'>{{ trans('lang.previous') }}</a>
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
@endsection

@section('js')
    <script src="{{asset('assets/js/lity.min.js')}}"></script>
    <script>
        let url = '{{url("/offers/rate")}}';
    </script>
@endsection
