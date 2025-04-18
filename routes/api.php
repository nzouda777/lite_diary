<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoryController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class,'signup'])->name('register');
Route::post('/login', [AuthController::class,'login'])->name('login');

Route::get('/stories/public', [StoryController::class,'publicStories'])->name('public_stories');


Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/stories', [StoryController::class,'index'])->name('index');
    Route::get('/stories/{id}', [StoryController::class,'show'])->name('show');
    Route::post('/stories', [StoryController::class,'store'])->name('store');
    Route::put('/stories/{id}', [StoryController::class,'update'])->name('update');
    Route::delete('/stories/{id}', [StoryController::class, 'destroy'])->name('destroy');
});