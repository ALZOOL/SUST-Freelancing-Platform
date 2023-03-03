<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ManagerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(StudentController::class)->group(function(){
    //Main action 
    Route::post('student/register','register_action')->name('student_register.process'); 
    Route::post('student/login_action','login_action')->name('student_login.process');
    Route::get('student/logout', 'logout')->name('student_logout');
    Route::get('student/notifications',  'notifications')->name('notifications');
    Route::put('student/changeinfo',  'changeinfo')->name('changeinfo');
    Route::get('student/calculateCategoryIndicators',  'calculateCategoryIndicators')->name('calculateCategoryIndicators');
    Route::get('student/roadmaps',  'roadmaps')->name('roadmaps');
    Route::get('student/get_team_members',  'get_team_members')->name('get_team_members');

    //rank:
    Route::get('student/rank', 'rank')->name('rank');
    Route::post('student/upgrade_rank',  'upgrade_rank')->name('upgrade_rank');
    Route::get('student/getStarsCountForStudent',  'getStarsCountForStudent')->name('getStarsCountForStudent');

    //Tasks 
    Route::get('student/tasks',  'tasks')->name('tasks');
    Route::get('student/lastes_tasks',  'lastes_tasks')->name('lastes_tasks'); 
    Route::post('student/submitTask',  'submitTask')->name('submitTask');
    Route::get('student/last_solved',  'last_solved')->name('last_solved');
   // view Route::get('student/submitTasks',  'submitTasks')->name('submitTasks');
    
    Route::get('student/test_url','test_fun')->name('test_route');
    
    //projects action :
    Route::post('student/student_project_request',  'student_project_request')->name('student_project_request');
    Route::post('student/team_project_request',  'team_project_request')->name('team_project_request');
    Route::get('student/client_project','client_project')->name('client_project');
    Route::get('student/projects',  'projects')->name('projects');
    
    ///Team actions :
    Route::post('student/create_team','create_team_action')->name('teams.create');
    Route::post('student/join_team',  'join_team_action')->name('teams.join');
    Route::put('student/edit_team_name',  'edit_team_name')->name('edit_team_name');
    Route::put('student/delete_team',  'delete_team')->name('delete_team');
    Route::put('student/delete_team_member',  'delete_team_member')->name('delete_team_member');
    
    
    
});

Route::middleware(['throttle:api'])->group(function () {
    Route::controller(ClientController::class)->group(function(){
    Route::get('client/login', 'login')->name('client_login');
    Route::post('client/login_action','login_action')->name('client_login.process');
    Route::get('client/logout', 'logout')->name('logout');


   // Route::get('client/home','home')->name('home');
    Route::get('client/register','register')->name('client_register');
    Route::post('client/register_action','register_action')->name('client_register.process'); 
    Route::get('client/logout', 'logout')->name('client_logout');
    Route::get('client/test_url','test_fun')->name('test_route');
    Route::get('client/client_project','client_project')->name('client_project');
    Route::get('client/client_project_requetses','client_project_requetses')->name('client_project_requetses');
    Route::post('client/client_project_requets','client_project_requets')->name('client_project.request');
    Route::get('client/getProjects','getProjects')->name('getProjects');

    Route::get('client/contacts_us', 'contacts_us')->name('contacts_us');
    Route::post('client/contact_us', 'contact_us')->name('contact_us.store');

    Route::get('client/getProjectsAndTeams','getProjectsAndTeams')->name('getProjectsAndTeams');
    Route::get('client/showstar', 'showstar')->name('showstar');
    Route::post('client/stars_store', 'stars')->name('stars.store');
    Route::get('client/notifications', 'notifications')->name('notifications');
    Route::put('client/changeinfo', 'changeinfo')->name('changeinfo');
    Route::post('client/checkstar', 'checkstar')->name('checkstar');
    
    
    

    
    });
});



Route::get('/token', function () {
    return csrf_token(); 
});


//*********************************** */
//Manager SYSTEM
Route::controller(ManagerController::class)->group(function(){
    //LOGIN-LOGOUT
    //manager
   // Route::get('manager/login', 'manager_login')->name('manager_login');
    Route::post('manager/login','manager_login_action')->name('manager_login.process');
    Route::get('manager/logout', 'manager_logout')->name('manager_logout');
    //admin
    //Route::get('admin/login', 'admin_login')->name('admin_login');
    Route::post('admin/login','admin_login_action')->name('admin_login.process');
    Route::get('admin/logout', 'admin_logout')->name('admin_logout');
    //teacher
    //Route::get('teacher/login', 'teacher_login')->name('teacher_login');
    //Route::post('teacher/login','teacher_login_action')->name('teacher_login.process');
    //Route::get('teacher/logout', 'teacher_logout')->name('teacher_logout');
    
    //add-edit-delete managers and teachers
    Route::post('admin/addmanager',"addManager");
    Route::get('admin/count_users',"count_users");
    Route::get('/admin/show_managers',"show_managers")->name('admin');
   // Route::get('/admin/system_managers/edit/{id}',"edit_system_managers")->name('system_managers.edit');
    Route::PUT('/admin/update',"update_system_managers")->name('system_managers.update');
    Route::post('/admin/delete',"delete_system_managers")->name('system_managers.delete');

    
    //PROJECTS REQUESTS
    Route::get('manager/Projects_requests',"show_projects_requests")->name('/projects_requests');
    Route::post('manager/project_request/accept',"accept_project_request")->name('project_request.accept');
    Route::post('manager/project_request/reject',"reject_project_request")->name('project_request.reject');
    //APPROVED PROJECTS
    Route::get('manager/Approved_projects',"show_accepted_requests")->name('/approved_projects');
    Route::get('manager/Clients_projects',"show_clients_projects")->name('/approved_projects');
    Route::post('manager/publish',"publish")->name('publish');
    Route::post('manager/publish/cancel',"cancel_publish")->name('publish.cancel');
    Route::PUT('manager/update_status',"update_status")->name('status.update');
    //ROAD-MAPS
    //ADD-EDIT-DELETE ROAD-MAPS
    Route::get('/Roadmaps',"show_roadmaps")->name('/Roadmaps');
    Route::post('/addRoadmap',"addRoadmap");
   // Route::get('/roadmap/edit',"edit_roadmap")->name('roadmap.edit');
    Route::post('/roadmaps/delete',"delete_roadmap")->name('roadmap.delete');
    Route::PUT('/roadmaps/update',"update_roadmap")->name('roadmap.update');

    //TASKS
    //ADD-EDIT-DELETE-TASKS
    Route::get('manager/Tasks',"show_tasks")->name('/Tasks');
    Route::post('manager/task/add',"add_task")->name('web.add');
    Route::get('manager/task/edit',"edit_task")->name('task.edit');
    Route::post('manager/task/delete',"delete_task")->name('task.delete');
    Route::PUT('manager/task/update',"update_task")->name('task.update');

    //SUBMITTED TASKS
    Route::get('manager/submitted_web',"show_web")->name('/show_web');
    Route::get('manager/submitted_security',"show_security")->name('/show_security');
    Route::get('manager/submitted_design',"show_design")->name('/show_design');
    Route::PUT('manager/custom_points',"custom")->name('custom');
    Route::PUT('manager/full_points',"full")->name('full');

     //mahdi stop here 

    //RANK-INTERVIEWS
    Route::get('manager/Rank_interview',"rank_interview_requests")->name('/rank_interview');
    //Route::get('/Rank_interview2',"rank_interview")->name('/rank_interview/upgrade');
    Route::post('manager/rank_interview/accept',"accept_interview_request")->name('interview_request.accept');
    Route::post('manager/rank_interview/reject',"reject_interview_request")->name('interview_request.reject');
    Route::PUT('manager/rank/update',"upgrade_rank")->name('rank.upgrade');
    Route::post('manager/rank/update/cancel',"cancel_rank_upgrading")->name('rank_upgrading.cancel');
    //Route::PUTmanager('/rank.upgrade/{id}/{next_rank}');
//38 routes until here
    //TEAM REQUESTS AND TEAMS JOINING
    Route::get('manager/Team_requests/accepte',"accepted_team_requests")->name('/team_requests');
    Route::get('manager/team_join_projects',"team_join_projects")->name('team_join_projects');
    //show requests to join projects
    Route::get('manager/join_project_requests',"join_project_requests")->name('/team_requests');
   
    Route::post('manager/add_student_to_project',"add_student_to_project")->name('add_student_to_project');
    Route::post('manager/student_to_project/reject',"reject_student_request_project");
    Route::post('manager/add_team_to_project',"add_team_to_project")->name('add_team_to_project');
    Route::post('manager/team_to_project/reject',"reject_team_request_project");
    Route::get('manager/show_contact_us',"show_contact_us")->name('/show_contact_us');
    
    
   //tst
    // Route::PUT('manager/team/accept_single/{id}',"accept_single_student")->name('accept_single.team');
    // Route::PUT('manager/team/accept_full/{id}',"accept_full_team")->name('accept_full.team');
    

});
//#####################
//*************************** */
// <<<<<<< HEAD
// Route::controller(TaskController::class)->group(function(){

//     Route::post('/web_task',"add_web_task");
//     Route::post('/security_task',"add_security_task");
//     Route::post('/desgin_task',"add_desgin_task");
//     Route::get('/Tasks',"show_tasks")->name('/Tasks');
//     Route::get('/task/edit/{id}',"edit_task")->name('task.edit');
//     Route::get('/task/delete/{id}',"delete_task")->name('task.delete');
//     Route::PUT('/task/update/{id}',"update_task")->name('task.update');
// //Route::guard('managers')->post('/addmanager',[HomeController::class,"addManager"]);
// });


// =======
// >>>>>>> da2e3b8fd9c8ea397fc4f7df9f070f8bfef2e619
?>