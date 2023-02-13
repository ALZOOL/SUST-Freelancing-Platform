<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class SubmittedTasksController extends Controller
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

    public function show_web(){
        //$users = DB::select('select * from submitted_tasks');
        $users = DB::table('submitted_tasks')->where('category', '=', 'Web')->get();
        return view('ManagerDashboard.submitted_tasks.web',['users'=>$users]);
        }
    
    public function show_security(){
        //$users = DB::select('select * from submitted_tasks');
        $users = DB::table('submitted_tasks')->where('category', '=', 'Security')->get();
        return view('ManagerDashboard.submitted_tasks.security',['users'=>$users]);
        }
    
    public function show_design(){
        //$users = DB::select('select * from submitted_tasks');
        $users = DB::table('submitted_tasks')->where('category', '=', 'Design')->get();
        return view('ManagerDashboard.submitted_tasks.design',['users'=>$users]);
        }


    //##################

    //CUSTOM-POINTS

    public function custom(Request $request,$student_name,$id){
        $users = DB::table('students')->where('first_name', '=', $student_name)->value('points');
        $x = $users;
        $y=$request->points_n;
        $z=$x+$y;
    	DB::table('students')->where('first_name',$student_name)->update([
    		'points'=>$z,
    	]);
    	DB::table('submitted_tasks')->where('id',$id)->delete();
        return back();
    }

    //####################

    //FULL-POINTS

    public function full(Request $request,$student_name,$id){
        $users = DB::table('students')->where('first_name', '=', $student_name)->value('points');
        $x = $users;
        $y=$request->full;
        $z=$x+$y;
    	DB::table('students')->where('first_name',$student_name)->update([
    		'points'=>$z,
    	]);
    	DB::table('submitted_tasks')->where('id',$id)->delete();
        return back();
    }

    //####################

    

        
    
    
}
