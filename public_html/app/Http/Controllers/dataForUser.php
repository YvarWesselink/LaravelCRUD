<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\userInfo;
use App\Models\admins;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class dataForUser extends Controller
{

    public function userInfo(Request $request, $id){
        // validation of the required inputs
        $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer',
            'tel' => 'required|integer',
            'place' => 'required|string',
            'intro' => 'required|string|max:256',
            'adress' => 'required|string',
        ]);

        // Fetch current user from db
        $currentUser = User::findOrFail($id);

        $userData = userInfo::where('user_id', $currentUser['id'])->first();
        if($userData != ""  ){
            // If user data exist, update userdata with new values
            $userData->update([
                'name' => $request->input('name'),
                'age' => $request->input('age'),
                'tel' => $request->input('tel'),
                'place' => $request->input('place'),
                'intro' => $request->input('intro'),
                'adress' => $request->input('adress'),
                'socialFB' => $request->input('socialFB', ''),
                'socialLI' => $request->input('socialLI', ''),
                'socialIG' => $request->input('socialIG', ''),
                'socialTW' => $request->input('socialTW', ''),
                'socialLT' => $request->input('socialLT', ''),
                'socialTE' => $request->input('socialTE', ''),
                'notes' => $request->input('adminNote', ''),
                'level' => $request->input('adminLevel', 0)
            ]);
        }else{
            // Creates user data in case acount doesnt have any.
            $userInfo = new userInfo([
                'user_id'=> $request->input('user_id'),
                'name' => $request->input('name'),
                'age' => $request->input('age'),
                'tel' => $request->input('tel'),
                'place' => $request->input('place'),
                'intro' => $request->input('intro'),
                'adress' => $request->input('adress'),
                'socialFB' => $request->input('socialFB', ''),
                'socialLI' => $request->input('socialLI', ''),
                'socialIG' => $request->input('socialIG', ''),
                'socialTW' => $request->input('socialTW', ''),
                'socialLT' => $request->input('socialLT', ''),
                'socialTE' => $request->input('socialTE', ''),
                'notes' => $request->input('adminNote', ''),
                'level' => $request->input('adminLevel', '')
            ]);
            $currentUser->userInfo()->save($userInfo);
        }

        // return to the view after either safing or updating our data :D
        return redirect('acount/'.$request->input('user_id') . '');
    }

    public function fetchUserInfo($urlId){
        //Gets all the data we need for this function from respective databases
        $currentUser = User::findOrFail($urlId);
        $currentUserData = userInfo::where('user_id', $currentUser->id)->first();
        $isAdmin = admins::where('user_id', $currentUser->id)->first();

        // a check to make sure the logged in user is an admin
        $loggedInIsAdminUser = false;
        if($isAdmin != "" ){
            $loggedInIsAdminUser = true;
        }

        // // check toe voegen of logged in user the owner of the details is
        // $notOwnerOfDetails = true;        
        // if(Auth::id() == $urlId){
        //     $notOwnerOfDetails = false;
        // }
        


        $notOwnerOfDetails = true;
        if($currentUserData != "" ){
            // check toe voegen of logged in user the owner of the details is
            if($currentUserData->user_id == Auth::id()){
                $notOwnerOfDetails = false;
            }
        }elseif($urlId == $currentUser->id){
            $notOwnerOfDetails = false;
        }

        // returns everything so blade can work 
        return  view('acount') 
        ->with('currentUserData' , $currentUserData) 
        ->with('notOwnerOfDetails', $notOwnerOfDetails)
        ->with('loggedInIsAdminUser' , $loggedInIsAdminUser);   
    }
}