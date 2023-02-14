<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Manager;
class AdminController extends Controller
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
    //SHOW SYSTEM-MANAGERS

    public function show_managers(){
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
        return view('admin')
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

        //return redirect()->back();
        return view('test');
    }
    
    public function test_fun()
    {
        return view('test');
    }
    
    //################

    //EDIT MANAGERS
    public function edit_system_managers($id){
    	$data = DB::table('managers')->where('id',$id)->first();
    	return view('admin.managers_edition',compact('data'));
    }

    public function update_system_managers(Request $request,$id){

    	DB::table('managers')->where('id',$id)->update([

    		'name'=>$request->name,
    		'email'=>$request->email,
            'role'=>$request->role,
            'password'=>bcrypt($request->password),
            //$data->password=bcrypt($request->password);
    	]);
   	return redirect()->route('admin');
    } 

    public function delete_system_managers($id){

        DB::table('managers')->where('id',$id)->delete();
        return redirect()->route('admin');

    }

    //####################

    //SHOW NUMBERS OF SYSTEM USERS 
    public function system_users_numbers(){
        //Retrieve data from database table
        $data = DB::table('managers')->get();

        //Convert the data into an array
        $dataArray = $data->toArray();

        //Get the length of the array
        $length = count($dataArray);
    }

}
