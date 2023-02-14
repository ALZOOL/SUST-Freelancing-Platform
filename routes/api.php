<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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