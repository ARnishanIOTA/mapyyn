@extends('layouts.front')


@section('title') {{ trans('lang.offers') }} @endsection

@section('content')

<?php if(LaravelLocalization::getCurrentLocale() == 'ar'){
	$ads = 'ads_ar.jpg';
	$align = 'align_ar.jpg';

}else{
	$ads = 'ads_en.jpg';
	$align = 'align_en.jpg';
}?>
   <!-- start information  -->
	<div class="information mt-4">
            <div class="container">
                <div class="col-md-12">
                    <h6> <a href="{{url('/')}}"> {{ trans('lang.main') }} </a> / <a href="#" class="active"> {{trans('lang.offers')}} </a> </h6>
                </div>
            <form action="{{route('all-offers')}}" method="GET">
                    <div class="row">
                        <div class="col-md-3 col-12 mb-4">
                            <div class="custome-input">
                                <span><img class="img-fluid " src="{{asset('img/flag.png')}}" alt="flag"> </span>
                                <select name="country" class="form-control p-3" id="category_id">
                                    <option selected value="" disabled>{{ trans('lang.countries') }}</option>
                                    <option value="">{{ trans('lang.all') }}</option>
                                    @foreach ($countries as $country)
                                        <option value="{{$country->id}}" {{$country->id == request('country') ? 'selected' : ''}}>{{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <div class="custome-input">
                                <span><img class="img-fluid" src="{{asset('img/categories.png')}}" alt="categories"> </span>
                                <select name="category" class="form-control p-3" id="category_id">
                                    <option selected value="" disabled>{{ trans('lang.categories') }}</option>
                                    <option value="">{{ trans('lang.all') }}</option>
                                    <option value="1" {{request('category') == 1 ? 'selected' : ''}}>{{ trans('lang.entertainment') }}</option>
                                    <option value="2" {{request('category') == 2 ? 'selected' : ''}}>{{ trans('lang.educational') }}</option>
                                    <option value="3" {{request('category') == 3 ? 'selected' : ''}}>{{ trans('lang.sport') }}</option>
                                    <option value="4" {{request('category') == 4 ? 'selected' : ''}}>{{ trans('lang.medical') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-4">
                                <div class="custome-input">
                                    <span><img class="img-fluid" src="{{asset('img/dollar.png')}}"> </span>
                                    <select name="currency" class="form-control p-3" id="currency">
                                        <option selected value="" disabled>{{ trans('lang.currency') }}</option>
                                        <option value="">{{ trans('lang.all') }}</option>
                                        <option value="dollar" {{request('currency') == 'dollar' ? 'selected' : ''}}>{{ trans('lang.dollar') }}</option>
                                        <option value="sar" {{request('currency') == 'sar' ? 'selected' : ''}}>{{ trans('lang.sar') }}</option>
                                    </select>
                                </div>
                            </div>
                        <div class="col-md-2 col-6  mb-4">
                            <div class="custome-input">
                                <span><img class="img-fluid" src="{{asset('img/dollar.png')}}" alt="dollar"> </span>
                                <select name="price" class="form-control p-3" id="price">
                                    <option selected value="" disabled>{{ trans('lang.price') }}</option>
									<option value="">{{ trans('lang.all') }}</option>
									<option value="2000" {{request('price') == 4000 ? 'selected' : ''}}>2000 - 4000</option>
									<option value="4000" {{request('price') == 8000 ? 'selected' : ''}}>4000 - 8000</option>
									<option value="8000" {{request('price') == 16000 ? 'selected' : ''}}>8000 - 16000</option>
									<option value="16000" {{request('price') == 50000 ? 'selected' : ''}}>16000 - 50000</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-6  mb-4">
                            <div class="custome-input">
                                <span><img class="img-fluid" src="{{asset('img/chavron.png')}}" alt="chavron"> </span>
                                <select name="recent" class="form-control p-3" id="">
                                    <option selected value="" disabled> {{ trans('lang.recent') }} </option>
                                    <option value="desc" {{request('recent') == 'desc' ? 'selected' : ''}}>{{ trans('lang.desc') }}</option>
                                    <option value="asc" {{request('recent') == 'asc' ? 'selected' : ''}}>{{ trans('lang.asc') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-6  mb-4">
                            <button class="btn btn-info btn-block"><i class="fas fa-search fa-2x"></i></button>
                        </div>
                    </div>
                </form>
                <!--  -->
            </div>
        </div>
        <!-- End information -->
    
        <!-- Strat best-offers -->
        <div class="best-offers" id="bestOffers">
            <div class="container">
                <h3 class="text-center">{{ trans('lang.offers') }}</h3>
                <div class="row">
                    @foreach ($offers as $offer)
                        <!-- start item -->
                        <div class="col-md-6">
                            <a href='{{route('client-offer-details', ['id' => $offer->id])}}'>
                                <div class="item mb-4">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?php 
                                                foreach ($offer->images as $image) {
                                                    
                                                }
                                            ?>
                                            <img class="img-fluid w-100 h-100" src='{{asset("uploads/$image->image")}}'>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="desc">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h6> {{$offer->country->name}} - {{$offer->city->name}} </h6>
                                                        <small> {{$offer->days}} {{trans('lang.days')}} - {{$offer->days - 1}} {{trans('lang.night')}} </small>
                                                        <ul class="list-inline mt-2">
                                                                {{$offer->rate }} / 10
                                                        </ul>
                                                        <?php
                                                            if($offer->category_id == 1){
                                                                $category = trans('lang.entertainment');
                                                            }else if($offer->category_id == 2){
                                                                $category = trans('lang.educational');
                                                            }else if($offer->category_id == 3){
                                                                $category = trans('lang.sport');
                                                            }else{
                                                                $category = trans('lang.medical');
                                                            }
                                                        ?>
                                                        <h5> {{$category}} </h5>
                                                    </div>
                                                    <div class="col-4 text-center mt-2">
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
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>

                        </div>
                        <!-- start item -->
                    @endforeach
                    </div>
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
            </div>
        </div>
        <!-- End best-offers -->
    
        <!-- start space-ads -->
        <div class="space-ads">
            <div class="container">
                <div class="row">
                    <a href="{{route('client-request-offer')}}"><img class="img-fluid" src="{{asset("img/$align")}}" alt="aline-ads"></a>
                </div>
            </div>
        </div>
        <!-- End space-ads -->
@endsection
