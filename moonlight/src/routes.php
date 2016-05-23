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
        
        Route::post('/users/{id}/delete', ['as' => 'user.delete', 'uses' => 'Moonlight\Controllers\UserController@delete'])->
            where(['id' => '[0-9]+']);
        
        Route::get('/groups/create', ['as' => 'group.create', 'uses' => 'Moonlight\Controllers\GroupController@create']);
        
        Route::post('/groups/create', ['as' => 'group.add', 'uses' => 'Moonlight\Controllers\GroupController@add']);
        
        Route::get('/groups/{id}', ['as' => 'group', 'uses' => 'Moonlight\Controllers\GroupController@edit'])->
            where(['id' => '[0-9]+']);
        
        Route::post('/groups/{id}', ['as' => 'group.save', 'uses' => 'Moonlight\Controllers\GroupController@save'])->
            where(['id' => '[0-9]+']);
        
        Route::post('/groups/{id}/delete', ['as' => 'group.delete', 'uses' => 'Moonlight\Controllers\GroupController@delete'])->
            where(['id' => '[0-9]+']);
        
        Route::get('/groups/{id}/items', ['as' => 'group.items', 'uses' => 'Moonlight\Controllers\GroupController@items'])->
            where(['id' => '[0-9]+']);
        
        Route::post('/groups/{id}/items', ['as' => 'group.items.save', 'uses' => 'Moonlight\Controllers\GroupController@saveItems'])->
            where(['id' => '[0-9]+']);
        
        Route::get('/groups/{id}/{item}/elements', ['as' => 'group.elements', 'uses' => 'Moonlight\Controllers\GroupController@elements'])->
            where(['id' => '[0-9]+', 'item' => '[A-Za-z0-9\.]+']);
        
        Route::post('/groups/{id}/{item}/elements', ['as' => 'group.elements.save', 'uses' => 'Moonlight\Controllers\GroupController@saveElements'])->
            where(['id' => '[0-9]+', 'item' => '[A-Za-z0-9\.]+']);
        
        Route::get('/search', ['as' => 'search', 'uses' => 'Moonlight\Controllers\SearchController@show']);
        
        Route::get('/search/{item}', ['as' => 'search.item', 'uses' => 'Moonlight\Controllers\SearchController@item'])->
            where(['item' => '[A-Za-z0-9\.]+']);
        
        Route::get('/browse', ['as' => 'browse', 'uses' => 'Moonlight\Controllers\BrowseController@show']);
        
        Route::get('/trash', ['as' => 'trash', 'uses' => 'Moonlight\Controllers\TrashController@show']);
        
        Route::get('/log', ['as' => 'log', 'uses' => 'Moonlight\Controllers\LogController@show']);
        
        Route::get('/log/search', ['as' => 'log.search', 'uses' => 'Moonlight\Controllers\LogController@search']);
        
        Route::get('/browse', ['as' => 'browse', 'uses' => 'Moonlight\Controllers\BrowseController@root']);
        
        Route::get('/browse/root', ['as' => 'browse.root', 'uses' => 'Moonlight\Controllers\BrowseController@root']);
        
        Route::get('/browse/root/{item}', ['as' => 'browse.list', 'uses' => 'Moonlight\Controllers\BrowseController@rootList'])->
            where(['item' => '[A-Za-z0-9\.]+']);
        
        Route::get('/browse/{classId}', ['as' => 'element', 'uses' => 'Moonlight\Controllers\BrowseController@element'])->
            where(['classId' => '[A-Za-z0-9\.]+']);
        
        Route::get('/browse/{classId}/{item}', ['as' => 'element.list', 'uses' => 'Moonlight\Controllers\BrowseController@elementList'])->
            where(['classId' => '[A-Za-z0-9\.]+', 'item' => '[A-Za-z0-9\.]+']);
        
        Route::get('/elements/count', ['as' => 'elements.count', 'uses' => 'Moonlight\Controllers\BrowseController@count']);
        
        Route::get('/elements/list', ['as' => 'elements.list', 'uses' => 'Moonlight\Controllers\BrowseController@elements']);
        
        Route::get('/browse/{classId}/edit', ['as' => 'element.edit', 'uses' => 'Moonlight\Controllers\BrowseController@edit'])->
            where(['classId' => '[A-Za-z0-9\.]+']);
    });
});
