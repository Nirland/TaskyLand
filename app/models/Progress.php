<?php namespace Nirland\TaskyLand\Models;

/**
 * Progress model
 *
 * @author Nirland
 */
class Progress extends \BaseModel {
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'progress';
    
    /**
     * This attributes can be set from instances
     *
     * @var array
     */
    protected $fillable = array('title', 'message', 'revision', 'hours', 'minutes');
    
    /**
     * Validation rules
     *
     * @var array
     */
    protected static $rules = array(
                'title'  => 'required|between:3,255',
                'message'   => 'required',
                'hours' => 'integer',
                'minutes' => 'integer|between:0,59',
            );
             
    /**
     * Many to One relationship with Task
     *
     * @return Task
     */
    public function task(){
        return $this->belongsTo('Nirland\TaskyLand\Models\Task', 'task_id');
    }
    
    /**
     * Many to One relationship with User
     *
     * @return User
     */
    public function worker(){
        return $this->belongsTo('User', 'user_id');
    }
}
