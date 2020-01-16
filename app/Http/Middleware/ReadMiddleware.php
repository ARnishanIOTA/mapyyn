<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;

class ReadMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()->permission->is_superadmin == 1){
            return $next($request);
        }else{
           $url   = url()->current();
           $roles = Role::where('permission_id', auth()->user()->permission_id)->get();
           foreach($roles as $role){
               $page = $role->page;
               if(strpos($url, $page)){
                    if($role->is_read == 1){
                        return $next($request);
                    }
               }
           }
           return redirect('/backend/errors');
        }
    }
}
