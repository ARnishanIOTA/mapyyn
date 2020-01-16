@extends('layouts.master')

@section('title') {{trans('lang.create-admin')}} @endsection

@section('content')
<!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title ">{{ trans('Input Videos') }}</h3>
            </div>
        </div>
    </div>
   
            <div class="row">
                <div class="col-md-12">
                    <!--begin::Form-->
                    

                <form id="modelForm" class="m-form m-form--fit m-form--label-align-right m-form--state" method="POST" action="{{route('videos.store')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                                <div class="form-group m-form__group custom-file" id="videosGroup">
                                <label for="video">{{ trans('Choose Video') }}</label>
                                <div class="custom-file">
                                    <input type="file" class="form-control" name="video">
                                    
                                </div>

                                
                                <br>
                                <br>
                                <button type="submit" id="create" class="btn btn-success">Submit</button>
                            </div>


                   </form>
                    <!--end::Form-->
                </div>
            </div>
@endsection

