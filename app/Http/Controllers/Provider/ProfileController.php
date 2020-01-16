<?php

namespace App\Http\Controllers\Provider;

use App\Models\City;
use App\Models\Country;
use App\Models\Provider;
use App\Models\EditProfile;
use App\Models\ProviderCategory;
use App\Models\MobileNotification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\EditProviderCategory;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Providers\ProviderRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ProfileController extends Controller
{
    public function index()
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }
        $countries = Country::select('id',"$name as name", 'sortname', 'latitude', 'longitude', 'phonecode')->get();
        return view('providers.profile', compact('countries'));
    }



    public function update(ProviderRequest $request)
    {
        
        $provider = Provider::where('id', auth('providers')->id())->first();

        if($request->password != null){
            if (Hash::check($request->password, $provider->password)) {
                if(LaravelLocalization::getCurrentLocale() == 'ar'){
                    return response(['password' => 'يجب اختيار كلمة مرور مختلفة عن كلمة المرور الحالية'], 404);
                }else{
                    return response(['password' => 'You must enter password not same with current password'], 404);
                }
            }
            $inputs['password'] = bcrypt($request->password);
        }

        if($request->logo != null){
            $image = auth('providers')->user()->logo;
            if($image != null){
                $file = public_path("uploads/$image");
                if(file_exists($file)){
                    unlink($file);
                }
            }
            
            $inputs['logo'] = $request->logo->store('providers');
        }
        
        if($request->password != null || $request->logo != null){
            $provider->update($inputs);
        }

        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }



            $check = EditProfile::where('provider_id', auth('providers')->id())->first();
            $inputs = $request->only(['first_name', 'last_name', 'email', 'phone', 'address']);
            $inputs['country']   = auth('providers')->user()->country;
            if($request->city == null){
                $inputs['city']      = auth('providers')->user()->city;
            }else{
                $inputs['city']      = $request->city;
            }
            $inputs['code'] = auth('providers')->user()->code;
            $inputs['status'] = 0;
            $inputs['provider_id'] = auth('providers')->id();
            if($check != null){
                $check->update($inputs);
            }else{
                EditProfile::create($inputs);
            }
    
            if($request->categories != null){
                EditProviderCategory::where('provider_id', auth('providers')->id())->delete();
                foreach ($request->categories as $category) {
                    EditProviderCategory::create(['category_id' => $category, 'provider_id' => auth('providers')->id()]);
                }
            }


            $notify['message_en'] = "new request for update profile : ". auth('providers')->user()->first_name . ' ' .  auth('providers')->user()->first_name;
            $notify['message_ar'] = "طلب جديد لتعديل الصفحة الشخصية لـ : ". auth('providers')->user()->last_name . ' ' . auth('providers')->user()->last_name ;
            $notify['provider_id'] = auth('providers')->id();
            $notify['type'] = 'profile';
            $notify['user_type'] = 11;
            MobileNotification::create($notify);
        
       
        if($request->password != null){
            $provider->sendChangePasswordNotification();
            auth('providers')->logout();
        }
        return response('success');

        // DB::transaction(function () use ($inputs) {
        //     $provider = Provider::where('id', auth('providers')->id())->update($inputs);
        //     if(request('categories') != null){
        //         ProviderCategory::where('provider_id', auth('providers')->id())->delete();
        //         foreach(request('categories') as $category){
        //             ProviderCategory::create([
        //                 'category_id' => $category,
        //                 'provider_id' => auth('providers')->id()
        //             ]);
        //         }
        //     }
        // });

        // return response('success');
    }


    /**
     * Display a listing of the resource.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCities($id)
    {
        if(LaravelLocalization::getCurrentLocale() == 'ar'){
            $name = 'name_ar';
        }else{
            $name = 'name_en';
        }
        $cities = City::select('id', "$name as name")->where('country_id', $id)->get();
        return response($cities);
    }

}
