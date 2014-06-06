<?php

use Nirland\TaskyLand\Models\Progress;

class UserProgressController extends \BaseController {

    /**
     * Display a listing of the Progress.
     *
     * @param int $user_id     
     *  
     * @param int $_GET['page'] 
     * @param int $_GET['limit']      
     * @param string $_GET['sort_field'] 
     * @param string $_GET['sort_order']     
     * 
     * @return Response
     */
    public function index($user_id) {
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
                
        
        $query = Progress::with('task', 'task.project')
                ->where('user_id', $user_id)
                ->orderBy($sortField, $sortOrder)
                ->skip($limit * ($page - 1))->take($limit);
        
        $progress = $this->getQueryResult($query, Config::get('querycache.progress.list'), 'userprogress.list');
                
        return $this->buildResponse($progress);
    }

}
