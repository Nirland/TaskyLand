<?php

use Nirland\TaskyLand\Models\Project;

class UserProjectController extends \BaseController {

    /**
     * Display a listing of the project.
     * 
     * @param int $user_id
     * 
     * @param int $_GET['page'] 
     * @param int $_GET['limit'] 
     * @param int $_GET['active'] 
     * @param string $_GET['sort_field'] 
     * @param string $_GET['sort_order'] 
     * 
     * @return Response
     */
    public function index($user_id) {
        $rules = array('page' => 'integer|min:1',
            'limit' => 'integer|min:1',
            'active' => 'integer|between:0,2',
            'sort_field' => 'in:name,created_at',
            'sort_order' => 'in:ASC,DESC',
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->passes()) {
            $page = Input::get('page', 1);
            $limit = Input::get('limit', 100);
            $active = Input::get('active', 2);
            $sortField = Input::get('sort_field', 'name');
            $sortOrder = Input::get('sort_order', 'ASC');
        } else {
            $page = 1;
            $limit = 100;
            $active = 2;
            $sortField = 'name';
            $sortOrder = 'ASC';
        }

        $operator = ($active == 2) ? '<' : '=';

        $query = Project::whereHas('members', function($q) use ($user_id){
                        $q->where('user_id', $user_id);
                    })->where('is_active', $operator, $active)
                        ->orderBy($sortField, $sortOrder)
                        ->skip($limit * ($page - 1))->take($limit);

        $projects = $this->getQueryResult($query, Config::get('querycache.project.list'), 'userproject.list');

        return $this->buildResponse($projects);
    }

}
