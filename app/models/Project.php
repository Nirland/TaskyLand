<?php namespace Nirland\TaskyLand\Models;

/**
 * Project model
 *
 * @author Nirland
 */
class Project extends \BaseModel {
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'projects';
    
    /**
     * This attributes can be set from instances
     *
     * @var array
     */
    protected $fillable = array('name', 'description', 'is_active');
    
    /**
     * Validation rules
     *
     * @var array
     */
    protected static $rules = array(
                'name'  => 'required|between:3,150|alphanum',
                'description'   => 'required',                
                'is_active' => 'integer|between:0,1'                
            );
        
    /**
     * Many to Many relationship with User
     *
     * @return Collection User
     */
    public function members(){        
        return $this->belongsToMany('User', 'users_projects', 'project_id', 'user_id');
    }
            
    /**
     * One to many relationship with Task
     *
     * @return Collection Task
     */
    public function tasks(){
        return $this->hasMany('Nirland\TaskyLand\Models\Task', 'project_id');
    }
}
