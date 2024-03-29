<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Accepted_clients_request;
class ProjectsRequestsController extends Controller
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
    //SHOW PROJECTS-REQUESTS

    public function show_projects_requests(){
        $users = DB::select('select * from client_project_requests');
        return view('manager.projects_requests',['users'=>$users]);
        }

    //##################

    //ACCEPT-PROJECT-REQUEST

    public function accept_project_request($id)
    {
        $result = DB::table('client_project_requests')->where('id',$id)->first();
        $data=new Accepted_clients_request;
        $data->id=$result->id;
        $data->client_id=$result->client_id;
        $data->title=$result->title;
        $data->category=$result->category;
        $data->description=$result->description;
        DB::table('client_project_requests')->where('id',$id)->delete();
        $data->save();
        
        return back();
        //return view('projects_requests');
        //return view('ManagerDashboard.projects_requests');
    }
    
    //DELETE-REQUESTS-AFTER-ACCEPTED
    /* public function delete_request($id){

        DB::table('requests')->where('id',$id)->delete();
        //return redirect()->route('/Roadmaps');

    } */

    //####################    
    
}
