<?php namespace Nirland\TaskyLand\Models;

/**
 * Simple response model for unification responses
 *
 * @author Nirland
 */
class ResponseModel implements \JsonSerializable {
    
    /**
    * @var mixed    
    */
    private $error;
    
    /**
    * @var array
    */
    private $data;
    
    private function __construct($data, $error) {
        $this->error = $error;
        $this->data = $data;
    }

    /**
    * Fabric
    * @return ResponseModel
    */
    public static function make($data = null, $error = false){
        return new ResponseModel($data, $error);
    }

    public function jsonSerialize() {
        return array('error' => $this->error,
                    'data' => $this->data);
    }

}
