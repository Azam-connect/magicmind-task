<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [TaskController::class,'index']);

Route::post('store',[TaskController::class,'store'])->name('store.data');
Route::get('search/{searchText?}',[TaskController::class,'search']);
Route::get('edit/{id}',[TaskController::class,'show']);
Route::get('delete/{id}',[TaskController::class,'destroy']);
