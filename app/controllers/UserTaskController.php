<?php

use Nirland\TaskyLand\Models\Task;

class UserTaskController extends \BaseController {

    /**
     * Display a listing of the Task.
     *
     * @param int $user_id
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
    public function index($user_id) 
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
                
        $query = Task::with('project')
                ->where('user_id', $user_id)
                ->whereIn('status', $status)
                ->whereIn('kind', $kind)
                ->orderBy($sortField, $sortOrder)
                ->skip($limit * ($page - 1))->take($limit);
        
        $tasks = $this->getQueryResult($query, Config::get('querycache.task.list'), 'usertask.list');
                
        return $this->buildResponse($tasks);
    }

}
