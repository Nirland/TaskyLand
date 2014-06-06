<?php

use Nirland\TaskyLand\Models\Project;

class ProjectController extends \BaseController {

    /**
     * Control admin credintials for modification Project
     *
     */
    public function __construct() {
        $this->beforeFilter('admin', array('only' => array('store', 'update', 'destroy')));
    }

    /**
     * Display a listing of the project.
     * 
     * @param int $_GET['page'] 
     * @param int $_GET['limit'] 
     * @param int $_GET['active'] 
     * @param string $_GET['sort_field'] 
     * @param string $_GET['sort_order'] 
     * 
     * @return Response
     */
    public function index() {
        $rules = array('page' => 'integer|min:1', 
                    'limit' => 'integer|min:1', 
                    'active' => 'integer|between:0,2',
                    'sort_field' => 'in:name,created_at',
                    'sort_order' => 'in:ASC,DESC',
                );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->passes()){
            $page = Input::get('page', 1);
            $limit = Input::get('limit', 100);
            $active = Input::get('active', 2);
            $sortField = Input::get('sort_field', 'name');
            $sortOrder = Input::get('sort_order', 'ASC');
        } else{
            $page = 1;
            $limit = 100;
            $active = 2;
            $sortField = 'name';
            $sortOrder = 'ASC';
        }
        
        $operator = ($active == 2) ? '<' : '=';

        $query = Project::where('is_active', $operator, $active)
                ->orderBy($sortField, $sortOrder)
                ->skip($limit * ($page - 1))->take($limit);
                
        $projects = $this->getQueryResult($query, Config::get('querycache.project.list'), 'project.list');
        
        return $this->buildResponse($projects);
    }

    /**
     * Store a newly created Project in storage.
     *
     * @return Response
     */
    public function store() {       
        if (Project::validate()) {
            try {
                $project = DB::transaction(function() {           
                            $project = new Project();
                            $project->name = Input::get('name');
                            $project->description = Input::get('description');
                            $project->is_active = Input::get('is_active', 1);
                            $project->save();
                    
                            $members = Input::get('members');                            
                            if (is_array($members)) {
                                foreach ($members as $member){
                                    $project->members()->attach($member);
                                }                                
                            }

                            return $project;
                        });
            } catch (Exception $ex) {
                $error = 'Project name exists or not correct members data';
            }
        } else {
            $error = 'Not correct project data';
        }

        if (!isset($error) && (isset($project) && $project instanceof Project)) {
            return $this->buildResponse($project->id);
        } else {
            return $this->buildResponse(null, $error);
        }
    }

    /**
     * Display the specified Project.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $query = Project::with('members')
                ->where('id', $id);
               
        $project = $this->getQueryResult($query, Config::get('querycache.project.id'), 'project.id');
        
        return $this->buildResponse($project);
    }

    /**
     * Update the specified Project in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {        
        if (Project::validate(null, false)) {
            try {
                $project = DB::transaction(function() use ($id) {           
                            $project = Project::find($id);
                            $project->name = Input::get('name', $project->name);
                            $project->description = Input::get('description', $project->description);
                            $project->is_active = Input::get('is_active', $project->is_active);
                            $project->save();
                    
                            $members = Input::get('members');                            
                            if (is_array($members)) {                                
                                $project->members()->sync($members);                                
                            }

                            return $project;
                        });
            } catch (Exception $ex) {
                $error = 'Project name exists or not correct members data';
            }
        } else {
            $error = 'Not correct project data';
        }
        
        if (!isset($error) && (isset($project) && $project instanceof Project)) {
            return $this->buildResponse($project);
        } else {
            return $this->buildResponse(null, $error);
        }
    }

    /**
     * Remove the specified Project from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $affected = Project::destroy($id);        
        return $this->buildResponse($affected);
    }

}
