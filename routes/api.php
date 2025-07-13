<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogCategoryController;
use App\Http\Controllers\API\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register' , [AuthController::class , "register"])->name("register");
Route::post("/login" , [AuthController::class , 'login'])->name('login');
Route::apiResource('/students' , StudentController::class);

Route::group(["middleware" => "auth:sanctum"] , function () {
    Route::get('/profile' , [AuthController::Class , "profile"])->name('profile');
    Route::get("/logout" , [AuthController::class , "logout"])->name("logout");

    Route::apiResource('/categories' , BlogCategoryController::class);
});

Route::get('/categories' , [BlogCategoryController::class , 'index']);