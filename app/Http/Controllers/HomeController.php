<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Manager;
use App\Models\User;
class HomeController extends Controller
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
    public function index()
    {
        $role=Auth::user()->role;

        if ($role=='1')
        {
            return view('admin');
        }

        if ($role=='2')
        {
            return view('manager');
        }

        if ($role=='0')
        {
            return view('student');
        }

        else
        {
            return view('home');
        }
        
    }
    //ADD NEW MANAGER 
    public function addManager(Request $request)
    {
        $data=new Manager;
        $data->name=$request->name;
        $data->email=$request->email;
        $data->role='2';
        //$data->password=$request->password;
        $data->password=bcrypt($request->password);

        $data->save();

        //return redirect()->back();
        return view('test');
    }
    public function test_fun()
    {
        return view('test');
    }
}
