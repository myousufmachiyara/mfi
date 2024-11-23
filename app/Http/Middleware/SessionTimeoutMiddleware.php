<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\users;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeoutMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::has('user_id') && now()->diffInMinutes(Session::get('last_activity_time')) > config('session.lifetime')) {
            // Log the user out and update the database
            $userId = Session::get('user_id');
            users::where('id', $userId)->update(['is_login' => 0]);
            Auth::logout();
            Session::flush();

            // Redirect to login page with a session timeout message
            return redirect()->route('login')->withErrors(['error' => 'Your session has expired. Please log in again.']);
        }

        // Update the session last activity time
        Session::put('last_activity_time', now());

        return $next($request);
    }
}
