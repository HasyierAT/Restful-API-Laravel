<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UserController::class)->group(function(){
    Route::post('login', 'loginUser' );
});

Route::controller(UserController::class)->group(function(){
    Route::get('user', 'getUserDetails');
    Route::get('logout', 'userLogout');
})->middleware('auth:api');

// Route::middleware('auth:api')->group(function(){
//     Route::resource('posts','PostController');
// });
Route::controller(PostController::class)->group(function(){
    Route::get('post', 'index');
    Route::post('post/create', 'store');
    Route::get('post/show/{id}', 'show');
    Route::put('post/update/{id}', 'update');
    Route::delete('post/delete/{id}', 'destroy');
})->middleware('auth:api');

Route::controller(CategoryController::class)->group(function(){
    Route::post('category/create', 'store');
})->middleware('auth:api');