<?php

namespace App\Http\Controllers;

use App\Models\Client_projects;
use App\Models\project;
use App\Models\Student;
use App\Models\InterviewRequest;
use App\Models\Team;
use App\Models\StudentJoinProject;
use App\Models\TeamJoinProject;
use App\Models\DefaultRank;
use App\Models\StudentRank;
use App\Models\StudentSolvedTask;
use App\Models\TaskSolution;
use App\Models\Task;
use App\Models\Star;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function register()
    {
        $data['title'] = 'Register';
        return view('student.register', $data);
    }

    public function register_action(Request $request){
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:students',
            'email' => 'required|unique:students',
            'role' => 'required',
            'password' => 'required',
            'password_confirm' => 'required|same:password',
        ]);
        $Student = new Student([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);
        $Student->save();
        $users = DB::table('students')->where('username', $request->username)->get();
        foreach($users as $user){
            $student_id=$user->student_id;
            $data=new DefaultRank;
            $data->student_id=$student_id;
            $data->save();
        }
    
        return response()->json(['success' => 'Registration completed successfully'], 200);
    }//done


    public function login(){
        $data['title'] = 'Login';
        return view('student.login', $data);
    }

//start login function
    public function login_action(Request $request){
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        if (Auth::guard('student')->attempt(['username' => $request->username, 'password' => $request->password]) 
        || Auth::guard('student')->attempt(['email' => $request->username, 'password' => $request->password])) {
            $student = Student::where(function ($query) use ($request) {$query->where('email', $request->username)->orWhere('username', $request->username);})->first();
            $studentinfo = Student::where(function ($query) use ($request) {$query->where('email', $request->username)->orWhere('username', $request->username);})
            ->select('first_name', 'last_name', 'username', 'email', 'avater','role','team_id')->first();
            $authorizationExists = true;
            while ($authorizationExists) {
                $Authorization = Str::random(40);
                $authorizationExists = DB::table('students')->where('Authorization', $Authorization)->exists();
            }
            $student->Authorization = $Authorization;
            $student->save(); 
            // return response()->json([
            //     "student" => $studentinfo,
            //     "Authorization" => $Authorization
            // ]);
            
           // $request->session()->regenerate();

            return view('student.home');
        }
        return response()->json(['ok' => "wrong username or password"], 200);
    }//done

//end login finction 


    // public function password()
    // {
    //     $data['title'] = 'Change Password';
    //     return view('student.password', $data);
    // }

    // public function password_action(Request $request)
    // {
    //     $request->validate([
    //         'old_password' => 'required|current_password',
    //         'new_password' => 'required|confirmed',
    //     ]);
    //     $Student = Student::find(Auth::id());
    //     $Student->password = Hash::make($request->new_password);
    //     $Student->save();
    //     $request->session()->regenerate();
    //     return back()->with('success', 'Password changed!');
    // }

    public function logout(Request $request){

        $Authorization = $request->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $student = Student::where('Authorization', $Authorization)->first();
        if (!$student) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        $student->update(['Authorization' => null]);
        Auth::guard('student')->logout();
        return response()->json(['ok' => "logout successfully"], 200);
    }//done


//  function that returnt rank and update rank from rank table based on seesion user_id 
    public function rank(){
        $Authorization = request()->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Validate authorization token with client guard
        $student = Student::where('Authorization', $Authorization)->first();
        if (!$student) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        $student_id = $student->student_id;
        $rank = DB::table('student_ranks')->where('student_id', $student_id)->get();
        foreach ($rank as $student_rank) {
        return response()->json([
                'attributes'=>[
                    'student_id'=>$student_rank->student_id,
                    'points'=>$student_rank->points,
                    'rank'=>$student_rank->rank,
            ]
                ]);
        
         }
    }//done

    public function upgrade_rank(Request $request){
            $id = Auth::guard('student')->user()->student_id ;//get user id from currunt session 
            $users = DB::table('students')->where('student_id', $id)->get();
            $rank = DB::table('student_ranks')->where('student_id', $id)->get();
            foreach ($rank as $user_rank) {
                $current_points = $user_rank->points;
                //echo $current_point ;
                // return redirect()->intended('/home');
                // $id = Auth()->id();//get user id from currunt session 
                // $rank = DB::table('rank')->where('user_id', $id)->get();
                // $current_rank= $rank->rank;
                foreach ($users as $user) {
                if ($current_points >= 0 && $current_points <= 50) {
                    $current_rank = 'f';
                } elseif ($current_points > 50 && $current_points <= 100) {
                    $current_rank = 'e';
                }elseif ($current_points > 100 && $current_points <= 200) {
                    $current_rank = 'd';
                }elseif ($current_points > 200 && $current_points <= 400) {
                    $current_rank = 'c';
                }
                elseif ($current_points > 400) {//requst go to manager 
                    if ($current_points > 400  && $current_points <= 500 ){
                        $next_rank='b';
                    }elseif($current_points > 500  && $current_points <= 600){
                        $next_rank='a';
                    }elseif($current_points > 600  && $current_points <= 700){
                        $next_rank='s';
                    }
                    $student_id=$user->user_id;
                    $first_name=$user->first_name;
                    $last_name=$user->last_name;
                    $username=$user->username;
                    $role=$user->role;
                    $current_rank = $user_rank->rank ;
                        $RRank = new InterviewRequest([
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            'username' => $username,
                            'role' => $role,
                            'current_rank'=>$current_rank,
                            'next_rank'=>$next_rank,
                        ]);
                        $RRank->save();
                }
            }
                //echo $current_rank;
                //echo $id;
                //DB::update('update student_ranks set rank = e where student_id= 11');
                DB::update('update student_ranks set rank = ? where student_id= ?', [$current_rank, $id]);
                echo "Record updated successfully.";
                //return redirect()->route('login')->with('success', 'update rank complete');
            }
        
        }
//end of rank function  //do it tomorow 



//start of notification function 
    public function notifications(){

    $id = Auth()->id();//get user id from currunt session 
    $rank = DB::table('student_notifications')->where('user_id', $id)->get();
        foreach ($rank as $user_rank) {
        return response()->json([
                'attributes'=>[
                    'id'=>$user_rank->user_id,
                    'student_id'=>$user_rank->points,
                    'message_id'=>$user_rank->rank,
            ]
                ]);
            }
} //add join with message and also do it tomorow
//end of notification function 


//start of tasks function 
    public function tasks(Request $request){
    $Authorization = request()->header('Authorization');
    if (!$Authorization) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    // Validate authorization token with client guard
    $student = Student::where('Authorization', $Authorization)->first();
    if (!$student) {
        return response()->json(['error' => 'Invalid token'], 401);
    }

    $tasks = DB::table('tasks')->get();
    $data = [];
    foreach ($tasks as $task) {
        $data[] = [
            'task_id'=>$task->id,
            'title'=>$task->title,
            'category'=>$task->category,
            'description'=>$task->description,
            'level'=>$task->level,
            'rank'=>$task->rank,
            'file_path'=>$task->file_path,
            'points'=>$task->points,
        ];
    }
    return response()->json([
        'data' => $data
    ]);  
} //done


//>orderBy('created_at', 'desc')->first()

    public function lastes_tasks(){
        $Authorization = request()->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Validate authorization token with client guard
        $student = Student::where('Authorization', $Authorization)->first();
        if (!$student) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        $tasks = DB::table('tasks')->orderBy("id", "desc")->limit(2)->get();
        $data = [];
        foreach ($tasks as $task) {
            $data[] = [
                'task_id'=>$task->id,
                'title'=>$task->title,
                'category'=>$task->category,
                'description'=>$task->description,
                'level'=>$task->level,
                'rank'=>$task->rank,
                'file_path'=>$task->file_path,
                'points'=>$task->points,
            ];
        }
        return response()->json([
            'data' => $data
     ]);  
    }//done

    public function last_solved(){
        $Authorization = request()->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Validate authorization token with client guard
        $student = Student::where('Authorization', $Authorization)->first();
        if (!$student) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        $student_id = $student->student_id;
        $solved_tasks = StudentSolvedTask::where('student_id', $student_id)
            ->orderBy('created_at', 'desc')
            ->get();
        $tasks = [];
        foreach ($solved_tasks as $solved_task) {
            $task = Task::find($solved_task->task_id);
            if ($task) {
                $tasks[] = $task;
            }
        }
        return $tasks;
    }//done


    public function calculateCategoryIndicators() {
        $Authorization = request()->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Validate authorization token with client guard
        $student = Student::where('Authorization', $Authorization)->first();
        if (!$student) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        $student_id = $student->student_id;

        $webSecurityCount = DB::table('student_solved_tasks')
            ->where('category', 'web_security')
            ->where('student_id', $student_id)
            ->whereNotNull('points')
            ->count();

        $webDevCount = DB::table('student_solved_tasks')
            ->where('category', 'web_development')
            ->where('student_id', $student_id)
            ->whereNotNull('points')
            ->count();

        $uiUxCount = DB::table('student_solved_tasks')
            ->where('category', 'ui/ux')
            ->where('student_id', $student_id)
            ->whereNotNull('points')
            ->count();

        $total = $webSecurityCount + $webDevCount + $uiUxCount;

        $webSecurityPercent = $total > 0 ? round(($webSecurityCount / $total) * 100, 2) : 0;
        $webDevPercent = $total > 0 ? round(($webDevCount / $total) * 100, 2) : 0;
        $uiUxPercent = $total > 0 ? round(($uiUxCount / $total) * 100, 2) : 0;

        return [
            'web_security' => $webSecurityPercent,
            'web_development' => $webDevPercent,
            'ui_ux' => $uiUxPercent
        ];
    }//done 

    public function getStarsCountForStudent() {
        $Authorization = request()->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Validate authorization token with client guard
        $student = Student::where('Authorization', $Authorization)->first();
        if (!$student) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        $student_id = $student->student_id;
        $stars_count = Star::where('student_id', $student_id)->count();
        return response()->json(['stars_count' => $stars_count], 200);
    }//done


//start of roadmap function 
    public function roadmaps(){
        $Authorization = request()->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Validate authorization token with client guard
        $student = Student::where('Authorization', $Authorization)->first();
        if (!$student) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        //$roadmaps = DB::table('roadmaps')->get();
        $roadmaps = DB::table('roadmaps')
        ->join('managers', 'roadmaps.manager_id', '=', 'managers.id')
        ->select('roadmaps.id', 'managers.first_name','managers.last_name','roadmaps.title','roadmaps.description')
        ->get();
        foreach ($roadmaps as $roadmap) {
            $data[] = [
                'roadmap_id'=>$roadmap->id,
                'first_name'=>$roadmap->first_name,
                'last_name'=>$roadmap->last_name,
                'title'=>$roadmap->title,
                'description'=>$roadmap->description,
            ];
        } 
        return response()->json([
            'data' => $data
        ]);  
    }//done
    
    public function submitTasks(Request $request){
        $tasks = Task::all();
        $data = [
            'title' => 'Submit Solution',
            'tasks' => $tasks
        ];
        return view('student/submit_task', $data);
    }// just view 

    public function submitTask(Request $request){
        $Authorization = request()->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Validate authorization token with client guard
        $student = Student::where('Authorization', $Authorization)->first();
        if (!$student) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        $student_id = $student->student_id;
        $task_id = $request->task_id;
        $orginal_task = Task::where('id', $task_id)->first();
        $category = $orginal_task->category;
        $level = $orginal_task->level;
        $solution = $request->solution;;
        $report = $orginal_task->report;
        $file_path = '';
        // check if file was uploaded
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_path = $file->store('uploads/tasks');
        }
        // check if student has already submitted the task
        $submitted_task = StudentSolvedTask::where('student_id', $student_id)
            ->where('task_id', $task_id)
            ->first();

        if ($submitted_task) {
            return response()->json(['error' => "You already sumbit the task "], 400);
        }
        // save task to student_solved_tasks table
        $task = new StudentSolvedTask();
        //echo $request->student_id;
        // check category and level to determine solution/report
        if ($category == 'ui/ux') {
            // no solution/report needed
            $task->task_id = $task_id;
            $task->student_id = $student_id;
            $task->file_path = $file_path;
            $task->category= $category;
            $task->save();
            return response()->json(['ok' => "Task upload successfully "], 200);
        } elseif ($category == 'web_security') {
            if ($level == 'easy') {
                $task->solution = $solution;
                $task_solution = TaskSolution::where('task_id', $task_id)->first();

                if ($task_solution->solution == $solution) {
                    $task->task_id = $task_id;
                    $task->student_id = $student_id;
                    $task->category= $category;
                    $task->solution = $solution;
                    $task->file_path = $file_path;
                    $task->points = $orginal_task->points;
                    $task->save();
                    // update student points in student_ranks table
                    $rank = StudentRank::where('student_id', $student_id)->first();
                    $rank->points += $orginal_task->points;
                    $rank->save();

                    return response()->json(['ok' => "congrats you solved it"], 200);
                } else {
                    return response()->json(['error' => "Incorrect solution"], 400);
                }
            } else {//for medim and hard security task
                $task->task_id = $task_id;
                $task->student_id = $student_id;
                $task->category= $category;
                $task->report = $report;
                $task->save();
            }
        } else { // web_development
            if ($level == 'easy') {
                $task->solution = $solution;
                $task_solution = TaskSolution::where('task_id', $task_id)->first();
                if ($task_solution->solution == $solution) {
                    $task->task_id = $task_id;
                    $task->student_id = $student_id;
                    $task->category= $category;
                    $task->solution = $solution;
                    $task->file_path = $file_path;
                    $task->points = $orginal_task->points;
                    $task->save();
                    // update student points in student_ranks table
                    $rank = StudentRank::where('student_id', $student_id)->first();
                    $rank->points += $orginal_task->points;
                    $rank->save();
                    return response()->json(['ok' => "congrats you solev it  "], 200);
                }
                else {
                        return response()->json(['error' => "Incorrect solution"], 400);
                     }
            }
            else{
                $task->task_id = $task_id;
                $task->student_id = $student_id;
                $task->category= $category;
                $task->file_path = $file_path;
                $task->save();
                return response()->json(['ok' => "Task upload successfully "], 200);
            }    
        }

        return response()->json(['ok' => "Task upload successfully "], 200);
   //return redirect()->back()->with('success', 'Task submitted successfully.');
    }//done tset with web_security and web_deveolmpent tasks 



//end of roadmap function 



//start of projects function

//here the with blade 
    public function projectsvue(){
        $projects = DB::table('client_projects')->get();
        //$data['title'] = 'teams show';
        return view('student/projects', ['projects' => $projects, 'title' => 'teams show']);
    } //no nedded just vue 


    public function projects(){
        $Authorization = request()->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Validate authorization token with client guard
        $student = Student::where('Authorization', $Authorization)->first();
        if (!$student) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        $data = [];
        $projects = DB::table('client_projects')->whereNull('status')->get();
        foreach ($projects as $project) {
            $data[] = [
                'project_id'=>$project->id,
                'title'=>$project->title,
                'category'=>$project->category,
                'description'=>$project->description,
                'deadline'=>$project->deadline,
                'frontend'=>$project->frontend,
                'backend'=>$project->backend,
                'ui_ux'=>$project->ui_ux,
                'security'=>$project->security,
            ];
        }
        return response()->json([
            'data' => $data
        ]);
    }//done //get project that has no status yet  


//start of StudentJoinProjects
//student send requst to project as indevisual 

    // public function student_project_request(Request $request){ 
    //     // $Authorization = request()->header('Authorization');
    //     // if (!$Authorization) {
    //     //     return response()->json(['error' => 'Unauthorized'], 401);
    //     // }
    //     // // Validate authorization token with client guard
    //     // $student = Student::where('Authorization', $Authorization)->first();
    //     // if (!$student) {
    //     //     return response()->json(['error' => 'Invalid token'], 401);
    //     // }
    //     //$student_id = $student->student_id;
    //     $student_id = Auth::guard('student')->id();

    //     $rank = DB::table('student_ranks')->where('student_id', $student_id)->first();
    //     $user = DB::table('students')->where('student_id', $student_id)->first();
    //     $project_id = $request->project_id;
    //     $project = Client_projects::find($project_id);
    //     $userpoint= $rank->points;
    //     $project_rank = DB::table('global_ranks')->where('rank', $project->rank)->first();  
    //    // $clientinfo= DB::table('clients')->where('client_id', $project->client_id)->first();
    //     if ($project_rank->start_points<=$userpoint){
    //         $request = StudentJoinProject::create([
    //             'project_id' => $project_id,
    //             'project_title' => $project->title,
    //             'client_id'=> $project->client_id,
    //             'student_id' => $user->student_id,
    //             'student_name' => $user->username,
    //             'student_role'=>$user->role
    //         ]);
    //         $request->save();
    //     }
    //     else{
    //         return response()->json(['ok' => "you cant join to this project keep rocking until you be able to join   "], 200);
    //     }
          
    // }//do it tomorow 

    public function student_project_request(Request $request){ 
        $Authorization = request()->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Validate authorization token with client guard
        $student = Student::where('Authorization', $Authorization)->first();
        if (!$student) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        $student_id = $student->student_id;
        $rank = DB::table('student_ranks')->where('student_id', $student_id)->first();
        $user = DB::table('students')->where('student_id', $student_id)->first();
        $project_id = $request->project_id;
        $project = Client_projects::find($project_id);
        $userpoint= $rank->points;
        $project_rank = DB::table('global_ranks')->where('rank', $project->rank)->first();
    
        // Check if user has already requested to join the project
        $existing_request = StudentJoinProject::where('project_id', $project_id)
                                                ->where('student_id', $student_id)
                                                ->first();
        if ($existing_request) {
            return response()->json(['error' => "You have already sent a request to join this project."], 400);
        }
    
        if ($project_rank->start_points<=$userpoint){
            $request = StudentJoinProject::create([
                'project_id' => $project_id,
                'project_title' => $project->title,
                'client_id'=> $project->client_id,
                'student_id' => $user->student_id,
                'student_name' => $user->username,
                'student_role'=>$user->role
            ]);
            $request->save();
            return response()->json(['ok' => "request send successfully  "], 200);
        } else {
            return response()->json(['ok' => "you cant join to this project keep rocking until you be able to join   "], 200);
        }
    }//done 
    
//end of user join project indevitual 
//end of StudentJoinProjects
//start of team send join project request 
    public function team_project_request(Request $request){
         $student_id = Auth::guard('student')->user()->student_id;
         $user = Auth::user();
         $project_id = $request->project_id;
         $project = Client_projects::find($project_id);
     
         // Check if the current student has created a team
         
         $team = DB::table('student_teams')->where('student_id', $student_id)->first();
         $team_leader = DB::table('teams')->where('team_id', $team->team_id)->first();
     
         if (!$team) {
             return response()->json(['error' => "You have not created a team. Please create a team first"], 403);
         }
         
         // Check if the team has already sent a request to join this project
         $team_join_project = DB::table('team_join_projects')
                                 ->where('team_id', $team->team_id)
                                 ->where('project_id', $project_id)
                                 ->first();
         
         if ($team_join_project) {
             return response()->json(['error' => "You have already sent a request to join this project"], 403);
         }
     
         // Allow the team to send a request to join a new project
         $existing_team_join_projects = DB::table('team_join_projects')
                                 ->where('team_id', $team->team_id)
                                 ->get();
     
         foreach ($existing_team_join_projects as $existing_team_join_project) {
             if ($existing_team_join_project->project_id == $project_id) {
                 return response()->json(['error' => "You have already sent a request to join this project"], 403);
             }
         }
     
         $request = TeamJoinProject::create([
             'project_id' => $project_id,
             'project_title' => $project->title,
             'team_id' => $team->team_id,
         ]);
     
         return response()->json(['ok' => "Your request has been sent successfully"], 200);
    } //do it tomorow 

     //end of team send join project request 
    //good but organize it 
// public function team_project_request(Request $request){
// $student_id = Auth::guard('student')->user()->student_id;
// $user = Auth::user();
// $project_id = $request->project_id;
// $project = Client_projects::find($project_id);
// $teams = DB::table('student_teams')->where('student_id', $student_id)->get();
// foreach($teams as $team){$team_leader=$team->team_leader;$team_id_exist=$team->team_id;}
// $team_projects = DB::table('team_join_projects')->where('team_id', $team_id_exist)->get();
// //echo $team_projects;
// if ($team_projects->isEmpty()){
// //echo $temp;
// if($teams->count() > 0){
//     if($team_leader == 1 ){
//     $request = TeamJoinProject::create([
//         'project_id' => $project_id,
//         'project_title' => $project->title,
//         'team_id' => $teams->first()->team_id,
//     ]);
//     //echo $request;
 
//     //$request->save();

//     return redirect()->route('home')->with('success', 'Your request has been sent successfully');
// }
// } else{
//     return redirect()->route('home')->with('error', 'You are not on a team');
// }
// }
// }

//end of user request to join to project 



//end  of projects function


    //make this to grep team mebers 
    public function get_teams(){
        $teams = DB::table('teams')->first();
        //$userIds = json_decode($team->team_members); 
        //echo $userIds;
        foreach ($teams as $team) {

            return response()->json([
                'attributes'=>[
                    'id'=>$teams->id,
                    'team_members'=>$teams->team_members,
            ]
                ]);

            }
    }

    public function test(Request $request){
        echo "holla";
    }

    public function create_team(Request $request){
        $data['title'] = 'create team ';
        return view('student/create_team', $data);
        //echo "holla";
    } //no nedded jsu vue
    

    public function create_team_action(Request $request){
        $Authorization = request()->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Validate authorization token with client guard
        $student = Student::where('Authorization', $Authorization)->first();
        if (!$student) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        $student_id = $student->student_id;
        $teams = DB::table('student_teams')->where('student_id', $student_id)->get();

        if ($teams->count() > 0) {
            return response()->json(['error' => "you are already a team member."], 422);
        }

        $validatedData = $request->validate([
            'team_name' => 'required|unique:teams'
        ]);
        $invitation_link = Str::random(10);
        $team = Team::create([
            'team_name' => $validatedData['team_name'],
            'invitation_link' => $invitation_link,
            'team_leader'=>$student_id
        ]);
        $users = Auth()->id();
        $team->users()->attach($users);
    }//done

    
    public function join_team(){
        $data['title'] = 'teams show';
        return view('student/join_team', $data);
    } //no nedded just vue
    
    public function join_team_action(Request $request){
        $Authorization = request()->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Validate authorization token with client guard
        $student = Student::where('Authorization', $Authorization)->first();
        if (!$student) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        $student_id = $student->student_id;
        $teams = DB::table('student_teams')->where('student_id', $student_id)->get();
        
        if ($teams->count() > 0) {
            return response()->json(['error' => "you are already a team member."], 422);
        }
        
        $invitation_link = $request->invitation_link;
        $team = Team::where('invitation_link', $invitation_link)->first();
        
        $team->users()->attach($student_id,['team_id'=>$team->team_id],['team_leader' => 0 ]);
    }//done
    
    //midle ware
// public function __construct()
// {
//     $this->middleware(CheckInvitationLink::class);
// }
//
    // public function edit_team_action(Request $request)
    // {
    //     $user = Auth::user();
    //     if($user){
    //         if($user->teams->count()>0){
    //             return redirect()->intended('/')->with('fail', 'you already team member ');
    //         }
    //     }
    // }

}//end of controller 