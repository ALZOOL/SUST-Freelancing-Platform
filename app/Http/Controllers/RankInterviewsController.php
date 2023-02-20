<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\RankInterview;
class RankInterviewsController extends Controller
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
    //SHOW RANK-INTERVIEW-REQUESTS

    public function rank_interview_requests(){
        $users = DB::select('select * from interview_requests');
        $results = DB::select('select * from rank_interviews');//for showing accepted rank interviews
        return view('manager.rank_interview')
        ->with(compact('users'))
        ->with(compact('results'));
        }

    //##################

    
    //ACCEPT INTERVIEW REQUESTS

    public function accept_interview_request($id)
    {
        $result = DB::table('interview_requests')->where('id',$id)->first();
        $data=new RankInterview;
        $data->id=$result->id;
        $data->student_name=$result->student_name;
        $data->role=$result->role;
        $data->current_rank=$result->current_rank;
        $data->next_rank=$result->next_rank;
        
        DB::table('interview_requests')->where('id',$id)->delete();
        $data->save();
        
        return back();
    }
    
    //###################


    //UPGRADE RANKE
    public function upgrade_rank($id,$next_rank){
        //$data=new Student;
        //$data->id=$result->id; 
        //$result = DB::table('rank_interviews')->get();
        //$resultArray = $result->toArray();
        DB::table('students')->where('id',$id)->update([

    		'rank'=>$next_rank,
    	]);
    	DB::table('rank_interviews')->where('id',$id)->delete();
        return back();
        //return redirect()->route('/Roadmaps');
    }

    //##################
        
}
