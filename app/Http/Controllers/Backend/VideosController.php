<?php

namespace App\Http\Controllers\Backend;

use App\User;
use App\Models\Videos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AdminRequest;
use App\Models\Permission;

class VideosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Videos = Videos::all();


      return view('backend.videos.index')->with('Videos',$Videos);
    }

    public function create()
    {
        $permissions = Permission::all();
        // return view('backend.admins.create', compact('permissions'));
        return view('backend.videos.create',compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


public function store(Request $request)
    {
        // $inputs = $request->only('videos');

      
        // $inputs['video']    = $request->image->store('videos');

        // Videos::create($inputs);

        // return response('success');
        $video = Videos::first();
        if($video){
            $filename = public_path().'/uploads/videos/'.$video->video; 
            unlink($filename);
            echo "string";
            $id = $video->id;
            $deletedRows = Videos::where('id', $id)->delete();
        }


          $validatedData = $request->validate([
          'video' => 'required',
         ]);
            $data=$request->all();
            $video=$data['video'];
            $input = time().'.'.$video->getClientOriginalExtension();
            $destinationPath = public_path().'\uploads\videos';
            dd($destinationPath);
            $success = $video->move($destinationPath, $input);
        // if($success){
        //                 $video = new Videos();
        //                 $video->video = $input;
        //                 $video->save();
        //                 return redirect()->route('videos');
        //             }

       

    }


    public function show(AdminRequest $request)
    {
        // $inputs = $request->only('name', 'email', 'permission_id');

        // $inputs['password'] = bcrypt($request->password);
        // $inputs['image']    = $request->image->store('admins');

        // User::create($inputs);

        // return response('success');

        return 'hi';
    }

   
}
