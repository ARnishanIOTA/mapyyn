<?php

namespace App\Http\Controllers\Backend;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AdminRequest;
use App\Models\Permission;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = User::with('permission')->get();
        return view('backend.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('backend.admins.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminRequest $request)
    {
        $inputs = $request->only('name', 'email', 'permission_id');

        $inputs['password'] = bcrypt($request->password);
        $inputs['image']    = $request->image->store('admins');

        User::create($inputs);

        return response('success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $permissions = Permission::all();
        return view('backend.admins.update', compact('user', 'permissions'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(AdminRequest $request, User $user)
    {
        $inputs = $request->only('name', 'email', 'permission_id');

        if($request->password != null){
            $inputs['password'] = bcrypt($request->password);
        }

        if($request->image != null){
            $file = public_path("uploads/$user->image");
            if(file_exists($file)){
                unlink($file);
            }
            $inputs['image']    = $request->image->store('admins');
        }

        $user->update($inputs);

        return response('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if($user->permission->is_superadmin == 1){
            abort(422);
        }
        if($user->id != 1){
            if($user->image != null){
                $file = public_path("uploads/$user->image");
                if(file_exists($file)){
                    unlink($file);
                }
            }
            $user->delete();
            return response('success');
        }

        abort(404);
    }
}
