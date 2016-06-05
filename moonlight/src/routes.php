<?php

use \Illuminate\Session\Middleware\StartSession;
use Moonlight\Middleware\GuestMiddleware;
use Moonlight\Middleware\AuthMiddleware;
use Moonlight\Middleware\MobileMiddleware;

Route::group(['prefix' => 'moonlight'], function() {    
    Route::group(['middleware' => [MobileMiddleware::class]], function () {
        Route::get('/', function() {
            return 'Full version';
        });
    });
});

Route::group(['prefix' => 'moonlight/touch'], function() {
    
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
        
        Route::get('/search', ['as' => 'search', 'uses' => 'Moonlight\Controllers\SearchController@index']);
        
        Route::get('/search/list', ['as' => 'search.list', 'uses' => 'Moonlight\Controllers\SearchController@elements']);
        
        Route::get('/search/{item}', ['as' => 'search.item', 'uses' => 'Moonlight\Controllers\SearchController@item'])->
            where(['item' => '[A-Za-z0-9\.]+']);
        
        Route::get('/browse', ['as' => 'browse', 'uses' => 'Moonlight\Controllers\BrowseController@show']);
        
        Route::get('/trash', ['as' => 'trash', 'uses' => 'Moonlight\Controllers\TrashController@show']);
        
        Route::get('/log', ['as' => 'log', 'uses' => 'Moonlight\Controllers\LogController@show']);
        
        Route::get('/log/search', ['as' => 'log.search', 'uses' => 'Moonlight\Controllers\LogController@search']);
        
        Route::get('/elements/count', ['as' => 'elements.count', 'uses' => 'Moonlight\Controllers\BrowseController@count']);
        
        Route::get('/elements/list', ['as' => 'elements.list', 'uses' => 'Moonlight\Controllers\BrowseController@elements']);
        
        Route::get('/elements/autocomplete', ['as' => 'elements.autocomplete', 'uses' => 'Moonlight\Controllers\BrowseController@autocomplete']);
        
        Route::get('/elements/favorites', ['as' => 'home.favorites', 'uses' => 'Moonlight\Controllers\HomeController@favorites']);
        
        Route::post('/elements/favorite', ['as' => 'home.favorite', 'uses' => 'Moonlight\Controllers\HomeController@favorite']);
        
        Route::get('/browse', ['as' => 'browse', 'uses' => 'Moonlight\Controllers\BrowseController@root']);
        
        Route::get('/browse/root', ['as' => 'browse.root', 'uses' => 'Moonlight\Controllers\BrowseController@root']);
        
        Route::get('/browse/{classId}', ['as' => 'browse.element', 'uses' => 'Moonlight\Controllers\BrowseController@element'])->
            where(['classId' => '[A-Za-z0-9\.]+']);
        
        Route::get('/browse/{classId}/edit', ['as' => 'element.edit', 'uses' => 'Moonlight\Controllers\EditController@edit'])->
            where(['classId' => '[A-Za-z0-9\.]+']);
        
        Route::post('/browse/{classId}/save', ['as' => 'element.save', 'uses' => 'Moonlight\Controllers\EditController@save'])->
            where(['classId' => '[A-Za-z0-9\.]+']);
    });
});
