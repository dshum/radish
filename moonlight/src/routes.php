<?php

use \Illuminate\Session\Middleware\StartSession;
use Moonlight\Middleware\GuestMiddleware;
use Moonlight\Middleware\AuthMiddleware;

Route::group(array('prefix' => 'moonlight/touch'), function() {
    
    Route::group(['middleware' => [StartSession::class, GuestMiddleware::class]], function () {
        Route::get('/login', ['as' => 'login', 'uses' => 'Moonlight\Controllers\LoginController@show']);
        
        Route::post('/login', ['as' => 'login', 'uses' => 'Moonlight\Controllers\LoginController@login']);
    });
    
    Route::group(['middleware' => [
        StartSession::class, 
        AuthMiddleware::class]
    ], function () {
        Route::get('/', ['as' => 'home', 'uses' => 'Moonlight\Controllers\HomeController@show']);

        Route::get('/logout', ['as' => 'logout', 'uses' => 'Moonlight\Controllers\LoginController@logout']);
       
        Route::get('/profile', ['as' => 'profile', 'uses' => 'Moonlight\Controllers\ProfileController@show']);
       
        Route::post('/profile', ['as' => 'profile', 'uses' => 'Moonlight\Controllers\ProfileController@save']);
        
        Route::get('/users', ['as' => 'users', 'uses' => 'Moonlight\Controllers\UsersController@show']);
        
        Route::get('/groups', ['as' => 'groups', 'uses' => 'Moonlight\Controllers\UsersController@show']);
        
        Route::get('/users/create', ['as' => 'user.create', 'uses' => 'Moonlight\Controllers\UserController@create']);
        
        Route::post('/users/create', ['as' => 'user.add', 'uses' => 'Moonlight\Controllers\UserController@add']);
        
        Route::get('/users/{id}', ['as' => 'user', 'uses' => 'Moonlight\Controllers\UserController@edit'])->
            where(['id' => '[0-9]+']);
        
        Route::post('/users/{id}', ['as' => 'user.save', 'uses' => 'Moonlight\Controllers\UserController@save'])->
            where(['id' => '[0-9]+']);
        
        Route::post('/users/delete/{id}', ['as' => 'user.delete', 'uses' => 'Moonlight\Controllers\UserController@delete'])->
            where(['id' => '[0-9]+']);
        
        Route::get('/groups/create', ['as' => 'group.create', 'uses' => 'Moonlight\Controllers\GroupController@create']);
        
        Route::post('/groups/create', ['as' => 'group.add', 'uses' => 'Moonlight\Controllers\GroupController@add']);
        
        Route::get('/groups/{id}', ['as' => 'group', 'uses' => 'Moonlight\Controllers\GroupController@edit'])->
            where(['id' => '[0-9]+']);
        
        Route::post('/groups/{id}', ['as' => 'group.save', 'uses' => 'Moonlight\Controllers\GroupController@save'])->
            where(['id' => '[0-9]+']);
        
        Route::post('/groups/delete/{id}', ['as' => 'group.delete', 'uses' => 'Moonlight\Controllers\GroupController@delete']);
        
        Route::get('/search', ['as' => 'search', 'uses' => 'Moonlight\Controllers\SearchController@show']);
        
        Route::get('/browse', ['as' => 'browse', 'uses' => 'Moonlight\Controllers\BrowseController@show']);
        
        Route::get('/trash', ['as' => 'trash', 'uses' => 'Moonlight\Controllers\TrashController@show']);
        
        Route::get('/log', ['as' => 'log', 'uses' => 'Moonlight\Controllers\LogController@show']);
        
        Route::get('/log/search', ['as' => 'log.search', 'uses' => 'Moonlight\Controllers\LogController@search']);
    });
});
