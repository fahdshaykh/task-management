<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [TaskController::class, "index"]);
Route::resource('tasks', TaskController::class);
Route::post('update-order', [TaskController::class, "updateOrder"])->name("update-order");
Route::post('tasks/update/{id}', [TaskController::class, "update"])->name("update-task");
Route::post('tasks/delete/{id}', [TaskController::class, "destroy"])->name("delete-task");
