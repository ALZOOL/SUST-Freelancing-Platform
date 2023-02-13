<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EditProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    public function edit_profile()
    {
        return view('test');
    }
    
    
    //EDIT MANAGER USERNAME-PASSWORD-EMAIL
    //EDIT MANAGER USERNAME
    public function manager_username($id){
    	$data = DB::table('managers')->where('id',$id)->first();
    	return view('ManagerDashboard.profile.manager_username',compact('data'));
    }

    public function m_username_update(Request $request,$id){

    	DB::table('managers')->where('id',$id)->update([

    		'name'=>$request->username,
    	]);
    	return view('ManagerDashboard.profile.manager_profile');
    } 

       //####################

}
