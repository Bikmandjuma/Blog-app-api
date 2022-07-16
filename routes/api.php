<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;

// #['middleware'=>'api','prefix'=>'v1']

Route::controller(AuthController::class)->group(function () {
    Route::post('v1/login', 'login');
    Route::post('v1/register', 'register');
    Route::post('v1/logout', 'logout');
    Route::post('v1/refresh', 'refresh');
});

Route::controller(BlogController::class)->group(function () {
    Route::get('v1/Viewblog', 'index');
    Route::post('v1/Createblog', 'store');
    Route::get('v1/ShowSingleblog/{id}', 'show');
    Route::put('v1/Updateblog/{id}', 'update');
    Route::delete('v1/Deleteblog/{id}', 'destroy');
    Route::post('v1/follow/{id}', 'follow');
    Route::post('v1/unfollow/{id}', 'unfollow');
    Route::post('v1/likeblog/{id}', 'like');
});
