<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\userInfo;
use App\Models\admins;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class dataForAdmin extends Controller
{
    public function fetchAllUsers(){
        // prepares user data in array where user and data are put in the same key, so that blade can access both from same user
        $usersData = User::all();
        $allUserData = [];

        // for each where we go to all the instances in userData where we only want the value !FIX make it more simple or exchange for each loop if i have time
        foreach ( $usersData as $user => $value) {
            $usersInfo =userInfo::where('user_id', $value['id'])->first();

                $singleUserData = [$value->id => [ "user"  => $value, "info" => $usersInfo]];
                $allUserData += $singleUserData; 
        };

        // check if logged in user is admin.
        $isAdmin = admins::where('user_id', Auth::id())->first();
        $loggedInIsAdminUser = false;
        if($isAdmin != "" ){
            $loggedInIsAdminUser = true;
        }

        return  view('info') 
            ->with('allUserData' , $allUserData) 
            ->with('loggedInIsAdminUser' , $loggedInIsAdminUser);
    }

    public function updateInfoUser(Request $request, $id){
        // get global data for function
        $updatedUser = User::findOrFail($id);
        $userData = userInfo::where('user_id', $updatedUser['id'])->first();

        // check for the active toggle, 0 is inactive, 1 is active
        $activeUserToggle = 0;
        if($request->input('active') == true){
            $activeUserToggle = 1;
        }

        // saves the values to user info
        $userData->update([
            'level' => $request->input('level', ''),
            'active' => $activeUserToggle,
        ]);

        // promotes users if their level is 1 or higher otherwise deletes their admin state
        if($request->input('level', '') != ""  && $request->input('level') > 1 ){
            // variable to store and compare the admin from db
            $promotedUser = admins::where('user_id', $id)->first();
            if($promotedUser != ""){
                // Updates level of user
                $promotedUser->update([
                    'level' => $request->input('level'),
                ]);
            }else{
                // creates new data in admins for promoted user
                $promotedUserInfo = new admins([
                    'user_id'=> $id,
                    'name' => $updatedUser->name,
                    'level' => $request->input('level'),
                ]);
                $updatedUser->admins()->save($promotedUserInfo);
            }
        }else{
            // deletes admin perms for users with level below 1
            admins::where('user_id', $id)->delete();
        }

        return redirect('info');
    }
}