<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
class ApprovedProjectsController extends Controller
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
    //SHOW APPROVED-PROJECTS

    public function show_accepted_requests(){
        $users = DB::select('select * from accepted_requests');
        return view('ManagerDashboard.approved_projects',['users'=>$users]);
        }

    //##################
    
    //ADD NEW APPROVED-PROJECT-REQUEST
    
    public function publish(Request $request)
    {
        $data=new Project;
        $data->client_id=$request->client_id;
        $data->title=$request->title;
        $data->category=$request->category;
        $data->description=$request->description;
        $data->frontend=$request->frontend;
        $data->backend=$request->backend;
        $data->designer=$request->designer;
        
        DB::table('accepted_requests')->where('id',$request->id)->delete();
        $data->save();
        
        return back();

        //return redirect()->back();
        //return view('test');
    }
    //########################

    //SHOW APPROVED-PROJECTS

    public function show_approved_projects(){
        $users = DB::select('select * from projects');
        return view('ManagerDashboard.approved_projects',['users'=>$users]);
        }

    //##################
    
    
}
