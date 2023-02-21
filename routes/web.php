<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RoadmapController;
use App\Http\Controllers\TaskController;
//use App\Http\Controllers\ManagerDashboardMovementController;
use App\Http\Controllers\ProjectsRequestsController;
use App\Http\Controllers\ApprovedProjectsController;
use App\Http\Controllers\RankInterviewsController;
use App\Http\Controllers\SubmittedTasksController;
use App\Http\Controllers\TeamRequestsController;
use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ManagerController;

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


//###########


//MANAGER DASHBOARD MOVEMENT
Route::view('/Submitted_tasks', 'manager.submitted_tasks');
Route::view('/create_web_task', 'manager.task.web');
Route::view('/create_security_task', 'manager.task.security');
Route::view('/create_design_task', 'manager.task.design');
Route::view('/manager_profile', 'ManagerDashboard.profile.manager_profile');
Route::view('/admin', 'admin')->name('admin');



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

//ADD-EDIT-DELETE MANAGERS

Route::controller(AdminController::class)->group(function(){
    Route::post('/addmanager',"addManager");
    Route::get('/admin',"show_managers")->name('admin');
    Route::get('/admin/system_managers/edit/{id}',"edit_system_managers")->name('system_managers.edit');
    Route::PUT('/admin/system_managers/edit/update/{id}',"update_system_managers")->name('system_managers.update');
    Route::get('/admin/delete/{id}',"delete_system_managers")->name('system_managers.delete');
    


});


//ADD-EDIT-DELETE TASKS

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

// //ADD-EDIT-DELETE ROAD-MAPS

// Route::controller(RoadmapController::class)->group(function(){
//     Route::post('/addRoadmap',"addRoadmap");
//     Route::get('/Roadmaps',"show_roadmaps")->name('/Roadmaps');
//     Route::get('/roadmap/edit/{id}',"edit_roadmap")->name('roadmap.edit');
//     Route::get('/roadmap/delete/{id}',"delete_roadmap")->name('roadmap.delete');
//     Route::PUT('/roadmaps/update/{id}',"update_roadmap")->name('roadmap.update');


// });

//PROJECTS REQUESTS
Route::controller(ProjectsRequestsController::class)->group(function(){
   // Route::get('/Projects_requests',"show_projects_requests")->name('/projects_requests');
    Route::get('/project_request/accept/{id}',"accept_project_request")->name('project_request.accept');
    Route::get('/project_request/delete/{id}',"delete_request")->name('project_request.delete');
    //Route::get('/project_request/reject/{id}',"reject_project_request")->name('project_request.reject');



});

//APPROVED PROJECTS
Route::controller(ApprovedProjectsController::class)->group(function(){
    //Route::get('/Approved_projects',"show_accepted_requests")->name('/approved_projects');
    //Route::get('/Approved_projects',"show_approved_projects")->name('/approved_projectss');
    //Route::post('/publish',"publish")->name('publish');
    //Route::get('/project_request/reject/{id}',"reject_project_request")->name('project_request.reject');

});


// //RANK-INTERVIEWS
// Route::controller(RankInterviewsController::class)->group(function(){
//     Route::get('/Rank_interview',"rank_interview_requests")->name('/rank_interview');
//     //Route::get('/Rank_interview2',"rank_interview")->name('/rank_interview/upgrade');
//     Route::get('/rank_interview/accept/{id}',"accept_interview_request")->name('interview_request.accept');
//     Route::PUT('/rank/update/{id}/{next_rank}',"upgrade_rank")->name('rank.upgrade');
//     //Route::PUT('/rank.upgrade/{id}/{next_rank}');

//     // then you'd use
//     //route('rank.upgrade', ['id' => $id, 'next_rank' => $next_rank]);

// });

//SUBMITTED-TASKS
// Route::controller(SubmittedTasksController::class)->group(function(){
//     Route::get('/submitted_web',"show_web")->name('/show_web');
//     Route::get('/submitted_security',"show_security")->name('/show_security');
//     Route::get('/submitted_design',"show_design")->name('/show_design');
//     Route::PUT('/submitted_web/custom/{student_name}/{id}',"custom")->name('custom');
//     Route::PUT('/submitted_web/full/{student_name}/{id}',"full")->name('full');


// });

//TEAM-REQUESTS
// Route::controller(TeamRequestsController::class)->group(function(){
//     Route::get('/Team_requests',"team_requests")->name('/team_requests');
//     Route::get('/Team_requests/accepted',"accepted_team_requests")->name('/team_requestss');
//     Route::PUT('/team/accept/{id}',"accept_team")->name('accept.team');

// });
//######################
//********************* */

//************************* */
//EDIT PROFILE
Route::controller(EditProfileController::class)->group(function(){
    //MANAGER PROFILE
    Route::get('/edit_profile/m/username/{id}',"manager_username")->name('/m_username.edit');
    Route::PUT('/edit_profile/m/password',"manager_password")->name('/m_password.edit');
    Route::PUT('/edit_profile/m/email',"manager_email")->name('/m_email.edit');
    Route::PUT('/m/username/update/{id}',"m_username_update")->name('m_username.update');
   
});
//###################
//************************ */


//CLIENTS LOGIN-REGISTERATION-SYSTEM
Route::controller(ClientController::class)->group(function(){
    Route::get('client/login', 'login')->name('client_login');
    Route::post('client/login','login_action')->name('client_login.process');
    Route::get('client/logout', 'logout')->name('logout');


   // Route::get('client/home','home')->name('home');
    Route::get('client/register','register')->name('client_register');
    Route::post('client/register','register_action')->name('client_register.process'); 
    Route::get('client/logout', 'logout')->name('client_logout');
    Route::get('client/test_url','test_fun')->name('test_route');
    Route::get('client/client_project','client_project')->name('client_project');
    Route::get('client/client_project_requetses','client_project_requetses')->name('client_project_requetses');
    Route::post('client/client_project_requets','client_project_requets')->name('client_project.request');
    Route::get('client/getProjects','getProjects')->name('getProjects');

    Route::get('client/contacts_us', 'contacts_us')->name('contacts_us');
    Route::post('client/contact_us', 'contact_us')->name('contact_us.store');
   
});
//###################
//********************** */

//****************************** */
//STUDENTS LOGIN-REGISTERATION-SYSTEM
Route::controller(StudentController::class)->group(function(){
    Route::get('student/login', 'login')->name('student_login');
    Route::post('student/login','login_action')->name('student_login.process');
    Route::get('student/logout', 'logout')->name('student_logout');
    Route::get('student/rank', 'rank')->name('rank');
    Route::get('student/roadmaps',  'roadmaps')->name('roadmaps');
    
    // Route::get('student/home','home')->name('home');
     Route::get('student/register','register')->name('student.register');
     Route::post('student/register','register_action')->name('student_register.process'); 
     Route::get('student/test_url','test_fun')->name('test_route');
     Route::get('student/client_project','client_project')->name('client_project');
    //Create Team :
     Route::get('student/create_team','create_team')->name('create_team');
     Route::post('student/create_team_action','create_team_action')->name('teams.create');
     Route::get('student/join_team',  'join_team')->name('join_team');
     Route::post('student/join_team_action',  'join_team_action')->name('teams.join');
     Route::post('student/upgrade_rank',  'upgrade_rank')->name('upgrade_rank');

     
    //Route::get('student/tasks',  'tasks')->name('tasks');
    
    //SHOW CLIENTS PROJECTS TO STUDENTS
    Route::get('student/projects',  'projects')->name('projects');
    
    //STUDENT JOINING CLIENTS PROJECTS AS TEAM OR SINGLE STUDENTS
    Route::post('student/student_project_request',  'student_project_request')->name('student_project_request');
    Route::post('student/team_project_request',  'team_project_request')->name('team_project_request');
    
    Route::get('student/calculateCategoryIndicators',  'calculateCategoryIndicators')->name('calculateCategoryIndicators');
    Route::get('student/submitTasks',  'submitTasks')->name('submitTasks');
    Route::post('student/submitTask',  'submitTask')->name('submitTask');
    Route::get('student/last_solved',  'last_solved')->name('last_solved');  
});
//#######################
//******************** */

//*********************************** */
//Manager SYSTEM
Route::controller(ManagerController::class)->group(function(){
    //LOGIN-LOGOUT
    //manager
    Route::get('manager/login', 'manager_login')->name('manager_login');
    Route::post('manager/home','manager_login_action')->name('manager_login.process');
    Route::get('manager/logout', 'manager_logout')->name('manager_logout');
    //admin
    Route::get('admin/login', 'admin_login')->name('admin_login');
    Route::post('admin/home','admin_login_action')->name('admin_login.process');
    Route::get('admin/logout', 'admin_logout')->name('admin_logout');
    //teacher
    Route::get('teacher/login', 'teacher_login')->name('teacher_login');
    Route::post('teacher/home','teacher_login_action')->name('teacher_login.process');
    Route::get('teacher/logout', 'teacher_logout')->name('teacher_logout');
    
    //add-edit-delete managers and teachers
    Route::post('/addmanager',"addManager");
    //Route::get('/admin',"show_managers")->name('admin');
    Route::get('/admin/system_managers/edit/{id}',"edit_system_managers")->name('system_managers.edit');
    Route::PUT('/admin/system_managers/edit/update/{id}',"update_system_managers")->name('system_managers.update');
    Route::get('/admin/delete/{id}',"delete_system_managers")->name('system_managers.delete');

    
    //PROJECTS REQUESTS
    Route::get('/Projects_requests',"show_projects_requests")->name('/projects_requests');
    Route::get('/project_request/accept/{id}',"accept_project_request")->name('project_request.accept');
    Route::get('/project_request/reject/{id}',"reject_project_request")->name('project_request.reject');
    //APPROVED PROJECTS
    Route::get('/Approved_projects',"show_accepted_requests")->name('/approved_projects');
    Route::post('/publish',"publish")->name('publish');
    Route::get('/publish/cancel/{id}/{email}',"cancel_publish")->name('publish.cancel');
    Route::PUT('/update_status/{project_id}',"update_status")->name('status.update');
    //ROAD-MAPS
    //ADD-EDIT-DELETE ROAD-MAPS
    Route::get('/Roadmaps',"show_roadmaps")->name('/Roadmaps');
    Route::post('/addRoadmap',"addRoadmap");
    Route::get('/roadmap/edit/{id}',"edit_roadmap")->name('roadmap.edit');
    Route::get('/roadmap/delete/{id}',"delete_roadmap")->name('roadmap.delete');
    Route::PUT('/roadmaps/update/{id}',"update_roadmap")->name('roadmap.update');

    //TASKS
    //ADD-EDIT-DELETE-TASKS
    Route::get('/Tasks',"show_tasks")->name('/Tasks');
    Route::post('/web_task',"add_web_task")->name('web.add');
    Route::post('/security_task',"add_security_task")->name('security.add');
    Route::post('/desgin_task',"add_desgin_task")->name('design.add');
    Route::get('/task/edit/{id}',"edit_task")->name('task.edit');
    Route::get('/task/delete/{id}',"delete_task")->name('task.delete');
    Route::PUT('/task/update/{id}',"update_task")->name('task.update');

    //SUBMITTED TASKS
    Route::get('/submitted_web',"show_web")->name('/show_web');
    Route::get('/submitted_security',"show_security")->name('/show_security');
    Route::get('/submitted_design',"show_design")->name('/show_design');
    Route::PUT('/submitted_web/custom/{student_name}/{id}',"custom")->name('custom');
    Route::PUT('/submitted_web/full/{student_name}/{id}',"full")->name('full');

    //RANK-INTERVIEWS
    Route::get('/Rank_interview',"rank_interview_requests")->name('/rank_interview');
    //Route::get('/Rank_interview2',"rank_interview")->name('/rank_interview/upgrade');
    Route::get('/rank_interview/accept/{id}',"accept_interview_request")->name('interview_request.accept');
    Route::get('/rank_interview/reject/{id}',"reject_interview_request")->name('interview_request.reject');
    Route::PUT('/rank/update/{id}/{next_rank}',"upgrade_rank")->name('rank.upgrade');
    Route::get('/rank/update/cancel/{id}',"cancel_rank_upgrading")->name('rank_upgrading.cancel');
    //Route::PUT('/rank.upgrade/{id}/{next_rank}');

    //TEAM REQUESTS AND TEAMS JOINING
    Route::get('/Team_requests',"team_requests")->name('/team_requests');
    Route::get('/Team_requests/accepted',"accepted_team_requests")->name('/team_requests');

    Route::post('/add_student_to_project',"add_student_to_project")->name('add_student_to_project');
    
    Route::get('/team_join_projects',"team_join_projects")->name('team_join_projects');
    Route::post('/add_team_to_project',"add_team_to_project")->name('add_team_to_project');
    
    
   
    Route::PUT('/team/accept_single/{id}',"accept_single_student")->name('accept_single.team');
    Route::PUT('/team/accept_full/{id}',"accept_full_team")->name('accept_full.team');
    

});
//#####################
//*************************** */