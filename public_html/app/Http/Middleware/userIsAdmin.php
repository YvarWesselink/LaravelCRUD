<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class userIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
	// get the session user (wich is unique).
        if(\Auth::user() != null){
		$userSession = \Auth::user()->name;
	}else{
		$userSession = "";
	}
	
	//get the confirmed admins
	$confirmedAdmin = \DB::table('admins')->where("name",  $userSession )->value("created_at");

	/**
	* we are doing an admin check here for security purposes.
	* we are checking if the user is in the admins table by checking if they have a created_at date
	* if they dont have this and their name is the superAdmins (yvar) we will let them trough.
	*/
	
	if($userSession == "yvar" && $confirmedAdmin == ""){
		return $next($request); // check if we are the SUPER ADMIN (now yvar, in public apps would be called superAdmin.)
	}else{
		if($confirmedAdmin != ""){ // checks if we have a date added. if we do we are admin. 
			return $next($request);
		}else{
			return redirect()->route('dashboard');
		}
	}

	}
}
