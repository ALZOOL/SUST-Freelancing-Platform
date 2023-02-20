<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Accepted_clients_request;
use App\Models\client_projects;
use App\Models\Task;
use App\Models\Roadmap; 
use App\Models\RankInterview;
use App\Models\StudentJoinProject;    
use App\Models\ProjectsTeam;
use App\Models\ProjectsTeamMember;
use App\Models\StudentNotification;
use App\Models\ClientNotification;
use App\Models\StudentTeam;
use App\Models\Student;

class ManagerController extends Controller
{
    
    public function register()
    {
        $data['title'] = 'Register';
        return view('manager/register', $data);
    }

    public function register_action(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:tb_manager',
            'password' => 'required',
            'password_confirm' => 'required|same:password',
        ]);
        $manger = new Manager([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);
        $manger->save();

        return redirect()->route('manager_login')->with('success', 'Registration success. Please login!');
    }
    //MANAGER LOGIN-LOGOUT SYSTEM
    public function manager_login()
    {
        $data['title'] = 'Login';
        return view('manager.manager_login', $data);
    }

    public function manager_login_action(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        if (Auth::guard('manager')->attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'Manager'])) {
            $request->session()->regenerate();
            return view('manager.manager_home');
            //return redirect()->intended('/');
        }

        return back()->withErrors([
            'password' => 'Wrong username or password',
        ]);
    }
    public function manager_logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('manager/login');
    }
    //************************************** */

    //ADMIN LOGIN-LOGOUT SYSTEM
    public function admin_login()
    {
        $data['title'] = 'Login';
        return view('manager.admin_login', $data);
    }

    public function admin_login_action(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        if (Auth::guard('manager')->attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'Admin'])) {
            $request->session()->regenerate();
            //$users = DB::select('select * from managers');
            $users = DB::table('managers')->where('role', 'Manager')->orwhere('role', 'Teacher')->get();
            //GET THE NUMBERS OF SYSTEM USERS $$$$$$
            //Retrieve data from database table
            $clients_num = DB::table('clients')->get();
            $managers_num = DB::table('managers')->get();
            $students_num = DB::table('students')->get();

            //Convert the data into an array
            $clients_array = $clients_num->toArray();
            $managers_array = $managers_num->toArray();
            $students_array = $students_num->toArray();

            //Get the length of the array
            $clients_number = count($clients_array);
            $managers_number = count($managers_array);
            $students_number = count($students_array);
            //$$$$$$$$$$$$$
            //$system_users_numbers=array($clients_number,$managers_number,$students_number);
            //return view('admin',['users'=>$users]);
            return view('manager.admin_home')
            ->with(compact('users'))
            ->with(compact('clients_number'))
            ->with(compact('managers_number'))
            ->with(compact('students_number'));
            
        }

        return back()->withErrors([
            'password' => 'Wrong username or password',
        ]);
    }
    public function admin_logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('admin/login');
    }
    //************************************** */


    //teacher_ LOGIN-LOGOUT SYSTEM
    public function teacher_login()
    {
        $data['title'] = 'Login';
        return view('manager.teacher_login', $data);
    }

    public function teacher_login_action(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        if (Auth::guard('manager')->attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'Teacher'])) {
            $request->session()->regenerate();
            return view('manager.teacher_home');
            //return redirect()->intended('/');
        }

        return back()->withErrors([
            'password' => 'Wrong username or password',
        ]);
    }
    public function teacher_logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('teacher/login');
    }
    //************************************** */

    //SHOW SYSTEM-MANAGERS

    public function show_managers(){
        $users = DB::table('managers')->where('role', 'Manager')->orwhere('role', 'Teacher')->get();
        //GET THE NUMBERS OF SYSTEM USERS $$$$$$
        //Retrieve data from database table
        $clients_num = DB::table('clients')->get();
        $managers_num = DB::table('managers')->get();
        $students_num = DB::table('students')->get();

        //Convert the data into an array
        $clients_array = $clients_num->toArray();
        $managers_array = $managers_num->toArray();
        $students_array = $students_num->toArray();

        //Get the length of the array
        $clients_number = count($clients_array);
        $managers_number = count($managers_array);
        $students_number = count($students_array);
        //$$$$$$$$$$$$$
        //$system_users_numbers=array($clients_number,$managers_number,$students_number);
        //return view('admin',['users'=>$users]);
        return view('manager.admin_home')
        ->with(compact('users'))
        ->with(compact('clients_number'))
        ->with(compact('managers_number'))
        ->with(compact('students_number'));
        }

    //##################
    
    //ADD NEW MANAGER 
    public function addManager(Request $request)
    {
        $data=new Manager;
        $data->name=$request->name;
        $data->email=$request->email;
        $data->role=$request->role;
        //$data->password=$request->password;
        $data->password=bcrypt($request->password);
        //'password' => Hash::make($data['password']),

        $data->save();

        $users = DB::table('managers')->where('role', 'Manager')->orwhere('role', 'Teacher')->get();
        //GET THE NUMBERS OF SYSTEM USERS $$$$$$
        //Retrieve data from database table
        $clients_num = DB::table('clients')->get();
        $managers_num = DB::table('managers')->get();
        $students_num = DB::table('students')->get();

        //Convert the data into an array
        $clients_array = $clients_num->toArray();
        $managers_array = $managers_num->toArray();
        $students_array = $students_num->toArray();

        //Get the length of the array
        $clients_number = count($clients_array);
        $managers_number = count($managers_array);
        $students_number = count($students_array);
        //$$$$$$$$$$$$$
        //$system_users_numbers=array($clients_number,$managers_number,$students_number);
        //return view('admin',['users'=>$users]);
        return view('manager.admin_home')
        ->with(compact('users'))
        ->with(compact('clients_number'))
        ->with(compact('managers_number'))
        ->with(compact('students_number'));
        
    }
    
    
    //################

    //EDIT MANAGERS
    public function edit_system_managers($id){
    	$data = DB::table('managers')->where('id',$id)->first();
    	return view('manager.managers_edition',compact('data'));
    }

    public function update_system_managers(Request $request,$id){

    	DB::table('managers')->where('id',$id)->update([

    		'name'=>$request->name,
    		'email'=>$request->email,
            'role'=>$request->role,
            'password'=>bcrypt($request->password),
            //$data->password=bcrypt($request->password);
    	]);
        $users = DB::table('managers')->where('role', 'Manager')->orwhere('role', 'Teacher')->get();
        //GET THE NUMBERS OF SYSTEM USERS $$$$$$
        //Retrieve data from database table
        $clients_num = DB::table('clients')->get();
        $managers_num = DB::table('managers')->get();
        $students_num = DB::table('students')->get();

        //Convert the data into an array
        $clients_array = $clients_num->toArray();
        $managers_array = $managers_num->toArray();
        $students_array = $students_num->toArray();

        //Get the length of the array
        $clients_number = count($clients_array);
        $managers_number = count($managers_array);
        $students_number = count($students_array);
        //$$$$$$$$$$$$$
        //$system_users_numbers=array($clients_number,$managers_number,$students_number);
        //return view('admin',['users'=>$users]);
        return view('manager.admin_home')
        ->with(compact('users'))
        ->with(compact('clients_number'))
        ->with(compact('managers_number'))
        ->with(compact('students_number'));
    } 

    public function delete_system_managers($id){

        DB::table('managers')->where('id',$id)->delete();
        $users = DB::table('managers')->where('role', 'Manager')->orwhere('role', 'Teacher')->get();
        //GET THE NUMBERS OF SYSTEM USERS $$$$$$
        //Retrieve data from database table
        $clients_num = DB::table('clients')->get();
        $managers_num = DB::table('managers')->get();
        $students_num = DB::table('students')->get();

        //Convert the data into an array
        $clients_array = $clients_num->toArray();
        $managers_array = $managers_num->toArray();
        $students_array = $students_num->toArray();

        //Get the length of the array
        $clients_number = count($clients_array);
        $managers_number = count($managers_array);
        $students_number = count($students_array);
        //$$$$$$$$$$$$$
        //$system_users_numbers=array($clients_number,$managers_number,$students_number);
        //return view('admin',['users'=>$users]);
        return view('manager.admin_home')
        ->with(compact('users'))
        ->with(compact('clients_number'))
        ->with(compact('managers_number'))
        ->with(compact('students_number'));

    }

    //####################


    //SHOW PROJECTS-REQUESTS
///notice from mahdi retutn this as json responce
    public function show_projects_requests(){
        $requests = DB::table('client_project_requests')
                ->join('clients', 'client_project_requests.client_id', '=', 'clients.client_id')
                ->select('client_project_requests.id', 'clients.email','client_project_requests.project_title','client_project_requests.project_description','client_project_requests.project_file_path')
                ->get();
                // return response()->json([
                //     'data' => $requests
                // ]);    
        // $users = DB::select('select * from client_project_requests');
        return view('manager.projects_requests',['users'=>$requests]);
        }

    //##################

    //ACCEPT-PROJECT-REQUEST

    public function accept_project_request($id)
    {
        $result = DB::table('client_project_requests')->where('id',$id)->first();
        $data=new Accepted_clients_request;

        $data->id=$result->id;
        $data->client_id=$result->client_id;
        $data->title=$result->project_title;
        $data->description=$result->project_description;
        $data->project_file_path=$result->project_file_path;
        $data->save();
        //sending messages to client_notifications table
        //$result_2 = DB::table('clients')->where('client_id',$result->client_email)->first();
        $data_2=new ClientNotification;
        $data_2->client_id=$result->client_id;
        $data_2->message_id=4;
        echo $data_2;
        $data_2->save();
        DB::table('client_project_requests')->where('id',$id)->delete();
        
        return response()->json(['ok' => "client project has been accepted"], 200);
        //return view('projects_requests');
        //return view('ManagerDashboard.projects_requests');
    }
    
    //####################

    //REJECT PROJECTs REQUESTS
    public function reject_project_request(Request $request,$id){
        $result = DB::table('client_project_requests')->where('id',$id)->first();
        //$result_2 = DB::table('clients')->where('email',$result->client_email)->first();
         //sending messages to notifications table
         $data_2=new ClientNotification;
         $data_2->client_id=$result->client_id;
         $data_2->message_id=$request->reject;
          //echo $data_2;
         $data_2->save();
         DB::table('client_project_requests')->where('id',$id)->delete();

         return response()->json(['ok' => "client project has been rejected "], 200);
 
     }
     
     //###################
 

    //************************************ */

    //************************************ */
    //SHOW APPROVED-PROJECTS

    public function show_accepted_requests(){
        $users = DB::table('accepted_clients_requests')->join('clients', 'accepted_clients_requests.client_id', '=', 'clients.client_id')
        ->select('accepted_clients_requests.id','accepted_clients_requests.client_id', 'clients.email','accepted_clients_requests.title','accepted_clients_requests.description','accepted_clients_requests.project_file_path')
        ->get();
        $projects = DB::table('client_projects')->join('clients', 'client_projects.client_id', '=', 'clients.client_id')
        ->select('client_projects.id', 'clients.email','client_projects.title','client_projects.category','client_projects.description','client_projects.deadline','client_projects.status','client_projects.rank','client_projects.frontend',
        'client_projects.backend','client_projects.security','client_projects.ui_ux')
        ->get();; //for showing accepted clients projects
       // $teams = DB::select('select * from projects_teams');

       // echo $users;
        //return response()->json($users);
        // $ys= DB::table('students')->where('student_id',$team->student_id)->get();    
        // $students = Student::all()->toArray();
        // $records = Model::where('column', 'value')->get()->toArray();
        // foreach($teams as $team){
        //     //$x=$team->student_id;
        //     $ys= DB::table('students')->where('student_id',$team->student_id)->get();
        //     $students = DB::table('users')->where('id', 1)->value('name');
        //     //$students = Student::where('student_id', $team->student_id)->get()->toArray();
        //     return view('manager.approved_projects')
        //     ->with(compact('users'))
        //     ->with(compact('ys'))
        //     ->with(compact('projects'));
        // }

        return view('manager.approved_projects')
        ->with(compact('users'))
        //->with(compact('ys'))
        ->with(compact('projects'));
        
        }

    //##################
    
    //ADD NEW APPROVED-PROJECT-REQUEST
    
    public function publish(Request $request)
    {


        $request->validate([
            'title' => 'required',
            'category' => 'required',
            'description' => 'required',
            'deadline' => 'required',
            'rank' => 'required',
        ]);
        $data = new client_projects([
            'client_id' => $request->client_id,
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'rank' => $request->rank,
            'forntend' => $request->frontend,
            'backend' => $request->backend,
            'ui_ux' => $request->ui_ux,
            'security' => $request->security,
            'team_count' => $request->security+$request->ui_ux+$request->backend+$request->frontend,            
        ]);
        //echo $data;
        $data->save();

        //sending messages to client_notifications table
        $result_2 = DB::table('clients')->where('email',$request->client_email)->first();
        $data_2=new ClientNotification;
        $data_2->client_id=$result_2->client_id;
        $data_2->message_id=5;
        $data_2->save();

        DB::table('accepted_clients_requests')->where('id',$request->id)->delete();

        return response()->json(['ok' => "client project has been approved  "], 200);
        //return back();

        //return redirect()->back();
        //return view('test');
    }
    //########################

    //CANCELING PROJECTS PUBLISHING
    public function cancel_publish(Request $request,$id,$email){
        //$result = DB::table('accepted_clients_requests')->where('id',$request->id)->first();
        $result_2 = DB::table('clients')->where('email',$email)->first();
         //sending messages to notifications table
         $data_2=new ClientNotification;
         $data_2->client_id=$result_2->client_id;
         $data_2->message_id=$request->cancel;
          
         $data_2->save();
         DB::table('accepted_clients_requests')->where('id',$id)->delete();
         return response()->json(['ok' => "client project has been rejected "], 200);
         //return back();
 
     }
     
     //###################
     
     //UPDATE PROJECT PROGRESS STATUS
     public function update_status(Request $request,$project_id){
        $team_id=DB::table('client_projects')->where('id',$project_id)->first();
        $team_check= $team_id->team_id;

        $max_allowed = DB::table('client_projects')->where('id',$project_id)->first();
        $student_count = DB::table('projects_teams')->where('team_id',$team_check)->get();
        //$users_all_2 = ProjectsTeam::all()->toArray();
        $max_allowed_2=$max_allowed->team_count;
        $student_count_2=count($student_count);
        
        if(!empty($student_count_2>=$max_allowed_2)) { 
            DB::table('client_projects')->where('id',$project_id)->update([

                'status'=>$request->status,
            ]);
            return redirect()->route('/approved_projects');
     
        }
        else{
            echo"there is no team on this project or the team is not full  ";
        }
        
     }
     //##############################

    //**************************************


    //ROAD-MAP ****************************************
    //SHOW ROAD-MAPS

    public function show_roadmaps(){
        $users = DB::select('select * from roadmaps');
        foreach ($users as $user) {
            return response()->json([
                    'attributes'=>[
                        'id'=>$user->id,
                        'manager_id'=>$user->manager_id,
                        'manager_name'=>$user->manager_name,
                        'title'=>$user->title,
                        'category'=>$user->category,
                        'description'=>$user->description,
                ]
                    ]);
            
             }
        //return view('manager.roadmap.roadmaps',['users'=>$users]);
        }

    //##################
    
    //ADD NEW ROAD-MAP 
    
    public function addRoadmap(Request $request)
    {
        // $data=new Roadmap;
        // $data->title=$request->title;
        // $data->category=$request->category;
        // $data->description=$request->description;
        $request->validate([
            'title' => 'required',
            'category' => 'required',
            'description' => 'required',
        ]);
        $manager_id = Auth::guard('manager')->user()->id;
        $manager_name = Auth::guard('manager')->user()->name;
        $data = new Roadmap([
            'manager_id'=> $manager_id,
            'manager_name'=> $manager_name,
            'title'=> $request->title,
            'category'=>$request->category,
            'description'=> $request->description,
                        
        ]);
        
        $data->save();

        //return redirect()->back();
        return redirect()->route('/Roadmaps');
    }
    
    public function test_fun()
    {
        return view('test');
    }
    
    //################

    //EDIT DELETE ROAD-MAPS
    public function edit_roadmap($id){
    	$myRoadmap = DB::table('roadmaps')->where('id',$id)->first();
    	return view('manager.roadmap.edit',compact('myRoadmap'));
    }

    public function update_roadmap(Request $request,$id){

    	DB::table('roadmaps')->where('id',$id)->update([

    		'title'=>$request->title,
    		'description'=>$request->description
    	]);
    	return redirect()->route('/Roadmaps');
    } 

    public function delete_roadmap($id){

        DB::table('roadmaps')->where('id',$id)->delete();
        //DB::table('posts')->truncate();
        return redirect()->route('/Roadmaps');

    }

    //####################
    //****************************** */
    
    
    //******************************* */
    //ADD TASKS BY MANAGER

    public function show_tasks(){
        $users = DB::select('select * from tasks');
        // $data=[];

        // foreach ($users as $user) {
        //     $data[]=[
        //         'id'=>$user->id,
        //         'title'=>$user->title,
        //         'category'=>$user->category,
        //         'description'=>$user->description,
        //     ];
        // }
        // return response()->json([
        //     'data'=>$data

        // ]);
        //return response()->json(view('ManagerDashboard.tasks',['users'=>$users])->render());
        return  view('manager.tasks',['users'=>$users]);
        }
    //ADD NEW TASK 
    public function add_web_task(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'level' => 'required',
            'description' => 'required',
            'rank' => 'required',
        ]);

        $data = new Task([
            'title'=> $request->title,
            'level'=>$request->level,
            'category'=>'web devolopment',
            'description'=> $request->description,
            'file_path'=> $request->file_path,
            'solution'=> $request->solution,
            'rank'=> $request->rank,
            'points'=> $request->points,

                        
        ]);
       // echo $data;
        // $data=new Task; 
        // $data->title= $request->title;
        // $data->level=$request->level;
        // $data->category='web devolopment';
        // $data->description=$request->description;
        // $data->file_path=$request->file_path;
        // $data->rank= $request->rank;
        // $data->points= $request->points;

        $data->save();

        //return redirect()->back();
        return redirect()->route('/Tasks');
        //return view('manager.tasks');
    }
    
    public function add_security_task(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'level' => 'required',
            'description' => 'required',
            'solution' => 'required',
            'rank' => 'required',
        ]);
        $data = new Task([
            'title'=> $request->title,
            'level'=>$request->level,
            'category'=>'security',
            'description'=> $request->description,
            'file_path'=> $request->file_path,
            'solution'=> $request->solution,
            'rank'=> $request->rank,
            'points'=> $request->points,

                        
        ]);
        $data->save();

        //return redirect()->back();
        return redirect()->route('/Tasks');
    }

    public function add_desgin_task(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'level' => 'required',
            'description' => 'required',
            'rank' => 'required',
            'points'=> 'required',
        ]);
        $data = new Task([
            'title'=> $request->title,
            'level'=>$request->level,
            'category'=>'ui_ux',
            'description'=> $request->description,
            'file_path'=> $request->file_path,
            'rank'=> $request->rank,
            'points'=> $request->points,             
        ]);

        $data->save();

        //return redirect()->back();
        return redirect()->route('/Tasks');
    }

    //###################### 

    //EDIT DELETE TASKS
    public function edit_task($id){
    	$myTask = DB::table('tasks')->where('id',$id)->first();
    	return view('manager.task.edit',compact('myTask'));
    }

    public function update_task(Request $request,$id){

    	DB::table('tasks')->where('id',$id)->update([

    		'title'=>$request->title,
            'level'=>$request->level,
            'category'=>$request->category,
    		'description'=>$request->description,
            'file_path'=>$request->file_path,
            'rank'=>$request->rank,
            'points'=>$request->points,
    	]);
    	return redirect()->route('/Tasks');
    } 

    public function delete_task($id){

        DB::table('tasks')->where('id',$id)->delete();
        //DB::table('posts')->truncate();
        return redirect()->route('/Tasks');

    }
    //####################
    //****************************** */


    //****************************** */
    //SUBMITTED TASKS
    //SHOW SUBMITTED-TASKS

    public function show_web(){
        //$users = DB::select('select * from submitted_tasks');
        $users = DB::table('submitted_tasks')->where('category', '=', 'Web')->get();
        return view('manager.submitted_tasks.web',['users'=>$users]);
        }
    
    public function show_security(){
        //$users = DB::select('select * from submitted_tasks');
        $users = DB::table('submitted_tasks')->where('category', '=', 'Security')->get();
        return view('manager.submitted_tasks.security',['users'=>$users]);
        }
    
    public function show_design(){
        //$users = DB::select('select * from submitted_tasks');
        $users = DB::table('submitted_tasks')->where('category', '=', 'Design')->get();
        return view('manager.submitted_tasks.design',['users'=>$users]);
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
    //**************************** */

    //**************************** */
    //RANK INTERVIEWS
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

    public function accept_interview_request(Request $request,$id)
    {
        $result = DB::table('interview_requests')->where('id',$id)->first();
        $data=new RankInterview;
        $data->id=$result->id;
        $data->first_name=$result->first_name;
        $data->last_name=$result->last_name;
        $data->username=$result->username;
        $data->role=$result->role;
        $data->current_rank=$result->current_rank;
        $data->next_rank=$result->next_rank;
        
        $data->save();
        //$result_2 = DB::table('messages')->where('id',1)->get();
        //$result_2 = DB::table('messages')->where('id',1)->first();
        //sending messages to student_notifications table
        $data_2=new StudentNotification;
        $data_2->student_id=$result->id;
        $data_2->message_id=9;
        
        DB::table('interview_requests')->where('id',$id)->delete();
        $data_2->save();
        
        return back();
    }
    //#########################

    //REJECT INTERVIEW REQUESTS
    public function reject_interview_request(Request $request,$id){
       
        //sending messages to student_notifications table
        $data_2=new StudentNotification;
        $data_2->student_id=$id;
        $data_2->message_id=10;
        
        DB::table('interview_requests')->where('id',$id)->delete();
        $data_2->save();
        
        return back();

    }
    
    //###################


    //UPGRADE RANKE
    public function upgrade_rank(Request $request,$id,$next_rank){
        //$data=new Student;
        //$data->id=$result->id; 
        //$result = DB::table('rank_interviews')->get();
        //$resultArray = $result->toArray();
        DB::table('student_ranks')->where('student_id',$id)->update([

    		'rank'=>$next_rank,
    	]);
    	
        //sending messages to notifications table
        $data_2=new StudentNotification;
        $data_2->student_id=$id;
        $data_2->message_id=7;
        
        DB::table('rank_interviews')->where('id',$id)->delete();
        $data_2->save();

        return back();
        //return redirect()->route('/Roadmaps');
    }

    //##################

    //CANCEL RANK UPGRADING 
    public function cancel_rank_upgrading(Request $request,$id){
        
        //sending messages to student_notifications table
        $data_2=new StudentNotification;
        $data_2->student_id=$id;
        $data_2->message_id=8;
        
        DB::table('rank_interviews')->where('id',$id)->delete();
        $data_2->save(); 
        return back();

    }

    //SHOW TEAMS-REQUESTS

    public function team_requests(){
        $users = DB::select('select * from student_join_projects');
        $teams = DB::select('select * from team_join_projects');
        
        return view('manager.team_requests')
        ->with(compact('users'))
        ->with(compact('teams'));
        
        }

    


    //ACCEPT-TEAM
    //single student
    
    public function accept_single_student($id)
    {
        $result = DB::table('student_join_projects')->where('id',$id)->first();
        $max_allowed = DB::table('client_projects')->where('project_id',$result->project_id)->first();
        $student_count = DB::table('projects_teams')->where('team_id',$result->id)->get();
        //$users_all_2 = ProjectsTeam::all()->toArray();
        $max_allowed_2=$max_allowed->team_count;
        $student_count_2=count($student_count);
        $manager_id = Auth::guard('manager')->user()->id;

        if($student_count_2<$max_allowed_2){
            $data=new ProjectsTeam;
            $data->project_id=$result->project_id;
            $data->team_id=$result->project_id;
            $data->student_id=$result->student_id;
            $data->manager_id=$manager_id;
            
            DB::table('student_join_projects')->where('id',$id)->delete();
            $data->save();
            
    
            DB::table('client_projects')->where('project_id',$id)->update([
    
                'status'=>'Start devolopment',
                'team_id'=>$id,
            ]);
            
            // $result_2 = DB::table('projects_teams')->where('project_id',$id)->first();
            // //foreach($result_2 as $result){$team_id=$result->team_id;}
            // $data_2=new ProjectsTeamMember;
            // //$data->id=$result->id;
            // $data_2->student_id=$result->student_id;
            // $data_2->team_id=$result_2->team_id;
            // $data_2->save();
            return back();
            //return redirect()->back();
            
        }
        else{
            echo("team is full");
        }
        
    }
    //#######################################

    //full team
    public function accept_full_team($id)
    {
        $result = DB::table('team_join_projects')->where('id',$id)->first();
        $team_members = DB::table('student_teams')->where('team_id',$result->team_id)->get();
        $max_allowed = DB::table('client_projects')->where('project_id',$result->project_id)->first();
        $student_count = DB::table('projects_teams')->where('team_id',$result->id)->get();
        //$users_all_2 = ProjectsTeam::all()->toArray();
        $max_allowed_2=$max_allowed->team_count;
        $student_count_2=count($student_count);
        $manager_id = Auth::guard('manager')->user()->id;

        if($student_count_2<$max_allowed_2){
            foreach($team_members as $member){
                $data=new ProjectsTeam;
                $data->project_id=$result->project_id;
                $data->team_id=$result->project_id;
                $data->student_id=$member->student_id;
                $data->manager_id=$manager_id;
                $data->save();
            }
            
            DB::table('team_join_projects')->where('id',$id)->delete();
    
            DB::table('client_projects')->where('project_id',$id)->update([
    
                'status'=>'Start devolopment',
                'team_id'=>$id,
            ]);
            
            return back();

        }
        else{
            echo("team is full");
        }
        
    }
    //#######################################

//     //
//     public function create_team_action(Request $request){
//     $user = Auth::user();
//     $manager_id = Auth::guard('manager')->user()->student_id;
//     $teams = DB::table('projects_teams')->where('manager_id', $manager_id)->get();

//     if (!$user) {
//         return redirect()->intended('/')->with('fail', 'please login first ');
//     }

//     if ($teams->count() > 0) {
//         return redirect()->intended('/')->with('fail', 'you are already a team member');
//     }

//     // $validatedData = $request->validate([
//     //     'team_name' => 'required|unique:teams'
//     // ]);
//     $team = ProjectsTeam::create([
//         //'invitation_link' => $invitation_link
//     ]);
//     $users = Auth()->id();
//     $team->users()->attach($users);
// }

    //#################

    // public function accept_team($id)
    // {
    //     $result = DB::table('student_join_projects')->where('id',$id)->first();
    //     $data=new AcceptedTeam;
    //     //$data->id=$result->id;
    //     $data->project_id=$result->project_id;
    //     $data->project_name=$result->project_name;
    //     $data->client_id=$result->client_id;
    //     $data->client_email=$result->client_email;
    //     $data->student_id=$result->student_id;
    //     $data->student_name=$result->student_name;
    //     $data->student_role=$result->student_role;
        
    //     DB::table('student_join_projects')->where('id',$id)->delete();
    //     $data->save();
        
    //     return back();
    //     //return redirect()->back();
    //     return view('test');
    // }
    // //#################

    //**************************** */

}
