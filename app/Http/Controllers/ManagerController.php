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
use App\Models\StudentSolvedTask;
use App\Models\TaskSolution;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class ManagerController extends Controller
{
    //MANAGER LOGIN-LOGOUT SYSTEM
    public function manager_login()
    {
        $data['title'] = 'Login';
        return view('manager.manager_login', $data);
    }//just view

    public function manager_login_action(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        if (Auth::guard('manager')->attempt(['email' => $request->email, 'password' => $request->password,'role'=>['manager','teacher']]))
         {
            //########## auth the function
            $manager = Manager::where('email', $request->email)->first();
            $managerinfo=Manager::where('email', $request->email)->select('first_name', 'last_name', 'email', 'role')->first();
            $authorizationExists = true;
            while ($authorizationExists) {
                $Authorization = Str::random(40);
                $authorizationExists = DB::table('managers')->where('Authorization', $Authorization)->exists();
            }
            $manager->Authorization = $Authorization;
            $manager->last_login_at = now();
            $manager->save(); 
            $manager->save(); 
            //return data to front
              return response()->json([
                "manager" => $managerinfo,
                "Authorization" => $Authorization
            ]);
            //#######//########## auth the function


            // $request->session()->regenerate();
            // return view('manager.manager_home');
            //return redirect()->intended('/');
        }
        return response()->json(['ok' => "wrong username or password"], 200);
        // return back()->withErrors([
        //     'password' => 'Wrong username or password',
        // ]);
    }//done with test ddd

    public function manager_logout(Request $request){
        //###### auth user logout function start
        $Authorization = $request->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $manager = Manager::where('Authorization', $Authorization)->first();
        if (!$manager) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        //###### auth logout function end

        $manager->update(['Authorization' => null]);

        Auth::guard('manager')->logout();
        return response()->json(['ok' => "logout successfully"], 200);
       
        // return redirect('manager/login');
    }//done with test ddd
    //************************************** */

    //ADMIN LOGIN-LOGOUT SYSTEM
    public function admin_login()
    {
        $data['title'] = 'Login';
        return view('manager.admin_login', $data);
    }// just view

    public function admin_login_action(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        if (Auth::guard('manager')->attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'admin'])) {
            $request->session()->regenerate();

            //########## auth the user function start
            $admin = Manager::where('email', $request->email)->first();
            $admininfo=Manager::where('email', $request->email)->select('first_name', 'last_name', 'email', 'role')->first();
            $authorizationExists = true;
            while ($authorizationExists) {
                $Authorization = Str::random(40);
                $authorizationExists = DB::table('managers')->where('Authorization', $Authorization)->exists();
            }
            $admin->Authorization = $Authorization;
            $admin->last_login_at = now();
            $admin->save(); 
            //return data to front
              return response()->json([
                "admin" => $admininfo,
                "Authorization" => $Authorization
            ]);
            //#######//########## auth user function end

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
        return response()->json(['ok' => "wrong username or password"], 200);
        // return back()->withErrors([
        //     'password' => 'Wrong username or password',
        // ]);
    }//done with test ddd

    public function admin_logout(Request $request){
        //###### auth user logout function start
        $Authorization = $request->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $admin = Manager::where('Authorization', $Authorization)->first();
        if (!$admin) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        //###### auth logout function end

        $admin->update(['Authorization' => null]);

        Auth::guard('manager')->logout();
        return response()->json(['ok' => "logout successfully"], 200);
        //return redirect('admin/login');
    }//done with test ddd
    //************************************** */


    //teacher_ LOGIN-LOGOUT SYSTEM
    public function teacher_login()
    {
        $data['title'] = 'Login';
        return view('manager.teacher_login', $data);
    } // just view

    public function teacher_login_action(Request $request) {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        if (Auth::guard('manager')->attempt(['email' => $request->email, 'password' => $request->password,'role'=>'teacher'])) {
            $request->session()->regenerate();
            //return view('manager.teacher_home');

             //########## auth the user function start
             $teacher = Manager::where('email', $request->email)->first();
             $teacherinfo=Manager::where('email', $request->email)->select('first_name', 'last_name', 'email', 'role')->first();
             $authorizationExists = true;
             while ($authorizationExists) {
                 $Authorization = Str::random(40);
                 $authorizationExists = DB::table('managers')->where('Authorization', $Authorization)->exists();
             }
             $teacher->Authorization = $Authorization;
             $teacher->last_login_at = now(); 
             $teacher->save(); 
             //return data to front
               return response()->json([
                 "teacher" => $teacherinfo,
                 "Authorization" => $Authorization
             ]);
             //#######//########## auth user function end
            //return redirect()->intended('/');
        }
        return response()->json(['ok' => "wrong username or password"], 200);
        // return back()->withErrors([
        //     'password' => 'Wrong username or password',
        // ]);
    }//done with test ddd

    public function teacher_logout(Request $request)
    {
        
       // return redirect('teacher/login');
       //###### auth user logout function start
       $Authorization = $request->header('Authorization');
       if (!$Authorization) {
           return response()->json(['error' => 'Unauthorized'], 401);
       }
       $teacher = Manager::where('Authorization', $Authorization)->first();
       if (!$teacher) {
           return response()->json(['error' => 'Invalid token'], 401);
       }
       //###### auth logout function end
       //update sattus of auth
       $teacher->update(['Authorization' => null]);

       Auth::guard('manager')->logout();
       return response()->json(['ok' => "logout successfully"], 200);
    }//done with test ddd
    //************************************** */

    //SHOW SYSTEM-MANAGERS

    public function show_managers(Request $request){

        //###### auth user logout function start
       $Authorization = $request->header('Authorization');
       if (!$Authorization) {
           return response()->json(['error' => 'Unauthorized'], 401);
       }
       $admin = Manager::where('Authorization', $Authorization)->where('role', 'admin')->first();
       if (!$admin) {
           return response()->json(['error' => 'Invalid token'], 401);
       }
       //###### auth logout function end

       $users = DB::table('managers')->where('role', 'manager')->orwhere('role', 'teacher')->select('id','first_name', 'last_name', 'role', 'email')->get();

       return response()->json([
        'managers' => $users
         ]);  
       
    }//done with test ddd

    //##################

    //COUNT OF SYSTEM USERS
    public function count_users(Request $request){
         //###### auth user logout function start
       $Authorization = $request->header('Authorization');
       if (!$Authorization) {
           return response()->json(['error' => 'Unauthorized'], 401);
       }
       $admin = Manager::where('Authorization', $Authorization)->where('role', 'admin')->first();
       if (!$admin) {
           return response()->json(['error' => 'Invalid token'], 401);
       }
       //###### auth logout function end

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
       return response()->json([
        'managers' => $managers_number,
        'clients' => $clients_number,
        'students' => $students_number,
         ]);
        //    return view('manager.admin_home')
        //    ->with(compact('clients_number'))
        //    ->with(compact('managers_number'))
        //    ->with(compact('students_number'));

    }//done  with test ddd
    
    //ADD NEW MANAGER 
    public function addManager(Request $request)
    {
        //###### auth user logout function start
       $Authorization = $request->header('Authorization');
       if (!$Authorization) {
           return response()->json(['error' => 'Unauthorized'], 401);
       }
       $admin = Manager::where('Authorization', $Authorization)->where('role', 'admin')->first();
       if (!$admin) {
           return response()->json(['error' => 'Invalid token'], 401);
       }
       //###### auth logout function end
        $data=new Manager;
        $data->first_name=$request->first_name;
        $data->last_name=$request->last_name;
        $data->email=$request->email;
        $data->role=$request->role;
        //$data->password=$request->password;
        $data->password=bcrypt($request->password);
        //'password' => Hash::make($data['password']),

        $data->save();

        return response()->json([
            'manager added successfully'
             ]);
        
        
    }//done with test ddd
    
    
    //################

    //EDIT MANAGERS
    public function edit_system_managers(Request $request){
        //###### auth user logout function start
       $Authorization = $request->header('Authorization');
       if (!$Authorization) {
           return response()->json(['error' => 'Unauthorized'], 401);
       }
       $admin = Manager::where('Authorization', $Authorization)->where('role', 'admin')->first();
       if (!$admin) {
           return response()->json(['error' => 'Invalid token'], 401);
       }
       //###### auth logout function end
    	$data = DB::table('managers')->where('id',$request->id)->first();
    	//return view('manager.managers_edition',compact('data'));
        return response()->json([
            'manager first_name'=>$data->first_name,
            'manager last_name'=>$data->last_name,
            'manager email'=>$data->email,
            'manager role'=>$data->role,
            'manager password'=>$request->password,
             ]);
    } //just view ddd

    public function update_system_managers(Request $request){
        //###### auth user logout function start
        $Authorization = $request->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $admin = Manager::where('Authorization', $Authorization)->where('role', 'admin')->first();
        if (!$admin) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        //###### auth logout function end
        try{
            $request->validate([
                'email' => [
                    'nullable',
                    'email',
                    Rule::unique('managers', 'email')->ignore($request->id, 'id'),
                ],
            ]);
        }catch (ValidationException $e) {
            $errors = $e->validator->errors();
            if ($errors->has('email')) {
                return response()->json(['error' => 'Email address is already in use'], 422);
            }
            throw $e;
        }
        $update_data = array();
        if ($request->filled('first_name')) {
            $update_data['first_name'] = $request->first_name;
        }
        if ($request->filled('last_name')) {
            $update_data['last_name'] = $request->last_name;
        }
        if ($request->filled('email')) {
            $update_data['email'] = $request->email;
        }
        if ($request->filled('role')) {
            $update_data['role'] = $request->role;
        }
        if ($request->filled('password')) {
            $update_data['password'] = bcrypt($request->password);
        }
   
        DB::table('managers')->where('id', $request->id)->update($update_data);
   
        return response()->json([
            'message' => 'Manager edited successfully'
        ]);
   }//done test add to aymen 

    public function delete_system_managers(Request $request){

         //###### auth user logout function start
         $Authorization = $request->header('Authorization');
         if (!$Authorization) {
             return response()->json(['error' => 'Unauthorized'], 401);
         }
         $admin = Manager::where('Authorization', $Authorization)->where('role', 'admin')->first();
         if (!$admin) {
             return response()->json(['error' => 'Invalid token'], 401);
         }
         //###### auth logout function end
        Roadmap::where('manager_id',$request->id)->update(['manager_id'=>null]);
        ProjectsTeam::where('manager_id',$request->id)->update(['manager_id'=>null]);
        DB::table('managers')->where('id',$request->id)->delete();
       // $users = DB::table('managers')->where('role', 'Manager')->orwhere('role', 'Teacher')->get();
        return response()->json([
            'manager deleted successfuly'
             ]);  

    }//done  with test ddd

    //####################


    //SHOW PROJECTS-REQUESTS

    public function show_projects_requests(Request $request){
         
        //###### auth user logout function start
         $Authorization = $request->header('Authorization');
         if (!$Authorization) {
             return response()->json(['error' => 'Unauthorized'], 401);
         }
         $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
         if (!$manager) {
             return response()->json(['error' => 'Invalid token'], 401);
         }
         //###### auth logout function end

        $requests = DB::table('client_project_requests')
                ->join('clients', 'client_project_requests.client_id', '=', 'clients.client_id')
                ->select('client_project_requests.id', 'clients.email','client_project_requests.project_title','client_project_requests.project_description','client_project_requests.project_file_path')
                ->where('client_project_requests.status','requested')
                ->get();
                return response()->json([
                    'data' => $requests
                ]);    
        // $users = DB::select('select * from client_project_requests');
       // return view('manager.projects_requests',['users'=>$requests]);
    }//done  with test ddd

    //##################

    //ACCEPT-PROJECT-REQUEST

    public function accept_project_request(Request $request)
    {

        //###### auth user logout function start
        $Authorization = $request->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
        if (!$manager) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        //###### auth logout function end

        DB::table('client_project_requests')->where('id', $request->id)->update(['status' => 'accepted']);
        $result = DB::table('client_project_requests')->where('id',$request->id)->first();

        $data_2=new ClientNotification;
        $data_2->client_id=$result->client_id;
        $data_2->message_id=4;
        $data_2->save();
        return response()->json([
            'request accepted successfuly' => $result
        ]);    
        
        //return view('projects_requests');
        //return view('ManagerDashboard.projects_requests');
    }//done  with test ddd
    
    //####################

    //REJECT PROJECTs REQUESTS
    public function reject_project_request(Request $request){

          //###### auth user logout function start
          $Authorization = $request->header('Authorization');
          if (!$Authorization) {
              return response()->json(['error' => 'Unauthorized'], 401);
          }
          $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
          if (!$manager) {
              return response()->json(['error' => 'Invalid token'], 401);
          }
          //###### auth logout function end

        $result = DB::table('client_project_requests')->where('id',$request->id)->first();
        //$result_2 = DB::table('clients')->where('email',$result->client_email)->first();
         //sending messages to notifications table
         $data_2=new ClientNotification;
         $data_2->client_id=$result->client_id;
         $data_2->message_id=$request->reject;
         $data_2->save();
         DB::table('client_project_requests')->where('id',$request->id)->delete();
         return response()->json([
            'request rejected successfuly' => $result
        ]);    
         //return response()->json(['ok' => "client project has been rejected "], 200);
 
    }//done  with test ddd
     
     //###################
 

    //************************************ */

    //************************************ */
    //SHOW APPROVED-PROJECTS

    public function show_accepted_requests(Request $request){

        //###### auth user logout function start
          $Authorization = $request->header('Authorization');
          if (!$Authorization) {
              return response()->json(['error' => 'Unauthorized'], 401);
          }
          $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
          if (!$manager) {
              return response()->json(['error' => 'Invalid token'], 401);
          }
          //###### auth logout function end

        $users = DB::table('client_project_requests')->join('clients', 'client_project_requests.client_id', '=', 'clients.client_id')
        ->select('client_project_requests.id','client_project_requests.client_id', 'clients.email','client_project_requests.project_title','client_project_requests.project_description','client_project_requests.project_file_path','client_project_requests.status')
        ->where('client_project_requests.status','accepted')
        ->get();

        //$clientss_projects = DB::table('client_projects')->get();
        $clients_projects = DB::table('client_projects')->join('clients', 'client_projects.client_id', '=', 'clients.client_id')
        ->get();

        $data = [];
        foreach ($clients_projects as $project) {
            $team_members = ProjectsTeamMember::where('team_id', $project->team_id)->get();
            $team_members_data = [];
            foreach ($team_members as $team_member) {
                $student = Student::find($team_member->student_id);
                $team_members_data[] = [
                    'id' => $student->student_id,
                    'first_name' => $student->first_name,
                    'last_name' => $student->last_name,
                    'rank' => $student->rank,
                    'role' => $student->role,
                    // add more fields as needed
                ];
            }
            $data[] = [
                
                'id' => $project->id,
                'project_title' => $project->title,
                'project_category' => $project->category,
                'email' => $project->email,
                'project_description' => $project->description,
                'project_deadline' => $project->deadline,
                'project_status' => $project->status,
                'project_rank' => $project->rank,
                'project_frontend' => $project->frontend,
                'project_backend' => $project->backend,
                'project_ui_ux' => $project->ui_ux,
                'project_web_security' => $project->web_security,
                'project_team_count' => $project->team_count,
                'team_id' => $project->team_id,
                'team_members' => $team_members_data
            ];
        }
        //for showing accepted clients projects
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

        return response()->json([
            'approved projects' => $users,
            'Ready clients projects' => $data,
        ]);    
        
    }//done  with test ddd

    //##################
    
    //ADD NEW APPROVED-PROJECT-REQUEST
    
    public function publish(Request $request)
    {

         //###### auth user logout function start
         $Authorization = $request->header('Authorization');
         if (!$Authorization) {
             return response()->json(['error' => 'Unauthorized'], 401);
         }
         $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
         if (!$manager) {
             return response()->json(['error' => 'Invalid token'], 401);
         }
         //###### auth logout function end

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
            'web_security' => $request->web_security,
            'team_count' => $request->web_security+$request->ui_ux+$request->backend+$request->frontend,            
        ]);
        //echo $data;
        $data->save();
        
        //sending messages to client_notifications table
        $result_2 = DB::table('clients')->where('email',$request->client_email)->first();
        $data_2=new ClientNotification;
        $data_2->client_id=$request->client_id;
        $data_2->message_id=5;
        $data_2->save();
        ////this is not working until i download the new commet of  mixing the tables//sweet 
        DB::table('client_project_requests')
        ->where('id',$request->id)
        ->where('status','accepted')->delete();
        $data_2->save();
        return response()->json([
            'project has been approved successfuly'
        ]);    
        //return back();

        //return redirect()->back();
        //return view('test');
    }//done with test ddd //test again
    //########################

    //CANCELING PROJECTS PUBLISHING
    public function cancel_publish(Request $request){

         //###### auth user logout function start
         $Authorization = $request->header('Authorization');
         if (!$Authorization) {
             return response()->json(['error' => 'Unauthorized'], 401);
         }
         $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
         if (!$manager) {
             return response()->json(['error' => 'Invalid token'], 401);
         }
         //###### auth logout function end

        //$result = DB::table('accepted_clients_requests')->where('id',$request->id)->first();
        $result_2 = DB::table('clients')->where('email',$request->email)->first();
         //sending messages to notifications table
         $data_2=new ClientNotification;
         $data_2->client_id=$request->client_id;
         $data_2->message_id=$request->cancel;
          
         $data_2->save();
         DB::table('client_project_requests')->where('id',$request->id)->delete();

         return response()->json([
            'project publishing canceld successfuly'
        ]);    
 
    }//done test it ddd
     
     //###################
     
     //UPDATE PROJECT PROGRESS STATUS
     public function update_status(Request $request){

        //###### auth user logout function start
        $Authorization = $request->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
        if (!$manager) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        //###### auth logout function end

        $team_id=DB::table('client_projects')->where('id',$request->id)->first();
        $team_check= $team_id->team_id;

        $max_allowed = DB::table('client_projects')->where('id',$request->id)->first();
        $student_count = DB::table('projects_team_members')->where('team_id',$team_check)->get();
        //$users_all_2 = ProjectsTeam::all()->toArray();
        $max_allowed_2=$max_allowed->team_count;
        $student_count_2=count($student_count);
        if(!empty($student_count_2>=$max_allowed_2)) { 
            DB::table('client_projects')->where('id',$request->id)->update([

                'status'=>$request->status,
            ]);
            return response()->json([
                'status update successfuly '
            ]);  
     
        }
        else{
            return response()->json([
                'there is no team on this project or the team is not full  '
            ]);  
        }
        
    }//done with test ddd 
     //##############################

    //**************************************


    //ROAD-MAP ****************************************
    //SHOW ROAD-MAPS

    public function show_roadmaps(Request $request){

         //###### auth user logout function start
         $Authorization = $request->header('Authorization');
         if (!$Authorization) {
             return response()->json(['error' => 'Unauthorized'], 401);
         }
         $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager',)->first();
         $teacher = Manager::where('Authorization', $Authorization)->where('role', 'teacher',)->first();
         if (!$manager and !$teacher) {
             return response()->json(['error' => 'Invalid token'], 401);
         }
         //###### auth logout function end
        
        //$users = DB::select('select * from roadmaps');
        $users = DB::table('roadmaps')
        ->join('managers', 'roadmaps.manager_id', '=', 'managers.id')
        ->select('roadmaps.id','managers.first_name','managers.last_name','roadmaps.title','roadmaps.category','roadmaps.description')
        ->get();
        $roadmaps= [];
        foreach ($users as $user) {
            $roadmaps[] = [
                'id'=>$user->id,
                'first_name'=>$user->first_name,
                'last_name'=>$user->last_name,
                'title'=>$user->title,
                'category'=>$user->category,
                'description'=>$user->description,
                // add more fields as needed
            ];
             }
             //return $roadmaps;
             return response()->json([
                'roadmaps' => $roadmaps
            ]); 
    }//done with test ddd 

    //##################
    
    //ADD NEW ROAD-MAP 
    
    public function addRoadmap(Request $request)
    {
         //###### auth user logout function start
         $Authorization = $request->header('Authorization');
         if (!$Authorization) {
             return response()->json(['error' => 'Unauthorized'], 401);
         }
         $manager =  Manager::where('Authorization', $Authorization)->whereIn('role', ['manager', 'teacher'])->first();
         
         if (!$manager) {
             return response()->json(['error' => 'Invalid token'], 401);
         }
         //###### auth logout function end

        $request->validate([
            'title' => 'required',
            'category' => 'required',
            'description' => 'required',
        ]);
        $manager_id = $manager->id;
        $data = new Roadmap([
            'manager_id'=> $manager_id,
            'title'=> $request->title,
            'category'=>$request->category,
            'description'=> $request->description,
                        
        ]);
        
        $data->save();

        //return redirect()->back();
       return response()->json([
                'Roadmap added successfuly'
            ]); 
    } //done with test ddd 
    
    
    //################

    //EDIT DELETE ROAD-MAPS
    public function edit_roadmap(Request $request){

        //###### auth user logout function start
        $Authorization = $request->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $manager =  Manager::where('Authorization', $Authorization)->whereIn('role', ['manager', 'teacher'])->first();
        
        if (!$manager) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        //###### auth logout function end

    	$myRoadmap = DB::table('roadmaps')->where('id',$request->id)->first();
    	return view('manager.roadmap.edit',compact('myRoadmap'));
    } //viwe 

    public function update_roadmap(Request $request){

        //###### auth user logout function start
        $Authorization = $request->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $manager =  Manager::where('Authorization', $Authorization)->whereIn('role', ['manager', 'teacher'])->first();
        
        if (!$manager) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        //###### auth logout function end
        $update_data = array();
        
        if ($request->filled('title')) {
            $update_data['title'] = $request->title;
        }
        if ($request->filled('category')) {
            $update_data['category'] = $request->category;
        }
        if ($request->filled('description')) {
            $update_data['description'] = $request->description;
        }
   
        DB::table('roadmaps')->where('id', $request->id)->update($update_data);
        return response()->json([
            'Roadmap edited successfuly'
        ]);
   
    }

    public function delete_roadmap(Request $request){

         //###### auth user logout function start
         $Authorization = $request->header('Authorization');
         if (!$Authorization) {
             return response()->json(['error' => 'Unauthorized'], 401);
         }
         $manager =  Manager::where('Authorization', $Authorization)->whereIn('role', ['manager', 'teacher'])->first();
         
         if (!$manager) {
             return response()->json(['error' => 'Invalid token'], 401);
         }
         //###### auth logout function end

        DB::table('roadmaps')->where('id',$request->id)->delete();
        //DB::table('posts')->truncate();
        return response()->json([
            'Roadmap deleted successfuly'
        ]); 
    }//done with test ddd  

    //####################
    //****************************** */
    
    
    //******************************* */
    //ADD TASKS BY MANAGER

    public function show_tasks(Request $request){

         //###### auth user logout function start
         $Authorization = $request->header('Authorization');
         if (!$Authorization) {
             return response()->json(['error' => 'Unauthorized'], 401);
         }
         $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
         if (!$manager) {
             return response()->json(['error' => 'Invalid token'], 401);
         }
         //###### auth logout function end

        
        $users =    DB::table('tasks')->get();
       
        return response()->json([
            'Tasks'=>$users,
        ]); 
    }//done with test ddd  
    //ADD NEW TASK 
    public function add_task(Request $request)
    {
        //###### auth user logout function start
        $Authorization = $request->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
        if (!$manager) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        //###### auth logout function end
        $path = null;
        if ($request->hasFile('file_path')) {
            $path = $request->file('file_path')->store('tasks/uploads');
        }

        $request->validate([
            'title' => 'required',
            'level' => 'required',
            'description' => 'required',
            'rank' => 'required',
            'category'=>'required'
        ]);

        $data = new Task([
            'title'=> $request->title,
            'level'=>$request->level,
            'category'=>$request->category,
            'description'=> $request->description,
            'file_path'=> $path,
            'rank'=> $request->rank,
            'points'=> $request->points,             
        ]);
        $data->save();
        if ($request->filled('solution')) {
            $solution = TaskSolution::firstOrCreate(
                ['task_id' => $data->id],
                ['solution' => $request->solution]
            );
        }
        return response()->json([
           "Task added successfuly"
        ]); 
    }
    

    //###################### 

    //EDIT DELETE TASKS
    public function edit_task(){
    	$myTask = DB::table('tasks')->where('id')->first();
    	return view('manager.task.edit',compact('myTask'));
    } //view 

    public function update_task(Request $request){

        //###### auth user logout function start
        $Authorization = $request->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
        if (!$manager) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        //###### auth logout function end
        $update_data = array();
        
        if ($request->filled('title')) {
            $update_data['title'] = $request->title;
        }
        if ($request->filled('level')) {
            $update_data['level'] = $request->level;
        }
        if ($request->filled('category')) {
            $update_data['category'] = $request->category;
        }
        if ($request->filled('description')) {
            $update_data['description'] = $request->description;
        }
        if ($request->filled('file_path')) {
            $update_data['file_path'] = $request->file_path;
        }
        if ($request->filled('rank')) {
            $update_data['rank'] = $request->rank;
        }
        if ($request->filled('points')) {
            $update_data['points'] = $request->points;
        }
        
        DB::table('tasks')->where('id', $request->id)->update($update_data);

        return response()->json([
            "Task edited successfuly"
         ]); 

    } ///done test add to aymen //done test it Mahdi should edit this 

    public function delete_task(Request $request){

         //###### auth user logout function start
         $Authorization = $request->header('Authorization');
         if (!$Authorization) {
             return response()->json(['error' => 'Unauthorized'], 401);
         }
         $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
         if (!$manager) {
             return response()->json(['error' => 'Invalid token'], 401);
         }
         //###### auth logout function end
        StudentSolvedTask::where('task_id',$request->id)->update(['task_id'=>null]);
        DB::table('tasks')->where('id',$request->id)->delete();
        //DB::table('posts')->truncate();
        return response()->json([
            "Task deleted successfuly"
         ]); 

    } //done test it 
    //####################
    //****************************** */


    //****************************** */
    //SUBMITTED TASKS 
    //SHOW SUBMITTED-TASKS

    public function show_web(Request $request){

         //###### auth user logout function start
         $Authorization = $request->header('Authorization');
         if (!$Authorization) {
             return response()->json(['error' => 'Unauthorized'], 401);
         }
         $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
         if (!$manager) {
             return response()->json(['error' => 'Invalid token'], 401);
         }
         //###### auth logout function end

        //$users = DB::select('select * from submitted_tasks');
        ///$users = DB::table('student_solved_tasks')->where('category', '=', 'Web')->get();
        $users = DB::table('student_solved_tasks')
        ->join('students', 'student_solved_tasks.student_id', '=', 'students.student_id')
        ->join('tasks', 'tasks.id', '=', 'student_solved_tasks.task_id')
        ->select(
        'student_solved_tasks.id',
        'student_solved_tasks.task_id',
        'tasks.title',
        'tasks.level',
        'student_solved_tasks.category',
        'students.student_id',
        'students.username',
        'student_solved_tasks.report',
        'student_solved_tasks.solution',
        'tasks.points'
        )
        ->where('student_solved_tasks.category', '=', 'web_development')->whereNull('student_solved_tasks.points')->get();
        
        //return $users;
        return response()->json([
            'tasks'=>$users
         ]); 
    }//done with test ddd  
    
    public function show_security(Request $request){

         //###### auth user logout function start
         $Authorization = $request->header('Authorization');
         if (!$Authorization) {
             return response()->json(['error' => 'Unauthorized'], 401);
         }
         $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
         if (!$manager) {
             return response()->json(['error' => 'Invalid token'], 401);
         }
         //###### auth logout function end

        //$users = DB::select('select * from submitted_tasks');

       // $users = DB::table('student_solved_tasks')->where('category', '=', 'Security')->get();
       $users = DB::table('student_solved_tasks')
       ->join('students', 'student_solved_tasks.student_id', '=', 'students.student_id')
       ->join('tasks', 'tasks.id', '=', 'student_solved_tasks.task_id')
       ->select(
       'student_solved_tasks.id',
       'student_solved_tasks.task_id',
       'tasks.title',
       'tasks.level',
       'student_solved_tasks.category',
       'students.student_id','students.username',
       'student_solved_tasks.report',
       'student_solved_tasks.solution',
       'tasks.points'
       )
       ->where('student_solved_tasks.category', '=', 'web_security')->whereNull('student_solved_tasks.points')->get();
       //return $users;
       return response()->json([
        'tasks'=>$users
     ]); 
    } //done with test ddd  
    
    public function show_design(Request $request){

         //###### auth user logout function start
         $Authorization = $request->header('Authorization');
         if (!$Authorization) {
             return response()->json(['error' => 'Unauthorized'], 401);
         }
         $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
         if (!$manager) {
             return response()->json(['error' => 'Invalid token'], 401);
         }
         //###### auth logout function end

        //$users = DB::select('select * from submitted_tasks');
       //$users = DB::table('student_solved_tasks')->where('category', '=', 'Design')->get();
       $users = DB::table('student_solved_tasks')
       ->join('students', 'student_solved_tasks.student_id', '=', 'students.student_id')
       ->join('tasks', 'tasks.id', '=', 'student_solved_tasks.task_id')
       ->select(
       'student_solved_tasks.id',
       'student_solved_tasks.task_id',
       'tasks.title',
       'tasks.level',
       'student_solved_tasks.category',
       'students.student_id','students.username',
       'student_solved_tasks.file_path',
       'tasks.points'
        )
       ->where('student_solved_tasks.category', '=', 'ui_ux')->whereNull('student_solved_tasks.points')->get();

       return response()->json([
        'tasks'=>$users
     ]); 
    } //done with test ddd  


    //##################

    //CUSTOM-POINTS

    public function custom(Request $request){

         //###### auth user logout function start
         $Authorization = $request->header('Authorization');
         if (!$Authorization) {
             return response()->json(['error' => 'Unauthorized'], 401);
         }
         $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
         if (!$manager) {
             return response()->json(['error' => 'Invalid token'], 401);
         }
         
         //###### auth logout function end 
        $users = DB::table('student_ranks')->where('student_id', '=', $request->student_id)->value('points');
        $x = $users;
        $y=$request->custom_points;
        $z=$x+$y;
    	DB::table('student_ranks')->where('student_id',$request->student_id)->update([
    		'points'=>$z,
    	]);

        DB::table('student_solved_tasks')->where('student_id',$request->student_id)->where('task_id',$request->task_id)->update([
    		'points'=>$y,]);
    	//DB::table('submitted_tasks')->where('id',$id)->delete();
        return response()->json([
            'custom points gived successfuly'
         ]); 
    } //done

    //####################

    //FULL-POINTS

    public function full(Request $request){

         //###### auth user logout function start
         $Authorization = $request->header('Authorization');
         if (!$Authorization) {
             return response()->json(['error' => 'Unauthorized'], 401);
         }
         $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
         if (!$manager) {
             return response()->json(['error' => 'Invalid token'], 401);
         }
         //###### auth logout function end
    	$task =DB::table('tasks')->where('id',$request->task_id)->first();


        $users = DB::table('student_ranks')->where('student_id', '=', $request->student_id)->value('points');
        $x = $users;
        $y=$task->points;
        $z=$x+$y;
    	DB::table('student_ranks')->where('student_id',$request->student_id)->update([
    		'points'=>$z,
    	]);
        DB::table('student_solved_tasks')->where('student_id',$request->student_id)->where('task_id',$request->task_id)->update([
    		'points'=>$y,
    	]);
    	//DB::table('student_solved_tasks')->where('id',$id)->delete();

        return response()->json([
            'full points gived successfuly'
         ]); 
    } //done with test ddd  

    //####################
    //**************************** */

    //**************************** */
    //RANK INTERVIEWS
    //SHOW RANK-INTERVIEW-REQUESTS

    public function rank_interview_requests(Request $request){

        //###### auth user logout function start
        $Authorization = $request->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
        if (!$manager) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        //###### auth logout function end

        $interview_requests = DB::table('interview_requests')->where('status', 'requested')->get();
        $accepted_interview_requests = DB::table('interview_requests')->where('status', 'accepted')->get();
        return response()->json([
           'interview_requests'=> $interview_requests,
           'accepted_interview_requests'=> $accepted_interview_requests ,
         ]); 
    }//done with test ddd  and it's show both requested and accepted

    //##################

    
    //ACCEPT INTERVIEW REQUESTS

    public function accept_interview_request(Request $request,)
    {
        
        //###### auth user logout function start
        $Authorization = $request->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
        if (!$manager) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        //###### auth logout function end

        DB::table('interview_requests')->where('id',$request->id)->update([

    		'status'=>'accepted',
    	]);
         //sending messages to student_notifications table
         $data_2=new StudentNotification;
         $data_2->student_id=$request->student_id;
         $data_2->message_id=9;
         $data_2->save(); 
        
        return response()->json([
            'interview requests accepted',
          ]); 
    }//done with test ddd 
    //#########################

    //REJECT INTERVIEW REQUESTS
    public function reject_interview_request(Request $request){

         //###### auth user logout function start
        $Authorization = $request->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
        if (!$manager) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        //###### auth logout function end 
       
        //sending messages to student_notifications table
        $data_2=new StudentNotification;
        $data_2->student_id=$request->student_id;
        $data_2->message_id=10;
        
        DB::table('interview_requests')->where('id',$request->id)->delete();
        $data_2->save();
        
        return response()->json([
            'interview requests rejected',
          ]); 

    }//done with test ddd 
    
    //###################


    //UPGRADE RANKE
    public function upgrade_rank(Request $request){
       
          //###### auth user logout function start
        $Authorization = $request->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
        if (!$manager) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        //###### auth logout function end 

        DB::table('student_ranks')->where('student_id',$request->student_id)->update([

    		'rank'=>$request->next_rank,
    	]);
    	
        //sending messages to notifications table
        $data_2=new StudentNotification;
        $data_2->student_id=$request->student_id;
        $data_2->message_id=8;
        
        DB::table('interview_requests')->where('id',$request->id)->delete();
        $data_2->save();

        return response()->json([
            'Rank has been upgrated successfuly',
          ]); 
        //return redirect()->route('/Roadmaps');
    }//done with test ddd 

    //##################

    //CANCEL RANK UPGRADING 
    public function cancel_rank_upgrading(Request $request){

          //###### auth user logout function start
        $Authorization = $request->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
        if (!$manager) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        //###### auth logout function end 
        
        //sending messages to student_notifications table
        $data_2=new StudentNotification;
        $data_2->student_id=$request->student_id;
        $data_2->message_id=7;
        $data_2->save(); 
        
        DB::table('interview_requests')->where('id',$request->id)->delete();
        return response()->json([
            'Rank  upgrated has been canceld',
          ]); 

    }//done with test ddd 

    //SHOW TEAMS-REQUESTS

    public function join_project_requests(Request $request){
            
            //###### auth user logout function start
            $Authorization = $request->header('Authorization');
            if (!$Authorization) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
            if (!$manager) {
                return response()->json(['error' => 'Invalid token'], 401);
            }
            //###### auth logout function end

        $teamRequests = DB::table('team_join_projects')
            ->join('teams', 'team_join_projects.team_id', '=', 'teams.team_id')
            ->join('client_projects', 'team_join_projects.project_id', '=', 'client_projects.id')
            ->join('clients', 'client_projects.client_id', '=', 'clients.client_id')
            ->select('team_join_projects.id', 'team_join_projects.project_id', 'clients.email', 'team_join_projects.project_title', 'team_join_projects.team_id', 'teams.team_name')
            ->get();

        $studentRequests = DB::table('student_join_projects')
            ->join('students', 'student_join_projects.student_id', '=', 'students.student_id')
            ->join('student_ranks', 'students.student_id', '=', 'student_ranks.student_id')
            ->join('clients', 'student_join_projects.client_id', '=', 'clients.client_id')
            ->select('students.student_id'
            ,'students.first_name',
            'students.last_name',
            'students.role','student_ranks.student_id',
            'student_join_projects.project_id',
            'student_join_projects.project_title',
            'clients.email',
            'student_ranks.rank',
            'student_join_projects.id',

            )
            ->orderBy('student_join_projects.id')
            ->get()
            ->groupBy('project_id');

        $results = [];
        foreach ($studentRequests as $projectId => $requests) {
            $project = [
                'id' => $requests->first()->id,
                'project_id' => $projectId,
                'title' => $requests->first()->project_title,
                'client_email' => $requests->first()->email,

                'student_requests' => [],
                'team_requests' => []
            ];

            foreach ($requests as $request) {
                $student = [
                    //'project_id' => $request->project_id,
                    'student_id' => $request->student_id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'role' => $request->role,
                    'rank' => $request->rank,

                    // add more fields as needed///
                ];

                $project['student_requests'][] = $student;
            }

            // Add team requests to the project array
            $teamRequestsForProject = $teamRequests->where('project_id', $projectId);
            foreach ($teamRequestsForProject as $teamRequest) {
                $team = [
                    'id' => $teamRequest->id,
                    'project_id' => $request->project_id,
                    'team_id' => $teamRequest->team_id,
                    'team_name' => $teamRequest->team_name
                ];

                $project['team_requests'][] = $team;
            }

            $results[] = $project;
        }

        return response()->json([
        // 'team_requests' => $teamRequests,
            'all_requests' => $results,
        ]);



            // return response()->json([
            //     'team_requests'=>$teamRequests,
            //    'student_requests'=>$results,
            //   ]); 
            
    }//done with test ddd 

    
//mahdi farfara
public function add_student_to_project(Request $request)
{

    //###### auth user logout function start
    $Authorization = $request->header('Authorization');
    if (!$Authorization) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
    if (!$manager) {
        return response()->json(['error' => 'Invalid token'], 401);
    }
    //###### auth logout function end

    $manager_id = $manager->id;

    $validatedData = $request->validate([
        'project_id' => 'required|integer',
        'student_id' => 'required|integer',
    ]);

    $project = Client_projects::findOrFail($validatedData['project_id']);

    // check if the student is already a member of the team
    $teamMembers = ProjectsTeamMember::where('team_id', $project->team_id)->get();
    $isMember = $teamMembers->contains('student_id', $validatedData['student_id']);
    if ($isMember) {
        return response()->json([
            'message' => 'The student is already a member of this team'
        ], 400);
    }

    // check if the team is already full
    $teamCount = $project->team_count;
    $currentTeamCount = $teamMembers->count();
    if ($currentTeamCount >= $teamCount) {
        // if($currentTeamCount== $teamCount && $project->start_dat==null){
        //     $project->start_date = now();
        //     $project->save();
        // }
        return response()->json([
            'message' => 'The team is already full'
        ], 400);
    }

    if ($project->team_id) {
        $team = ProjectsTeam::findOrFail($project->team_id);
    } else {
        $team = new ProjectsTeam();
        $team->project_id = $project->id;
        $team->manager_id = $manager_id;
        $team->save();
        $project->team_id = $team->id;
        $project->save();
    }
    $student = Student::findOrFail($validatedData['student_id']);
    $teamMember = new ProjectsTeamMember();
    $teamMember->team_id = $team->id;
    $teamMember->student_id = $student->student_id;
    $teamMember->save();
    DB::table('student_join_projects')->where('id',$request->id)->delete();    
    $newteamMembers = ProjectsTeamMember::where('team_id', $project->team_id)->get();
    $newcurrentTeamCount = $newteamMembers->count();
    if($newcurrentTeamCount== $teamCount){
        $project->start_date = now();
        $project->save();
    }


    return response()->json([
        'message' => 'Student added to the project team successfully',
        'team_member_id' => $teamMember->id
    ], 201);
}//done with test ddd 

///end of add single student to projetc with create team ;;;;; 

///start of add a team or accept a team to project


public function add_team_to_project(Request $request)
{

    //###### auth user logout function start
    $Authorization = $request->header('Authorization');
    if (!$Authorization) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager')->first();
    if (!$manager) {
        return response()->json(['error' => 'Invalid token'], 401);
    }
    //###### auth logout function end

    $validatedData = $request->validate([
        'team_id' => 'required|integer',
        'project_id' => 'required|integer',
    ]);

    $manager_id = $manager->id;

    // Check if the project already has a team
    $project = Client_projects::findOrFail($validatedData['project_id']);
    if ($project->team_id) {
        return response()->json([
            'error' => 'The project already has a team.',
        ], 422);
    } else {
        // Find the team members
        $team_members = DB::table('student_teams')
            ->where('team_id', $validatedData['team_id'])
            ->get();

        if (count($team_members) < 1) {
            return response()->json([
                'error' => 'The team does not have any members.',
            ], 422);
        }
        // Create the project team
        $team = new ProjectsTeam();
        $team->project_id = $validatedData['project_id'];
        $team->manager_id = $manager_id;
        $team->save();

        //Add team members to the project team
        foreach ($team_members as $team_member) {
            $projectsTeamMember = new ProjectsTeamMember();
            $projectsTeamMember->team_id = $team->id;
            $projectsTeamMember->student_id = $team_member->student_id;
            $projectsTeamMember->save();
            
        }
        DB::table('team_join_projects')->where('id',$request->id)->delete();

        // Update the project with the team id
        $project->team_id = $team->id;
        $project->save();
    }

    return response()->json([
        'message' => 'Team request accepted successfully',
        'team_id' => $team->id,
    ], 201);
}//done with test ddd 

public function show_contact_us(Request $request){

    //###### auth user logout function start
    $Authorization = $request->header('Authorization');
    if (!$Authorization) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    $manager = Manager::where('Authorization', $Authorization)->where('role', 'manager',)->first();
    $teacher = Manager::where('Authorization', $Authorization)->where('role', 'teacher',)->first();
    if (!$manager and !$teacher) {
        return response()->json(['error' => 'Invalid token'], 401);
    }
    //###### auth logout function end 
   
   //$users = DB::select('select * from roadmaps');
   $users = DB::table('contact_us')
   ->join('clients', 'clients.client_id', '=', 'contact_us.client_id')
   ->select('contact_us.id','clients.first_name','clients.last_name','contact_us.client_email','contact_us.description')
   ->orderBy("contact_us.id", "desc")->get();

   return response()->json([
    'conutact_us' => $users,
]);


}

//############################## AAAAAAAAAALLLL DDDDDOOONNNEEEEE ##############################

////end of team join project 


        //mahdi farfra

    //ACCEPT-TEAM
    //single student
    
    //#######################################
    

    //full team
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
