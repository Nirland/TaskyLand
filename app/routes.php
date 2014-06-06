<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/**
 * Single page application endpoint
 * 
 */
Route::get('/', function()
{
    return View::make('index');
});

/**
 * API route group with stateless basic auth
 * 
 */
Route::group(array('prefix' => 'api', 'before' => 'basic.once'), function()
{
    Route::resource('user', 'UserController', array('except' => array('create', 'edit')));
    
    Route::resource('user.project', 'UserProjectController', array('only' => array('index')));
    Route::resource('user.task', 'UserTaskController', array('only' => array('index')));
    Route::resource('user.progress', 'UserProgressController', array('only' => array('index')));
    
    Route::resource('project', 'ProjectController', array('except' => array('create', 'edit')));
    Route::resource('project.task', 'TaskController', array('except' => array('create', 'edit')));
    Route::resource('project.task.progress', 'ProgressController', array('except' => array('create', 'edit')));
       
    Route::get('/auth', function(){       
        return Response::json(Auth::user(), 200);       
    });
    
    Route::get('/task/{id}', function($id){
        return App::make('TaskController')->show(null, $id);
    });
    
    Route::get('/progress/{id}', function($id){
        return App::make('ProgressController')->show(null, null, $id);
    });
});

/**
 * Reports route group with basic auth
 * 
 */
Route::group(array('prefix' => 'report', 'before' => 'auth.basic'), function()
{           
    Route::get('/user/{id}/{project_id?}', 'ReportController@userReport');
    Route::get('/project/{id}', 'ReportController@projectReport');            
});

/**
 * Handle invalid requests
 * 
 */
App::missing(function($exception)
{
    return View::make('index');
});
