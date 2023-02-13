<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Manager;
use App\Models\User;
class ManagerDashboardMovementController extends Controller
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

    /* public function projects_requests()
    {
        return view('ManagerDashboard.projects_requests');
    }

    public function approved_projects()
    {
        return view('ManagerDashboard.approved_projects');
    }

    public function team_requests()
    {
        return view('ManagerDashboard.team_requests');
    }

    public function tasks()
    {
        return view('ManagerDashboard.tasks');
    }

    public function rank_interview()
    {
        return view('ManagerDashboard.rank_interview');
    }

    public function rank_interview2()
    {
        return view('ManagerDashboard.rank_interview2');
    }

    public function submitted_tasks()
    {
        return view('ManagerDashboard.submitted_tasks');
    }

    public function submitted_web_page()
    {
        return view('ManagerDashboard.submitted_tasks.web');
    }


    public function roadmaps()
    {
        return view('ManagerDashboard.roadmaps');
    }
    
    //TASKS CREATION WEB-SECURITY-DESGIN MOVEMENT #1#
    
    public function create_web_task()
    {
        return view('ManagerDashboard.tasks.web');
    }

    public function create_security_task()
    {
        return view('ManagerDashboard.tasks.security');
    }

    public function create_desgin_task()
    {
        return view('ManagerDashboard.tasks.desgin');
    }
    //#1# */
}
