<?php

use Illuminate\Database\Eloquent\Collection;
use Nirland\TaskyLand\Models\ResponseModel;

abstract class BaseController extends Controller {

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if ( ! is_null($this->layout))
        {
            $this->layout = View::make($this->layout);
        }
    }
    
    /**
     * Build json response or return 404
     *
     * @param mixed 
     * @param string
     * 
     * @return Response
     */
    protected function buildResponse($data, $error = null)
    {   
        if ($data instanceof Collection)
        {
            $data = $data->toArray();
        }
        
        if (empty($data) && empty($error)){
            return Response::make(json_encode(ResponseModel::make(null, 'Data not found'), JSON_UNESCAPED_UNICODE), 404)
                    ->header('Content-Type', "application/json");            
        } elseif(empty($data) && !empty($error)){
            return Response::make(json_encode(ResponseModel::make(null, $error), JSON_UNESCAPED_UNICODE), 200)
                    ->header('Content-Type', "application/json");
        } else{
            return Response::make(json_encode(ResponseModel::make($data, null), JSON_UNESCAPED_UNICODE), 200)
                    ->header('Content-Type', "application/json");
        }        
    }
    
    /**
     * Get query result from database or cache
     * This method solving a problem with Laravel Builder->remeber() 
     * when it's using with relationships.
     *
     * @param Illuminate\Database\Query\Builder $query
     * @param string $time
     * @param string $keyPrefix
     * 
     * @return mixed
     */
    protected function getQueryResult($query, $time, $keyPrefix = '')
    {   
        if (empty($time) || 
           (empty($query) && !($query instanceof \Illuminate\Database\Query\Builder))){
           return null;
        }
       
        $key = md5($keyPrefix.$query->toSql().serialize($query->getBindings()));
        
        if (!Cache::has($key)) {
            $result = Cache::remember($key, $time, function() use($query) {
                return $query->get();
            });
        } else {
            $result = Cache::get($key);
        }
        
        return $result;
    }

}
