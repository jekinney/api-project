<?php
Route::prefix('site')->namespace('Site')->group( function() {
    Route::get('/menu/list/menu', 'MenuController@listmenu');
    Route::get('/page/show/{identifier}', 'PageController@show');
});

Route::prefix('auth')->group( function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
  
    Route::middleware('auth:api')->group( function() {
        Route::post('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

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

Route::prefix('dash')->middleware(['auth:api', 'permission:access-dash'])->group( function() {
    Route::prefix('role')->namespace('Acl')->middleware(['permission:acl-roles'])->group( function() {
        Route::get('/list/full', 'RoleController@full');
        Route::get('/list/select', 'RoleController@select');
        Route::get('/show/{identifier}', 'RoleController@show');
        Route::post('/store', 'RoleController@store');
        Route::patch('/update/{identifier}', 'RoleController@update');
        Route::delete('/destroy/{identifier}', 'RoleController@destroy');
    });

    Route::prefix('permission')->namespace('Acl')->middleware(['permission:acl-roles'])->group( function() {
        Route::get('/list/full', 'PermissionController@full');
        Route::get('/list/select', 'PermissionController@select');
    });

    Route::prefix('user')->middleware(['permission:site-menu,acl-users,all'])->group( function() {
        Route::get('/list/full', 'UserController@listFull');
        Route::get('/list/select', 'UserController@listselect');
        Route::get('/edit/{identifier}', 'UserController@edit');
        Route::post('/store', 'UserController@store');
        Route::patch('/update/{identifier}', 'UserController@update');
        Route::delete('/destroy/{identifier}', 'UserController@destroy');
    });

    Route::prefix('site/menu')->namespace('Site')->middleware(['permission:site-menu'])->group( function() {
        Route::get('/create', 'MenuController@create');
        Route::get('/list/full', 'MenuController@listFull');
        Route::get('/list/select', 'MenuController@listselect');
        Route::get('/show/{identifier}', 'MenuController@show');
        Route::post('/store', 'MenuController@store');
        Route::patch('/update/{identifier}', 'MenuController@update');
        Route::delete('/destroy/{identifier}', 'MenuController@destroy');
    });

    Route::prefix('site/page')->namespace('Site')->middleware(['permission:site-menu'])->group( function() {
        Route::get('/list/full', 'PageController@listFull');
        Route::get('/list/select', 'PageController@listselect');
        Route::get('/edit/{identifier}', 'PageController@edit');
        Route::post('/store', 'PageController@store');
        Route::patch('/update/{identifier}', 'PageController@update');
        Route::delete('/destroy/{identifier}', 'PageController@destroy');
    });
});
