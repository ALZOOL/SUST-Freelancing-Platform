<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\AcceptedTeam;
class TeamRequestsController extends Controller
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
    //SHOW SUBMITTED-TASKS

    /* public function store_team_requests(){
        $result = DB::table('team_requests')->first();
        $result2 = DB::table('team_requests')->get();
        
        $project= $result->project_id;
        $client= $result->client_id;
        $student= $result->student_id;
        
        $project_name = DB::table('projects')->where('id', '=', $project)->get();
        $client_name = DB::table('clients')->where('id', '=', $client)->get();
        $student_name = DB::table('students')->where('id', '=', $student)->get();
        $data=new AcceptedTeam;
        $data->project_name=$project_name->title;
        $data->client_name=$client_name->email;
        $data->student_name=$student_name->first_name;
        $data->student_role=$student_name->role;
        
        $data->save();

        //return view('ManagerDashboard.team_requests',compact('result2','client_name','project_name','student_name'));
        
        //$project_name = DB::table('projects')->where('id', '=', $project)->get();
        //$client_name = DB::table('clients')->where('id', '=', $client)->get();
        //$student_name = DB::table('students')->where('id', '=', $student)->get();
        
        //$project_name = DB::select("select title from projects where id=$project;");
        //$client_name = DB::select("select name from clients where id=$client;");
        //$student_name = DB::select("select first_name from students where id=$student;");
        //$data=DB::select("select role from students where id=$student;"); 
        
        }
     */
    
    //##################

    //SHOW TEAMS-REQUESTS

    public function team_requests(){
        $users = DB::select('select * from student_join_projects');
        $teams = DB::select('select * from projects_teams');
        return view('ManagerDashboard.team_requests')
        ->with(compact('users'))
        ->with(compact('teams'));
        
        }


    //ACCEPT-TEAM

    public function accept_team($id)
    {
        $result = DB::table('student_join_projects')->where('id',$id)->first();
        $data=new AcceptedTeam;
        //$data->id=$result->id;
        $data->project_id=$result->project_id;
        $data->project_name=$result->project_name;
        $data->client_id=$result->client_id;
        $data->client_email=$result->client_email;
        $data->student_id=$result->student_id;
        $data->student_name=$result->student_name;
        $data->student_role=$result->student_role;
        
        DB::table('student_join_projects')->where('id',$id)->delete();
        $data->save();
        
        return back();
        //return redirect()->back();
        return view('test');
    }
    //#################
    
    
}
