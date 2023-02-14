<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            return redirect()->route('admin');
        }

        if ($role=='Manager')
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
    //##############
    public function test_fun()
    {
        return view('test');
    }
}
