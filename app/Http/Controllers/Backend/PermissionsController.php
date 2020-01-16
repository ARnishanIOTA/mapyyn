<?php

namespace App\Http\Controllers\Backend;

use App\Models\Page;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::all();

        return view('backend.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pages = Page::all();
        return view('backend.permissions.create', compact('pages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|unique:permissions,name',
            'roles'          => 'required',
        ]);
        
        if(request('roles') != null){
            DB::transaction(function() {
                $permission = Permission::create(['name' => request('name')]);
                foreach(request('roles') as $page => $roles){
                    $roles['page'] = $page;
                    $roles['permission_id'] = $permission->id;
                    Role::create($roles);
                }
            });
        }
        return response('success');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permission = Permission::with('roles')->where('id', $id)->where('is_superadmin', 0)->first();
        $pageObject = new Page;

        if($permission == null){
            return redirect('backend/errors/404');
        }
        return view('backend.permissions.update', compact('permission', 'pageObject'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'name'   => 'required|unique:permissions,name,'.$id,
        ]);
        if(request('roles') != null){
            DB::transaction(function() use ($id){
                Role::where('permission_id', $id)->delete();
                $permission = Permission::where('id', $id)->update(['name' => request('name')]);
                foreach(request('roles') as $page => $roles){
                    $roles['page'] = $page;
                    $roles['permission_id'] = $id;
                    Role::create($roles);
                }
            });
        }else{
            return back()->with('danger', 'You must choose some roles');

        }
        return back()->with('success', 'Permission Created Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Permission::where('id', $id)->delete();
    }
}
