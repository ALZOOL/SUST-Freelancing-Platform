<?php

namespace App\Http\Controllers;

use App\Models\Client_projects;
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
use App\Models\StudentTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

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
            $student->last_login_at = now();
            $student->save(); 
            $request->session()->regenerate();
            return response()->json([
                "student" => $studentinfo,
                "Authorization" => $Authorization
            ]);
            
           // $request->session()->regenerate();

           // return view('student.home');
        }
        return response()->json(['ok' => "wrong username or password"], 200);
    }//done back and delete seesion reganration and uncomment the return response

//end login finction 


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
        //$id = Auth::guard('student')->user()->student_id ;//get user id from currunt session 
        $user = DB::table('students')->where('student_id', $student_id)->first();
        $user_rank = DB::table('student_ranks')->where('student_id', $student_id)->first();
        $student_global_current_rank= DB::table('global_ranks')->where('rank', $user_rank->rank)->first();
        $student_global_next_rank=DB::table('global_ranks')->where('id', $student_global_current_rank->id+1)->first();
        $next_start_points=$student_global_next_rank->start_points;
        $next_end_points=$student_global_next_rank->end_points;
        $stars_count= Star::where('student_id', $student_id)->count();
    
        $current_points = $user_rank->points;
    
            if ($current_points >= $next_start_points && $current_points <= 300) {
                $current_rank = $student_global_next_rank->rank;
                DB::update('update student_ranks set rank = ? where student_id= ?', [$current_rank, $student_id]);
                return response()->json(['ok' => "Rank Upgrade Successfully "], 200);
            }
            elseif ($current_points >= 301) {//requst go to manager 
                if ($stars_count >= 7 && $current_points>=1301) {
                    $next_rank='SSS';
                }
                elseif($stars_count <= 3 && $current_points>=1301){
                    $next_rank='SS';
                }
                elseif($current_points >= $next_start_points){
                    $next_rank= $student_global_next_rank->rank ;
                }
                $first_name=$user->first_name;
                $last_name=$user->last_name;
                $username=$user->username;
                $role=$user->role;
                $current_rank = $user_rank->rank ;
                    $RRank = new InterviewRequest([
                        'student_id'=>$student_id,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'username' => $username,
                        'role' => $role,
                        'current_rank'=>$current_rank,
                        'next_rank'=>$next_rank,
                        'status'=>"requested",
                    ]);
                    $RRank->save();
                    return response()->json(['ok' => "Rank Upgrade Request Sent Successfully "], 200);
            }

                return response()->json(['error' => "You dont have enegh points "], 200);  
    }//end of rank function 




//start of notification function 
    public function notifications(){
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
        $notification = DB::table('student_notifications')->where('student_id', $student_id)
        ->join('messages','student_notifications.message_id','=','messages.id')->orderBy("id", "desc")
        ->get(['student_notifications.id', 'student_notifications.message_id', 'messages.message']);
        return response()->json([
            'notifications' => $notification
        ]);
    } //end
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
        $project_id = $request->project_id;
        $project = Client_projects::find($project_id);
        // Check if the current student has created a team
        $team = DB::table('student_teams')->where('student_id', $student_id)->first();
        $team_leader = DB::table('teams')->where('team_leader', $student_id)->exists();
 
        if (!$team) {
            return response()->json(['error' => "You have not created a team. Please create a team first"], 403);
        } elseif (!$team_leader) {
            return response()->json(['error' => "You are not the team leader. Only the team leader can send requests."], 403);
        }

        // Check if the team has already sent a request to join this project
        $team_join_project = DB::table('team_join_projects')
                                ->where('team_id', $team->team_id)
                                ->where('project_id', $project_id)
                                ->first();

        if ($team_join_project) {
            return response()->json(['error' => "You have already sent a request to join this project"], 403);
        }
 
        $request = TeamJoinProject::create([
            'project_id' => $project_id,
            'project_title' => $project->title,
            'team_id' => $team->team_id,
        ]);
 
        return response()->json(['ok' => "Your request has been sent successfully"], 200);
    }//done
 
//end of user request to join to project 



//end  of projects function


    //make this to grep team mebers 
    public function get_team_members(Request $request){
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
        $team_leader = Team::where('team_leader', $student_id)->exists();

        $team_members = StudentTeam::where('student_teams.team_id', $student->team_id)
                ->join('students', 'student_teams.student_id', '=', 'students.student_id')
                ->join('student_ranks','student_ranks.student_id','=','students.student_id'  )
                ->get(['students.student_id', 'students.username', 'students.role','student_ranks.rank']);

        return response()->json([
            'team_members' => $team_members,
            'team_leader'=>$team_leader
        ]);
    }//done with team leader value 

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
        $students = Student::where('Authorization', $Authorization)->first();
        $student = Student::find($students->student_id);
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
        //$users = $student_id;
        $team->users()->attach($student->student_id);
        $student->team_id= $team->team_id;;
        $student->save();

        return response()->json(['error' => "Team create successfully "], 422);


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
        $students = Student::where('Authorization', $Authorization)->first();

        $student = Student::find($students->student_id);
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
        
        $team->users()->attach($student_id,['team_id'=>$team->team_id]);
        $student->team_id= $team->team_id;;
        $student->save();

    }//done //add vervication for checking if not valid return respone 
    

    public function edit_team_name(Request $request){
        $Authorization = request()->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Validate authorization token with student guard
        $student = Student::where('Authorization', $Authorization)->first();
        if (!$student) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        $student_id = $student->student_id;
        $team_leader = DB::table('teams')->where('team_leader', $student_id)->exists();
        $teams = DB::table('teams')->where('team_leader', $student_id)->first();
        if(!$teams && !$team_leader){
            return response()->json(['error' => "You are not the team leader. Only the team leader can edit team."], 403);
        }
        $team = Team::find($teams->team_id);

        try{
        $request->validate([
            'team_name' => [
                'nullable',
                'string',
                Rule::unique('teams','team_name')->ignore($team->team_id,'team_id'),
            ],
        ]);
        }catch (ValidationException $e) {
            $errors = $e->validator->errors();
            if ($errors->has('team_name')) {
                return response()->json(['error' => 'Team name is already used'], 422);
            }
            throw $e;
        }


        if ($request->filled('team_name')) {
            $team->team_name = $request->input('team_name');
        }
        $team->save();
        // Return a success response
        return response()->json(['message' => 'Team name update successfully.']);

    }//done


    public function delete_team(Request $request){
        $Authorization = request()->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Validate authorization token with student guard
        $student = Student::where('Authorization', $Authorization)->first();
        if (!$student) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        $student_id = $student->student_id;
        $team_leader = DB::table('teams')->where('team_leader', $student_id)->exists();
        $team = DB::table('teams')->where('team_leader', $student_id)->first();
        if (!$team_leader) {
            return response()->json(['error' => "You are not the team leader. Only the team leader can delete the team."], 403);
        }

        $team_members = DB::table('student_teams')
        ->where('student_teams.team_id', $team->team_id)
        ->join('students', 'student_teams.student_id', '=', 'students.student_id')
        ->get();
        foreach ($team_members as $team_member) {
            Student::where('students.team_id', $team->team_id)
                ->where('students.student_id', $team_member->student_id)
                ->update(['students.team_id' => null]);
        }
    

        DB::table('student_teams')->where('team_id',$team->team_id)->delete();

        // Delete team record
        Student::where('team_id', $team->team_id)->update(['team_id' => null]);
        
        Team::where('team_id', $team->team_id)->update(['team_leader' => null]);

        DB::table('teams')->where('team_id',$team->team_id)->delete();
    
    
        return response()->json(['message' => 'Team delete successfully.']);

    }//done with test also



    public function delete_team_member(Request $request){
        $Authorization = request()->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Validate authorization token with student guard
        $student = Student::where('Authorization', $Authorization)->first();
        if (!$student) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        $student_id = $student->student_id;
        $deleted_student= $request->student_id;
        $team_leader = DB::table('teams')->where('team_leader', $student_id)->exists();
        $team = DB::table('teams')->where('team_leader', $student_id)->first();
        if (!$team_leader) {
            return response()->json(['error' => "You are not the team leader. Only the team leader can delete the team members."], 403);
        }
        DB::table('student_teams')->where('team_id',$team->team_id)
        ->where('student_id',$deleted_student)->delete();
        
        Student::where('team_id', $team->team_id)
        ->where('student_id', $deleted_student)->update(['team_id' => null]);
    
        return response()->json(['message' => 'Student remove from team successfully.']);

    }//done 



    public function changeinfo(Request $request) {
        $Authorization = request()->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Validate authorization token with student guard
        $student = Student::where('Authorization', $Authorization)->first();
        if (!$student) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        $student_id = $student->student_id;


        try{
            $request->validate([
                'email' => [
                    'nullable',
                    'email',
                    Rule::unique('students', 'email')->ignore($student_id, 'student_id'),
                ],
                'username' => [
                    'nullable',
                    'string',
                    Rule::unique('students', 'username')->ignore($student_id, 'student_id'),
                ],
            ]);
        }catch (ValidationException $e) {
            $errors = $e->validator->errors();
            if ($errors->has('email')) {
                return response()->json(['error' => 'Email address is already used'], 422);
            }
            elseif($errors->has('username')){
                return response()->json(['error' => 'username is already used'], 422);
            }
            throw $e;
        }
        if ($request->filled('first_name')) {
            $student->first_name = $request->input('first_name');
        }
        if ($request->filled('last_name')) {
            $student->last_name = $request->input('last_name');
        }
        if ($request->filled('username')) {
            $student->username = $request->input('username');
        }
        if ($request->filled('email')) {
            $student->email = $request->input('email');
        }
        if ($request->filled('avatar')) {
            $student->avatar = $request->input('avatar');
        }
        if ($request->filled('role')) {
            $student->role = $request->input('role');
        }
        if ($request->filled('password')) {
            $student->password = Hash::make($request->input('password'));
        }
        // Save the updated student information
        $student->save();
        // Return a success response
        return response()->json(['message' => 'User information updated successfully.']);
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