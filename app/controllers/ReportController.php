<?php

use Carbon\Carbon;

/**
 * ReportController
 *
 * @author Nirland
 */
class ReportController extends \BaseController {
    
    protected $layout = 'layouts.report';
    
    private $rules = array('start' => 'required|date', 
                        'end' => 'required|date');
    
    public function userReport($user_id, $project_id = null){        
        $params = array();        
        $params[] = $user_id;
        if (!empty($project_id)){
            $params[] = $project_id;
        }
        
        $validator = Validator::make(Input::all(), $this->rules);
        if ($validator->passes()){
            $params[] = Input::get('start');
            $params[] = Input::get('end');
        } else{
            $params[] = Carbon::now()->startOfMonth();
            $params[] = Carbon::now();
        }
        
        $query = "SELECT ROUND(SUM(pr.hours) + (SUM(pr.minutes)/60)) as worktime,  
                        u.username as user, u.firstname as firstname, u.surname as surname,
                        p.name as project, t.title as task from users as u 
                        JOIN users_projects as up ON (u.id = up.user_id) 
                        JOIN projects as p ON (p.id = up.project_id) 
                        JOIN tasks as t ON (t.project_id = p.id) 
                        JOIN progress as pr ON (pr.task_id = t.id and pr.user_id = u.id) 
                        WHERE u.id = ?". 
                        (!empty($project_id) ? " AND p.id=? " : "")."
                        AND pr.created_at BETWEEN ? AND ? 
                        GROUP BY u.username, p.name, t.title  
                        ORDER BY worktime DESC";
        
        $entities = DB::select($query, $params);
                
        $this->layout->content = View::make('userreport')->with('entities', $entities);         
    }
    
    public function projectReport($project_id){
        $params = array();        
        $params[] = $project_id;
        
        $validator = Validator::make(Input::all(), $this->rules);
        if ($validator->passes()){
            $params[] = Input::get('start');
            $params[] = Input::get('end');
        } else{
            $params[] = Carbon::now()->startOfMonth();
            $params[] = Carbon::now();
        }
        
        $query = "SELECT ROUND(SUM(pr.hours) + (SUM(pr.minutes)/60)) as worktime, 
                        p.name as project, t.title as task from projects as p
                        JOIN tasks as t ON (t.project_id = p.id)
                        JOIN progress as pr ON (pr.task_id = t.id)
                        WHERE p.id = ?
                        AND pr.created_at BETWEEN ? AND ?
                        GROUP BY p.name, t.title 
                        ORDER BY worktime DESC";
        
        $entities = DB::select($query, $params);
                
        $this->layout->content = View::make('projectreport')->with('entities', $entities);
    }
    
}
