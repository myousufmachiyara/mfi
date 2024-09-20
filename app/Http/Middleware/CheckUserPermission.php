<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $permission)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect('/login'); // or wherever you want to redirect unauthenticated users
        }

        // Get permissions from session
        $permissions = session('user_permissions', []);

        // Check if the user has the required permission
        if (!in_array($permission, $permissions)) {
            return redirect('/unauthorized'); // Redirect if the user does not have permission
        }

        return $next($request);
    }
}
