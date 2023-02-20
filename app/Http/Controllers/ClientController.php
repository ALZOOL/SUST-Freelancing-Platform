<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\ClientProjectRequest;
use App\Models\Client_projects;
use App\Models\ContactUs;


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
        return response()->json(['ok' => "registration successfully complete"], 200);

        //return redirect()->route('login')->with('success', 'Registration success. Please login!');
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
            //$request->session()->regenerate();
            return response()->json(['ok' => "login successfully"], 200);

            //return view('client.home');
            //return redirect()->intended('client.home');
        }

        return back()->withErrors([
            'password' => 'Wrong username or password',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['ok' => "logout successfully"], 200);
        //return redirect('client/login');
    }

    public function client_project_requetses()
    {
        $data['title'] = 'Login';
        return view('client.client_project_requets', $data);
    }

    public function client_project_requets(Request $request)
{
    $id = Auth::guard('client')->user()->client_id;

    $request->validate([
        'project_title' => 'required',
        'project_description' => 'required',
        'project_file' => 'nullable|file|mimes:pdf'
    ]);

    $path = null;
    if ($request->hasFile('project_file')) {
        $path = $request->file('project_file')->store('uploads');
    }

    $projectRequest = new ClientProjectRequest;
    $projectRequest->client_id = $id;
    $projectRequest->project_title = $request->project_title;
    $projectRequest->project_description = $request->project_description;
    $projectRequest->project_file_path = $path;
    $projectRequest->save();
    return response()->json(['ok' => "Project request submitted successfully."], 200);
    

    //return redirect()->back()->with('success', '');
}



public function getProjects()
{
    $client_id = Auth::guard('client')->id();

    $projects = Client_projects::select('title', 'category', 'description', 'status')
        ->where('client_id', $client_id)
        ->get();
        //->with(['team.members'])
    return $projects;
}

public function contacts_us()
    {
        $data['title'] = 'Login';
        return view('client.contact', $data);
    }

public function contact_us(Request $request)
    {
        $request->validate([
            'description' => 'required'
        ]);

        $contactUs = new ContactUs;
        $contactUs->client_id = Auth::guard('client')->user()->client_id;
        $contactUs->client_email = Auth::guard('client')->user()->email;
        $contactUs->description = $request->description;
        $contactUs->save();
        return response()->json(['ok' => "message has been send seccessfuly"], 200);

        //return redirect()->back()->with('success', 'Your message has been sent. We will get back to you soon!');
    }


}