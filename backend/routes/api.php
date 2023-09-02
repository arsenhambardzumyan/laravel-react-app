<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:api', 'check.blocked'])->group(function () {
  Route::get('/user', 'App\Http\Controllers\UserController@getUser');

 // Update authenticated user's password
  Route::put('/user/password', 'App\Http\Controllers\UserController@updatePassword');

  Route::get('/users', 'App\Http\Controllers\UserController@index')->middleware('can:view,App\Models\User');

  // Apply the 'create' policy to a route for creating a user
  Route::post('/users', 'App\Http\Controllers\UserController@store')->middleware('can:create,App\Models\User');

  // Apply the 'update' policy to a route for updating a user
  Route::put('/users/{user}', 'App\Http\Controllers\UserController@update')->middleware('can:update,user');

  // Apply the 'viewSelf' policy to a route for viewing their own information
  Route::get('/users/{user}', 'App\Http\Controllers\UserController@show')->middleware('can:viewSelf,user');

  // Apply the 'changePassword' policy to a route for changing their own password
  Route::put('/users/{user}/password', 'App\Http\Controllers\UserController@changePassword')->middleware('can:changePassword,user');
});
