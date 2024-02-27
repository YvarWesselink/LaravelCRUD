<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\userInfo;
use App\Models\admins;
use Illuminate\Support\Facades\Auth;

class CheckBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // gets admin table and user data by logged in ID
        $isAdmin = admins::where('user_id', Auth::id())->first();
        $userData = userInfo::where('user_id',Auth::id())->first();
        
        $userBanned = false;
        if($userData != ""){
           
           if($userData->active == 0 ){
            $userBanned = True;
           }
        }
        // checks if user is disabeld
        if(auth()->check() && $userBanned == true){
            // if user is admin does nothing, else returns user to login
            if( $isAdmin == ""){
                Auth::logout();
    
                $request->session()->invalidate();
    
                $request->session()->regenerateToken();
                
                return redirect()->route('login')->with('error', 'Your Account is suspended, please contact Admin.');
            }
        }
    
        return $next($request);
    }
}
