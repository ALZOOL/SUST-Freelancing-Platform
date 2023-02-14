<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
class TaskController extends Controller
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

    public function show_tasks(){
        $users = DB::select('select * from tasks');
        $data=[];

        foreach ($users as $user) {
            $data[]=[
                'id'=>$user->id,
                'title'=>$user->title,
                'category'=>$user->category,
                'description'=>$user->description,
            ];
        }
        return response()->json([
            'data'=>$data

        ]);
        //return response()->json(view('ManagerDashboard.tasks',['users'=>$users])->render());
        //return  view('ManagerDashboard.tasks',['users'=>$users]);
        }
    //ADD NEW TASK 
    public function add_web_task(Request $request)
    {
        $data=new Task;
        $data->title=$request->title;
        $data->category='web devolopment';
        $data->description=$request->description;
        
        $data->save();

        //return redirect()->back();
        return view('test');
    }
    
    public function add_security_task(Request $request)
    {
        $data=new Task;
        $data->title=$request->title;
        $data->category='security';
        $data->description=$request->description;
        
        $data->save();

        //return redirect()->back();
        return view('test');
    }

    public function add_desgin_task(Request $request)
    {
        $data=new Task;
        $data->title=$request->title;
        $data->category='desgin';
        $data->description=$request->description;
        
        $data->save();

        //return redirect()->back();
        return view('test');
    }

    public function test_fun()
    {
        return view('test');
    }
    //###################### 

    //EDIT DELETE TASKS
    public function edit_task($id){
    	$myTask = DB::table('tasks')->where('id',$id)->first();
    	return view('ManagerDashboard.tasks.edit',compact('myTask'));
    }

    public function update_task(Request $request,$id){

    	DB::table('tasks')->where('id',$id)->update([

    		'title'=>$request->title,
    		'description'=>$request->description
    	]);
    	return redirect()->route('/Tasks');
    } 

    public function delete_task($id){

        DB::table('tasks')->where('id',$id)->delete();
        //DB::table('posts')->truncate();
        return redirect()->route('/Tasks');

    }
    //####################
}
