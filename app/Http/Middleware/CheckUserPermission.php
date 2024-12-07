<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\modules;
use App\Models\role_access;

class CheckUserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $requestType, ...$permission)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect('/login'); // or wherever you want to redirect unauthenticated users
        }

        // Get permissions from db
        $parsedUrl = parse_url($request->url());
        $segments = explode('/', trim($parsedUrl['path'], '/'));
        $requestedSlug = !empty($segments) ? $segments[0] : null;

        if($requestedSlug=="rep-by-acc-name" OR $requestedSlug=="rep-by-acc-grp" OR $requestedSlug=="rep-daily-register" && $requestType=="report"){
            if((session('user_access')[10]['module_id'])==11 && (session('user_access')[10]['view']) == 1 || (session('user_access')[11]['module_id']) == 12 && (session('user_access')[11]['view']) == 1 || (session('user_access')[12]['module_id']) == 13 && (session('user_access')[12]['view']) == 1 || (session('user_access')[13]['module_id']) == 14 && (session('user_access')[13]['view']) == 1 || (session('user_access')[14]['module_id']) == 15 && (session('user_access')[14]['view']) == 1 || (session('user_access')[15]['module_id']) == 16 && (session('user_access')[15]['view']) == 1 || (session('user_access')[16]['module_id']) == 17 && (session('user_access')[16]['view']) == 1 || (session('user_access')[17]['module_id']) == 18 && (session('user_access')[17]['view']) == 1){
                return $next($request);
            }
            else{
                return redirect('/unauthorized');
            }
        }

        else if($requestedSlug=="rep-godown-by-item-name" OR $requestedSlug=="rep-godown-by-group-name" && $requestType=="report"){
            if((session('user_access')[18]['module_id']) == 19 && (session('user_access')[18]['view']) == 1 || (session('user_access')[19]['module_id']) == 20 && (session('user_access')[19]['view']) == 1)
            {
                return $next($request);
            }
            else{
                return redirect('/unauthorized');
            }
        }
        
        else if($requestedSlug=="rep-commissions" && $requestType=="report"){
            if(session('user_role')==1 OR session('user_role')==2){
                return $next($request);
            }
            else{
                return redirect('/unauthorized');
            }
        }

        else{
            $module = modules::where('slug',$requestedSlug)->first();
            $moduleID = $module['id'];
    
            $checkaccess = role_access::where('role_id',session('user_role'))
            ->where('module_id',$moduleID)
            ->select($requestType)
            ->first();
            
            if($checkaccess[$requestType]==1){
                return $next($request);
            }
            else{
                return redirect('/unauthorized');
            }
        }
    }
}
