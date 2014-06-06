<?php

/*
|--------------------------------------------------------------------------
| Query cache
|--------------------------------------------------------------------------
| Config for caching queries 
| Format: key => value
| Where key - query name, like a "entity.list" or "entity.find"
| And value - time in minutes.
|
*/
return array(

    /**
     * Project entity queries
     *     
     */
    'project.list' => 1,
    'project.id' => 1,
    
    /**
     * User entity queries
     *     
     */
    'user.list' => 1,
    'user.id' => 1,
    
    /**
     * Task entity queries
     *     
     */
    'task.list' => 1,
    'task.id' => 1,
    
    /**
     * Progress entity queries
     *     
     */
    'progress.list' => 1,
    'progress.id' => 1,
);
