<?php

use Nirland\TaskyLand\Models\Progress;
use Nirland\TaskyLand\Models\Task;

class ProgressController extends \BaseController {

    /**
     * Control project member credintials for modification Progress
     *
     */
    public function __construct() {
        $this->beforeFilter('member', array('only' => array('store', 'update', 'destroy')));
    }
    
    /**
     * Display a listing of the Progress.
     *
     * @param int $project_id
     * @param int $task_id
     *  
     * @param int $_GET['page'] 
     * @param int $_GET['limit']      
     * @param string $_GET['sort_field'] 
     * @param string $_GET['sort_order']     
     * 
     * @return Response
     */
    public function index($project_id, $task_id) {
        $rules = array('page' => 'integer|min:1', 
                    'limit' => 'integer|min:1',                    
                    'sort_field' => 'in:created_at, title',
                    'sort_order' => 'in:ASC,DESC',                    
                );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->passes()){
            $page = Input::get('page', 1);
            $limit = Input::get('limit', 100);            
            $sortField = Input::get('sort_field', 'created_at');
            $sortOrder = Input::get('sort_order', 'DESC');            
        } else {
            $page = 1;
            $limit = 100;           
            $sortField = 'created_at';
            $sortOrder = 'DESC';            
        }
                
        $query = Progress::with('worker')
                ->where('task_id', $task_id)
                ->orderBy($sortField, $sortOrder)
                ->skip($limit * ($page - 1))->take($limit);
        
        $progress = $this->getQueryResult($query, Config::get('querycache.progress.list'), 'progress.list');
                
        return $this->buildResponse($progress);
    }

    /**
     * Store a newly created Progress in storage.
     *
     * @param int $project_id
     * @param int $task_id
     * 
     * @return Response
     */
    public function store($project_id, $task_id) {
        if (Progress::validate()) {
            $progress = new Progress();
            $progress->title = Input::get('title');
            $progress->message = Input::get('message');
            $progress->hours = Input::get('hours', 0);
            $progress->minutes = Input::get('minutes', 0);
            $progress->revision = Input::get('revision', null);
            
            $worker = Auth::user();            
            $task = Task::find($task_id);
            
            if ($worker instanceof User && $task instanceof Task){
                $progress->worker()->associate($worker);
                $progress->task()->associate($task);
                $progress->save();
            } else{
                $error = 'Not correct task or worker data';
            }            
        } else {
            $error = 'Not correct progress data';
        }
        
         if (!isset($error) && isset($progress)) {         
            return $this->buildResponse($progress->id);
        } else {
            return $this->buildResponse(null, $error);
        }
    }

    /**
     * Display the specified Progress.
     *
     * @param int $project_id
     * @param int $task_id
     * 
     * @param  int  $id
     * @return Response
     */
    public function show($project_id, $task_id, $id) {
        $query = Progress::with('worker', 'task')
                ->where('id', $id);               
                
        $progress = $this->getQueryResult($query, Config::get('querycache.progress.id'), 'progress.id');
                
        return $this->buildResponse($progress);
    }

    /**
     * Update the specified Progress in storage.
     *
     * @param int $project_id
     * @param int $task_id
     * 
     * @param  int  $id
     * @return Response
     */
    public function update($project_id, $task_id, $id) {
        if (Progress::validate(null, false)) {
            $progress = Progress::find($id);
            $progress->title = Input::get('title', $progress->title);
            $progress->message = Input::get('message', $progress->message);
            $progress->hours = Input::get('hours', $progress->hours);
            $progress->minutes = Input::get('minutes', $progress->minutes);
            $progress->revision = Input::get('revision', $progress->revision);
            
            $worker = Auth::user();            
            
            if ($worker instanceof User && ($progress->worker->id == $worker->id)){
                $progress->save();
            } else{
                $error = 'Not correct task data or invalid credentials';
            }            
        } else {
            $error = 'Not correct progress data';
        }
        
         if (!isset($error) && isset($progress)) {         
            return $this->buildResponse($progress);
        } else {
            return $this->buildResponse(null, $error);
        }
    }

    /**
     * Remove the specified Progress from storage.
     *
     * @param int $project_id
     * @param int $task_id
     * 
     * @param  int  $id
     * @return Response
     */
    public function destroy($project_id, $task_id, $id) {
        $affected = Progress::destroy($id);
        return $this->buildResponse($affected);
    }

}
