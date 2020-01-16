<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        switch ($guard) {
            case 'clients':
                if (Auth::guard('clients')->check()) {
                    return redirect('/clients/statistics');
                }
                break;

            case 'providers':
                if (Auth::guard('providers')->check()) {
                    return redirect('/providers/statistics');
                }
                break;
            
            default:
                if (Auth::guard($guard)->check()) {
                    return redirect('/backend/dashboard/statistics');
                }
                break;
        }
        
        return $next($request);
    }
}
