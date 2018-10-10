<?php
Route::prefix('blog')->namespace('Blog')->group( function () {

    Route::prefix('category')->group( function() {
        Route::get('/list/full', 'CategoryController@full')->middleware(['auth:api', 'permission:blog-categories,blog-articles']);
        Route::get('/list/menu', 'CategoryController@menu');
        Route::get('/list/select', 'CategoryController@select')->middleware(['auth:api', 'permission:blog-author,blog-articles']);
        Route::get('/list/detailed', 'CategoryController@detailed');
        Route::get('/show/{identifier}', 'CategoryController@show');
        Route::post('/store', 'CategoryController@store')->middleware('auth:api')->middleware(['auth:api', 'permission:blog-categories,blog-articles']);
        Route::patch('/update/{identifier}', 'CategoryController@update')->middleware(['auth:api', 'permission:blog-categories,blog-articles']);
        Route::delete('/destroy/{identifier}', 'CategoryController@destroy')->middleware(['auth:api', 'permission:blog-categories,blog-articles']);
    });

    Route::prefix('article')->group( function() {
        Route::get('/list/top', 'ArticleController@top');
        Route::get('/list/full', 'ArticleController@full')->middleware(['auth:api', 'permission:blog-authors,blog-articles']);
        Route::get('/list/paginated', 'ArticleController@paginated');
        Route::get('/show/{identifier}', 'ArticleController@show');
        Route::post('/store', 'ArticleController@store')->middleware(['auth:api', 'permission:blog-authors,blog-articles']);
        Route::patch('/update/{identifier}', 'ArticleController@update')->middleware(['auth:api', 'permission:blog-author,blog-articles']);
        Route::delete('/destroy/{identifier}', 'ArticleController@destroy')->middleware(['auth:api', 'permission:blog-author,blog-articles']);
    });

});

Route::prefix('auth')->group( function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
  
    Route::middleware('auth:api')->group( function() {
        Route::post('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });

    Route::prefix('page')->group( function() {
    	Route::get('/', 'PageController@index');
    	Route::get('/{page}', 'PageController@show');
    	Route::get('/create', 'PageController@create');
    	Route::put('/update/{post}', 'PageController@update');
    	Route::post('/store', 'PageController@store');
    	Route::delete('/destroy/{post}', 'PageController@destroy');
    });

    Route::prefix('role')->middleware('auth:api')->group( function() {
        Route::get('/list/full', 'RoleController@full');
        Route::get('/list/select', 'RoleController@select');
        Route::get('/show/{identifier}', 'RoleController@show');
        Route::post('/store', 'RoleController@store');
        Route::patch('/update/{identifier}', 'RoleController@update');
        Route::delete('/destroy/{identifier}', 'RoleController@destroy');
    });

    Route::prefix('permission')->middleware('auth:api')->group( function() {
        Route::get('/list/full', 'PermissionController@full');
        Route::get('/list/select', 'PermissionController@select');
    });
});
