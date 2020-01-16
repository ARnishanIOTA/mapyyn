<?php

namespace App\Http\Controllers\Api\Clients;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\ClientRequest;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Client\ProfileResource;

class ProfileController extends ApiController
{
    /**
     * return profile data
     * 
     * @return json
     */
    public function show()
    {
        $client = $this->client()->user();

        return $this->apiResponse(new ProfileResource($client)); 
    } 
    
    
    /**
     * update profile
     * 
     * @param object $request
     * @return json
     */
    public function update(ClientRequest $request)
    {
        $inputs = $request->only(['first_name', 'last_name', 'email', 'phone', 'code','country', 'city', 'lang']);
        if($request->password != null){
            if (Hash::check($request->password, $this->client()->user()->password)) {
                if(request('lang') == 'ar'){
                    return $this->apiResponse((object)[], 'يجب اختيار كلمة مرور مختلفة عن كلمة المرور الحالية', 404);
                }else{
                    return $this->apiResponse((object)[], 'You must enter password not same with current password', 404);
                }
            }
            $inputs['password'] = bcrypt($request->password);
        }
        Client::where('id', $this->client()->id())->update($inputs);

        if($request->password != null){
            $user = Client::find($this->client()->id());
            $user->sendChangePasswordNotification();
        }
        if(request('lang') == 'ar'){
            return $this->apiResponse(['success' => 'تم بنجاح']); 
        }else{
            return $this->apiResponse(['success' => 'Profile Updated Successfully']); 
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

        $client = Client::where('id', $this->client()->id())->first();
        if($client->image != null){
            $file = public_path("uploads/$client->image");
            if(file_exists($file)){
                unlink($file);
            } 
        }
        
        
        $client->image = $request->image->store('clients');
        $client->save();

        if(request('lang') == 'ar'){
            return $this->apiResponse(['success' => 'تم بنجاح']); 
        }else{
            return $this->apiResponse(['success' => 'Image Updated Successfully']); 
        }
        
    }
}
