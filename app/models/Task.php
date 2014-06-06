<?php namespace Nirland\TaskyLand\Models;

/**
 * Task model
 *
 * @author Nirland
 */
class Task extends \BaseModel {
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tasks';
    
    /**
     * This attributes can be set from instances
     *
     * @var array
     */
    protected $fillable = array('title', 'description', 'date_end', 'kind', 'status');
    
    /**
     * Validation rules
     *
     * @var array
     */
    protected static $rules = array(
                'title'  => 'required|between:3,255',
                'description'   => 'required',                
                'date_end' => 'date',
                'kind' => 'in:NORMAL,IMPORTANT',
                'status' => 'in:OPENED,CLOSED,DELAYED'                
            );
    
    /**
     * Consts for task status enum
     *
     * @var string
     */
    const STATUS_OPENED = 'OPENED';
    const STATUS_CLOSED = 'CLOSED';
    const STATUS_DELAYED = 'DELAYED';
    
    /**
     * Consts for task kind enum
     *
     * @var string
     */
    const KIND_NORMAL = 'NORMAL';
    const KIND_IMPORTANT = 'IMPORTANT';
    
    /**
     * Many to One relationship with User
     *
     * @return User
     */
    public function creator(){
        return $this->belongsTo('User', 'user_id')->withTrashed();
    }
    
    /**
     * Many to One relationship with Project
     *
     * @return Project
     */
    public function project(){
        return $this->belongsTo('Nirland\TaskyLand\Models\Project', 'project_id');
    }
    
    /**
     * One to many relationship with Progress
     *
     * @return Collection Project
     */
    public function progress(){
        return $this->hasMany('Nirland\TaskyLand\Models\Progress', 'task_id');
    }
}
