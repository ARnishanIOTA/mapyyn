<?php

namespace App\Http\Controllers\Api\Providers;

use App\Models\Provider;
use App\Models\EditProfile;
use Illuminate\Http\Request;
use App\Models\MobileNotification;
use App\Models\EditProviderCategory;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\ProviderRequest;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Provider\ProfileResource;

class ProfileController extends ApiController
{
    /**
     * return profile data
     * 
     * @return json
     */
    public function show()
    {
        $provider = $this->provider()->user();

        return $this->apiResponse(new ProfileResource($provider)); 
    } 
    
    
    /**
     * update profile
     * 
     * @param object $request
     * @return json
     */
    public function update(ProviderRequest $request)
    {
        $check = EditProfile::where('provider_id', $this->provider()->id())->first();
        $inputs = $request->only(['first_name', 'last_name', 'email', 'phone', 'code','country', 'address']);
        
        if($request->city != null){
            $inputs['city'] = $request->city;
        }
        $inputs['status'] = 0;
        $inputs['provider_id'] = $this->provider()->id();
        if($check != null){
            $check->update($inputs);
        }else{
            EditProfile::create($inputs);
        }

        if($request->categories != null){
            EditProviderCategory::where('provider_id', $this->provider())->delete();
            foreach ($request->categories as $category) {
                EditProviderCategory::create(['category_id' => $category, 'provider_id' => $this->provider()->id()]);
            }
        }

        $notify['message_en'] = "new request for update profile : ". $this->provider()->user()->first_name. ' ' . $this->provider()->user()->first_name ;
        $notify['message_ar'] = "طلب جديد لتعديل الصفحة الشخصية لـ : ". $this->provider()->user()->last_name. ' ' . $this->provider()->user()->last_name ;
        $notify['provider_id'] = $this->provider()->id();
        $notify['type'] = 'profile';
        $notify['user_type'] = 11;
        MobileNotification::create($notify);

        if(request('lang') == 'ar'){
            return $this->apiResponse(['success' => 'في انتظار موافقة الادمن']);
        }else{
            return $this->apiResponse(['success' => 'waiting approval from admin']);
        } 
    }


    /**
     * update profile
     * 
     * @param object $request
     * @return json
     */
    public function password(Request $request)
    {
        $request->validate(['password' => 'required|string|min:8|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/']);

        if (Hash::check($request->password, $this->provider()->user()->password)) {
            if(request('lang') == 'ar'){
                return $this->apiResponse((object)[], 'يجب اختيار كلمة مرور مختلفة عن كلمة المرور الحالية', 404);
            }else{
                return $this->apiResponse((object)[], 'You must enter password not same with current password', 404);
            }
        }
        
        $inputs['password'] = bcrypt($request->password);
        
        Provider::where('id', $this->provider()->id())->update($inputs);
        $provider = Provider::find($this->provider()->id());
        $provider->sendChangePasswordNotification();

        if(request('lang') == 'ar'){
            return $this->apiResponse(['success' => 'تم بنجاح']);
        }else{
            return $this->apiResponse(['success' => 'Password Updated Successfully']); 
        }
    }


     /**
     * update Image
     * 
     * @param object $request
     * @return json
     */
    public function updateImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:3000'
        ]);

        $provider = Provider::where('id', $this->provider()->id())->first();
        if($provider->logo != null){
            $file = public_path("uploads/$provider->logo");
            if(file_exists($file)){
                unlink($file);
            } 
        }
        
        
        $provider->logo = $request->image->store('providers');
        $provider->save();

        if(request('lang') == 'ar'){
            return $this->apiResponse(['success' => 'تم بنجاح']);
        }else{
            return $this->apiResponse(['success' => 'Image Updated Successfully']); 
        }
    }
}
