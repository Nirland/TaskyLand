<?php

use Nirland\TaskyLand\Models\Task;
use Nirland\TaskyLand\Models\Project;

class TaskController extends \BaseController {

    /**
     * Control project member credintials for modification Task
     *
     */
    public function __construct() {
        $this->beforeFilter('member', array('only' => array('store', 'update', 'destroy')));
    }
    
    /**
     * Display a listing of the Task.
     *
     * @param int $project_id
     *  
     * @param int $_GET['page'] 
     * @param int $_GET['limit']      
     * @param string $_GET['sort_field'] 
     * @param string $_GET['sort_order'] 
     * @param string $_GET['kind']
     * @param string $_GET['status']
     * 
     * @return Response
     */
    public function index($project_id) 
    {
        $rules = array('page' => 'integer|min:1', 
                    'limit' => 'integer|min:1',                    
                    'sort_field' => 'in:created_at,date_end',
                    'sort_order' => 'in:ASC,DESC',
                    'kind' => 'in:NORMAL,IMPORTANT',
                    'status' => 'in:OPENED,CLOSED,DELAYED',
                );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->passes()){
            $page = Input::get('page', 1);
            $limit = Input::get('limit', 100);            
            $sortField = Input::get('sort_field', 'created_at');
            $sortOrder = Input::get('sort_order', 'DESC');
            $kind = !empty(Input::get('kind'))? 
                    array(Input::get('kind')): 
                    array(Task::KIND_IMPORTANT, Task::KIND_NORMAL);
            $status = !empty(Input::get('status'))? 
                    array(Input::get('status')): 
                    array(Task::STATUS_OPENED, Task::STATUS_CLOSED, Task::STATUS_DELAYED);
        } else {
            $page = 1;
            $limit = 100;           
            $sortField = 'created_at';
            $sortOrder = 'DESC';
            $kind = array(Task::KIND_IMPORTANT, Task::KIND_NORMAL);
            $status = array(Task::STATUS_OPENED, Task::STATUS_CLOSED, Task::STATUS_DELAYED);
        }
                
        $query = Task::with('creator')
                ->where('project_id', $project_id)
                ->whereIn('status', $status)
                ->whereIn('kind', $kind)
                ->orderBy($sortField, $sortOrder)
                ->skip($limit * ($page - 1))->take($limit);
        
        $tasks = $this->getQueryResult($query, Config::get('querycache.task.list'), 'task.list');
                
        return $this->buildResponse($tasks);
    }

    /**
     * Store a newly created resource in storage.
     * @param  int  $project_id
     * 
     * @return Response
     */
    public function store($project_id) {
        if (Task::validate()) {
            $task = new Task();
            $task->title = Input::get('title');
            $task->description = Input::get('description');
            $task->date_end = Input::get('date_end', null);                       
            $task->kind = Input::get('kind', Task::KIND_NORMAL);
            
            $creator = Auth::user();            
            $project = Project::find($project_id);
            
            if ($creator instanceof User && $project instanceof Project){
                $task->creator()->associate($creator);
                $task->project()->associate($project);
                $task->save();
            } else{
                $error = 'Not correct project or creator data';
            }            
        } else {
            $error = 'Not correct task data';
        }
        
         if (!isset($error) && isset($task)) {         
            return $this->buildResponse($task->id);
        } else {
            return $this->buildResponse(null, $error);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $project_id
     * @param  int  $task_id
     * 
     * @return Response
     */
    public function show($project_id, $task_id) {
        $query = Task::with('creator', 'project')
                ->where('id', $task_id);               
                
        $task = $this->getQueryResult($query, Config::get('querycache.task.id'), 'task.id');
                
        return $this->buildResponse($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $project_id
     * @param  int  $task_id
     * 
     * @return Response
     */
    public function update($project_id, $task_id) {
        if (Task::validate(null, false)) {
            $task = Task::find($task_id);
            
            $task->title = Input::get('title', $task->title);
            $task->description = Input::get('description', $task->description);
            $task->date_end = Input::get('date_end', $task->date_end);
            $task->kind = Input::get('kind', $task->kind);
            $task->status = Input::get('status', $task->status);
            
            $member = Auth::user();
            
            if ($project_id != $task->project->id && $member->projects->contains($task->project->id)){
                $project = Project::find($project_id);
                $task->project()->associate($project);
            } 
                                                
            $task->save();
            
        } else {
            $error = 'Not correct task data';
        }
        
         if (!isset($error) && isset($task)) {         
            return $this->buildResponse($task);
        } else {
            return $this->buildResponse(null, $error);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $project_id
     * @param  int  $task_id
     * 
     * @return Response
     */
    public function destroy($project_id, $task_id) {
        $affected = Task::destroy($task_id);
        return $this->buildResponse($affected);
    }

}
