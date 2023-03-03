<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\ClientProjectRequest;
use App\Models\Client_projects;
use App\Models\ContactUs;
use App\Models\Manager;
use App\Models\ProjectsTeam;
use App\Models\Star;
use App\Models\ProjectsTeamMember;
use App\Models\Student;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;




class ClientController extends Controller
{
    public function register()
    {
        return view('client.register');
    }

    public function register_action(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:clients',
            'password' => 'required',
            'password_confirm' => 'required|same:password',
        ], [
            'required' => 'Please fill the :attribute field',
            'unique' => 'This :attribute is already used',
            'same' => 'The :attribute and :other must match',
        ]);
    
        $Client = new Client([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'company_name' => $request->company_name,
            'company_email' => $request->company_email,
            'password' => Hash::make($request->password),
        ]);
        $Client->save();
    
        return response()->json(['success' => 'Registration completed successfully'], 200);
    }
    
    public function login()
    {
        $data['title'] = 'Login';
        return view('client.login', $data);
    }

    public function login_action(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        if (Auth::guard('client')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $client = Client::where('email', $request->email)->first();
            $clientinfo=Client::where('email', $request->email)->select('first_name', 'last_name', 'company_name', 'email', 'company_email')->first();
            // Generate a unique cookie value for the client
            $Authorization = Str::random(40);
            // Update the client's cookie_value field with the generated value
            $client->Authorization = $Authorization;
            $client->last_login_at = now();
            $client->save();

            return response()->json([
                "client" => $clientinfo,
                "Authorization" => $Authorization
             ]);
        } else {
             return response()->json(['ok' => "wrong username or password"], 200);
        }
    }



public function getProjects()
{
    $Authorization = request()->header('Authorization');
    if (!$Authorization) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    $client = Client::where('Authorization', $Authorization)->first(); //get existing user by the Authorization header
    if (!$client) {
        return response()->json(['error' => 'invalid token'], 401);
    }
    // Get projects for authenticated client
    $projects = Client_projects::select('title', 'category', 'description', 'status')
        ->where('client_id', $client->client_id)
        ->get();

    return response()->json([
        "projects" => $projects,
    ]);
}
public function logout(Request $request)
{
    $Authorization = $request->header('Authorization');
    if (!$Authorization) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    $client = Client::where('Authorization', $Authorization)->first();
    if (!$client) {
        return response()->json(['error' => 'Invalid token'], 401);
    }
    $client->update(['Authorization' => null]);
    Auth::guard('client')->logout();
    return response()->json(['ok' => "logout successfully"], 200);
}


public function getProjectsAndTeams() {
    $Authorization = request()->header('Authorization');
    if (!$Authorization) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    // Validate authorization token with client guard
    $client = Client::where('Authorization', $Authorization)->first();
    if (!$client) {
        return response()->json(['error' => 'Invalid token'], 401);
    } //add from mahdi until here 
    $client_id = $client->client_id;
    $client_projects = Client_projects::where('client_id', $client_id)->get();

    $data = [];
    foreach ($client_projects as $project) {
        $team_members = ProjectsTeamMember::where('team_id', $project->team_id)->get();
        $team_leader = ProjectsTeam::where('id', $project->team_id)->first();

        $team_members_data = [];
        foreach ($team_members as $team_member) {
            $student = Student::find($team_member->student_id);
            $team_members_data[] = [
                'id' => $student->student_id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'email' => $student->email,
                // add more fields as needed
            ];
        }
        $team_leader_data = null;
        if ($team_leader) {
            $team_leader_data = $team_leader->manager_id;
            $manager = Manager::find($team_leader_data);
            $mfirstname=$manager->first_name;
            $mlastname=$manager->last_name;
            $memail=$manager->email;

        }
       
        $data[] = [
            'project_id' => $project->id,
            'project_title' => $project->title,
            'project_category' => $project->category,
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
            'start_date' => $project->start_date,
            'manager_first_name'=> $mfirstname,
            'manager_last_name'=> $mlastname,
            'manager_email'=>$memail,
            'team_members' => $team_members_data
        ];
    }

    return response()->json(['data' => $data], 200);
}

public function client_project_requetses()
{
    $data['title'] = 'Login';
    return view('client.client_project_requets', $data);
}

public function client_project_requets(Request $request)
{
    $Authorization = request()->header('Authorization');
    if (!$Authorization) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    $client = Client::where('Authorization', $Authorization)->first();
    if (!$client) {
        return response()->json(['error' => 'Invalid token'], 401);
    }
    $id = $client->client_id;

    $request->validate([
        'project_title' => 'required',
        'project_description' => 'required',
        'project_file' => 'nullable|file'
    ]);

    $path = null;
    if ($request->hasFile('project_file')) {
        $path = $request->file('project_file')->store('uploads/clientprojectrequest');
        $url = url('/').'/'.$path;
                //$absoluetePath= Storage::path($path);
    }

    $projectRequest = new ClientProjectRequest;
    $projectRequest->client_id = $id;
    $projectRequest->project_title = $request->project_title;
    $projectRequest->project_description = $request->project_description;
    $projectRequest->project_file_path = $url;
    $projectRequest->status = 'requested';
    $projectRequest->save();

    return response()->json(['ok' => "Project request submitted successfully."], 200);
}

public function contacts_us()
    {
        $data['title'] = 'Login';
        return view('client.contact', $data);
    }

    public function contact_us(Request $request)
    {
        $Authorization = request()->header('Authorization');
        if (!$Authorization) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $client = Client::where('Authorization', $Authorization)->first();
        if (!$client) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
        $request->validate([
            'description' => 'required'
        ]);
        $contactUs = new ContactUs;
        $contactUs->client_id = $client->client_id;
        $contactUs->client_email = $client->email;
        $contactUs->description = $request->description;
        $contactUs->save();
        return response()->json(['ok' => "message has been sent successfully"], 200);
    }
    

    //start of star function : // here the user give star to the team 
    public function showstar()
{
    $Authorization = request()->header('Authorization');
    if (!$Authorization) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    $client = Client::where('Authorization', $Authorization)->first();
    // Validate authorization token with client guard
    if (!$client) {
        return response()->json(['error' => 'Invalid token'], 401);
    }
    
    $client_id = Auth::guard('client')->id();
    
    $projects = DB::table('client_projects')
        ->join('projects_teams', 'client_projects.team_id', '=', 'projects_teams.id')
        ->select('client_projects.id', 'projects_teams.id as team_id')
        ->where('client_id', $client_id)
        ->get();
        
    return response()->json([
        "projects" => $projects,
    ]);
}

public function checkstar(Request $request){
    $Authorization = request()->header('Authorization');
    if (!$Authorization) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    $client = Client::where('Authorization', $Authorization)->first();
    // Validate authorization token with client guard
    if (!$client) {
        return response()->json(['error' => 'Invalid token'], 401);
    }   
    if (!$request->project_id) {
        return response()->json(['error' => 'no project selected'], 401);
    }
    $existing_star = Star::where('client_id', $client->client_id)
    ->where('project_id', $request->project_id)
    ->first();

    if ($existing_star) {
    return response()->json("true");
    }
    else{
    return response()->json("false");

    }

}

public function stars(Request $request){
    $Authorization = request()->header('Authorization');
    if (!$Authorization) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    $client = Client::where('Authorization', $Authorization)->first();
    // Validate authorization token with client guard
    if (!$client) {
        return response()->json(['error' => 'Invalid token'], 401);
    }

    $client_id = $client->client_id;

    // Check if the client has already given a star to this team
    $existing_star = Star::where('client_id', $client_id)
                          ->where('project_id', $request->project_id)
                          ->first();
    if ($existing_star) {
        return response()->json(['error' => "You have already given a star to this team."], 422);
    }

    $teamMembers = DB::table('projects_team_members')->where('team_id', $request->team_id)->get();

    foreach ($teamMembers as $teamMember) {
        $star = Star::create([
            'client_id' => $client_id,
            'project_id' => $request->project_id,
            'team_id' => $request->team_id,
            'student_id' => $teamMember->student_id,
        ]);
    }
    return response()->json(['ok' => "Stars added successfully!"], 200);
}


public function notifications(){
    $Authorization = request()->header('Authorization');
    if (!$Authorization) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    // Validate authorization token with client guard
    $client = Client::where('Authorization', $Authorization)->first();
    if (!$client) {
        return response()->json(['error' => 'Invalid token'], 401);
    }
    $client_id = $client->client_id;
    $notification = DB::table('client_notifications')->where('client_id', $client_id)
    ->join('messages','client_notifications.message_id','=','messages.id')->orderBy("id", "desc")
    ->get(['client_notifications.id', 'client_notifications.message_id', 'messages.message']);
    return response()->json([
        'notifications' => $notification
    ]);
}


public function changeinfo(Request $request) {
    $Authorization = request()->header('Authorization');
    if (!$Authorization) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    // Validate authorization token with client guard
    $client = Client::where('Authorization', $Authorization)->first();
    if (!$client) {
        return response()->json(['error' => 'Invalid token'], 401);
    }
    $client_id = $client->client_id;
    try{
        $request->validate([
            'email' => [
                'nullable',
                'email',
                Rule::unique('clients', 'email')->ignore($client_id, 'client_id'),
            ],
        ]);
    }catch (ValidationException $e) {
        $errors = $e->validator->errors();
        if ($errors->has('email')) {
            return response()->json(['error' => 'Email address is already in use'], 422);
        }
        throw $e;
    }
    if ($request->filled('first_name')) {
        $client->first_name = $request->input('first_name');
    }
    if ($request->filled('last_name')) {
        $client->last_name = $request->input('last_name');
    }
    if ($request->filled('company_name')) {
        $client->company_name = $request->input('company_name');
    }
    if ($request->filled('email')) {
        $client->email = $request->input('email');
    }
    if ($request->filled('company_email')) {
        $client->company_email = $request->input('company_email');
    }
    if ($request->filled('password')) {
        $client->password = bcrypt($request->input('password'));
    }
    // Save the updated client information
    $client->save();
    // Return a success response
    return response()->json(['message' => 'User information updated successfully.']);
}//done 

}