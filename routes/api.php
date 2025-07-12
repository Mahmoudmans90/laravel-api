<?php

use App\Http\Controllers\API\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::get('/test' , function () {
   return response()->json([
    "data" => "data"
   ]); 
});


Route::apiResource('/students' , StudentController::class);