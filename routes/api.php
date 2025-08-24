<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('todos', [TodoController::class, 'store']);
    Route::get('todos', [TodoController::class,'index']);
    Route::get('todos/{id}', [TodoController::class,'show']);
    Route::get('/todos', [TodoController::class, 'paginatedIndex']);
    Route::delete('todos/{id}',[TodoController::class,'destroy']);
    Route::put('todos/{id}', [TodoController::class,'update']);
});