<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends \BaseModel implements UserInterface, RemindableInterface {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Turn on soft deleting for this model.
     *
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password');

    /**
     * This attributes can be set from instances
     *
     * @var array
     */
    protected $fillable = array('username', 'password', 'firstname', 'surname', 'email', 'role');

    /**
     * Validation rules
     *
     * @var array
     */
    protected static $rules = array(
        'username' => 'required|unique:users,username|between:3,30',        
        'firstname' => 'required|between:2,30|alpha',
        'surname' => 'required|between:2,30|alpha',
        'email' => 'required|email|between:5,150',
        'role' => 'in:ADMIN,USER'
    );

    /**
     * Consts for User Roles
     * 
     */
    const ROLE_ADMIN = 'ADMIN';
    const ROLE_USER = 'USER';

    /**
     * Many to Many relationship with Project
     *
     * @return Collection Project
     */
    public function projects() {
        return $this->belongsToMany('Nirland\TaskyLand\Models\Project', 'users_projects', 'user_id', 'project_id');
    }

    /**
     * One to many relationship with Task
     *
     * @return Collection Task
     */
    public function tasks(){
        return $this->hasMany('Nirland\TaskyLand\Models\Task', 'user_id');
    }
    
    /**
     * One to many relationship with Progress
     *
     * @return Collection Progress
     */
    public function progress(){
        return $this->hasMany('Nirland\TaskyLand\Models\Progress', 'user_id');
    }
    
    /**
     * Test admin credentials
     *
     * @return boolean
     */
    public function isAdmin() {
        return ($this->role === self::ROLE_ADMIN);
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier() {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword() {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken() {
        return $this->remember_token;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value) {
        $this->remember_token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName() {
        return 'remember_token';
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail() {
        return $this->email;
    }
    
}
