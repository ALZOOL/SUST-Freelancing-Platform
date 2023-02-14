<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RoadmapController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ManagerDashboardMovementController;
use App\Http\Controllers\ProjectsRequestsController;
use App\Http\Controllers\ApprovedProjectsController;
use App\Http\Controllers\RankInterviewsController;
use App\Http\Controllers\SubmittedTasksController;
use App\Http\Controllers\TeamRequestsController;
use App\Http\Controllers\EditProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/test_url', [HomeController::class, 'test_fun'])->name('test_route');
Route::post('/addmanager',[HomeController::class,"addManager"]);

//###########


//MANAGER DASHBOARD MOVEMENT
Route::view('/Submitted_tasks', 'ManagerDashboard.submitted_tasks');
Route::view('/create_web_task', 'ManagerDashboard.tasks.web');
Route::view('/create_security_task', 'ManagerDashboard.tasks.security');
Route::view('/create_design_task', 'ManagerDashboard.tasks.design');
Route::view('/manager_profile', 'ManagerDashboard.profile.manager_profile');

/* Route::controller(ManagerDashboardMovementController::class)->group(function(){
    //Route::get('/Projects_requests',"projects_requests")->name('/projects_requests');
    Route::get('/Approved_projects',"approved_projects")->name('/approved_projects');
    Route::get('/Team_requests',"team_requests")->name('/team_requests');
    Route::get('/Tasks',"tasks")->name('/Tasks');
    Route::get('/Rank_interview',"rank_interview")->name('/rank_interview');
    Route::get('/Rank_interview2',"rank_interview2")->name('/rank_interview/upgrade');
    Route::get('/Submitted_tasks',"submitted_tasks");
    Route::get('/submitted_web',"submitted_web_page")->name('/show_web');
    Route::get('/submitted_security',"submitted_security_page")->name('/show_security');
    Route::get('/submitted_design',"submitted_design_page")->name('/show_design');
    Route::get('/Roadmaps',"roadmaps");
    Route::get('/create_web_task',"create_web_task");
    Route::get('/create_security_task',"create_security_task");
    Route::get('/create_desgin_task',"create_desgin_task");

});
 */
//ADD-EDIT-DELETE TASKS

Route::controller(TaskController::class)->group(function(){

    Route::post('/web_task',"add_web_task");
    Route::post('/security_task',"add_security_task");
    Route::post('/desgin_task',"add_desgin_task");
    Route::get('/Tasks',"show_tasks")->name('/Tasks');
    Route::get('/task/edit/{id}',"edit_task")->name('task.edit');
    Route::get('/task/delete/{id}',"delete_task")->name('task.delete');
    Route::PUT('/task/update/{id}',"update_task")->name('task.update');
//Route::guard('managers')->post('/addmanager',[HomeController::class,"addManager"]);

});

//ADD-EDIT-DELETE ROAD-MAPS
//j

Route::controller(RoadmapController::class)->group(function(){
    Route::post('/addRoadmap',"addRoadmap");
    Route::get('/Roadmaps',"show_roadmaps")->name('/Roadmaps');
    Route::get('/roadmap/edit/{id}',"edit_roadmap")->name('roadmap.edit');
    Route::get('/roadmap/delete/{id}',"delete_roadmap")->name('roadmap.delete');
    Route::PUT('/roadmaps/update/{id}',"update_roadmap")->name('roadmap.update');


});

//PROJECTS REQUESTS
Route::controller(ProjectsRequestsController::class)->group(function(){
    Route::get('/Projects_requests',"show_projects_requests")->name('/projects_requests');
    Route::get('/project_request/accept/{id}',"accept_project_request")->name('project_request.accept');
    Route::get('/project_request/delete/{id}',"delete_request")->name('project_request.delete');
    //Route::get('/project_request/reject/{id}',"reject_project_request")->name('project_request.reject');



});

//APPROVED PROJECTS
Route::controller(ApprovedProjectsController::class)->group(function(){
    Route::get('/Approved_projects',"show_accepted_requests")->name('/approved_projects');
    //Route::get('/Approved_projects',"show_approved_projects")->name('/approved_projectss');
    Route::post('/publish',"publish")->name('publish');
    //Route::get('/project_request/reject/{id}',"reject_project_request")->name('project_request.reject');

});


//RANK-INTERVIEWS
Route::controller(RankInterviewsController::class)->group(function(){
    Route::get('/Rank_interview',"rank_interview_requests")->name('/rank_interview');
    //Route::get('/Rank_interview2',"rank_interview")->name('/rank_interview/upgrade');
    Route::get('/rank_interview/accept/{id}',"accept_interview_request")->name('interview_request.accept');
    Route::PUT('/rank/update/{id}/{next_rank}',"upgrade_rank")->name('rank.upgrade');
    //Route::PUT('/rank.upgrade/{id}/{next_rank}');

    // then you'd use
    //route('rank.upgrade', ['id' => $id, 'next_rank' => $next_rank]);

});

//SUBMITTED-TASKS
Route::controller(SubmittedTasksController::class)->group(function(){
    Route::get('/submitted_web',"show_web")->name('/show_web');
    Route::get('/submitted_security',"show_security")->name('/show_security');
    Route::get('/submitted_design',"show_design")->name('/show_design');
    Route::PUT('/submitted_web/custom/{student_name}/{id}',"custom")->name('custom');
    Route::PUT('/submitted_web/full/{student_name}/{id}',"full")->name('full');


});

//TEAM-REQUESTS
Route::controller(TeamRequestsController::class)->group(function(){
    Route::get('/Team_requests',"team_requests")->name('/team_requests');
    Route::get('/Team_requests/accepted',"accepted_team_requests")->name('/team_requestss');
    Route::PUT('/team/accept/{id}',"accept_team")->name('accept.team');

});

//EDIT PROFILE
Route::controller(EditProfileController::class)->group(function(){
    //MANAGER PROFILE
    Route::get('/edit_profile/m/username/{id}',"manager_username")->name('/m_username.edit');
    Route::PUT('/edit_profile/m/password',"manager_password")->name('/m_password.edit');
    Route::PUT('/edit_profile/m/email',"manager_email")->name('/m_email.edit');
    Route::PUT('/m/username/update/{id}',"m_username_update")->name('m_username.update');
   //###################
});
