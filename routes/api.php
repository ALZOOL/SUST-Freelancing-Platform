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
});


Route::get('/token', function () {
    return csrf_token(); 
});

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