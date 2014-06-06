<?php

/**
 * BaseModel class for validation and serialization
 *
 * @author Nirland
 */
abstract class BaseModel extends Eloquent implements \JsonSerializable {

    protected static $rules = array();
    protected static $messages = array();
    protected static $validationMessages = null;
       
    public static function validate($input = null, $required = true) {
        if (is_null($input)) {
            $input = Input::all();
        }

        $rules = static::$rules;
        
        if (!$required){
            foreach($rules as $key => $rule) {
                if (is_string($rules[$key])){                                        
                    $rules[$key] = str_replace('required',  '', $rule);                    
                    if (strlen($rules[$key]) == 0){
                        unset($rules[$key]);
                    }
                }                
            }
        }
                
        $validator = Validator::make($input, $rules, static::$messages);

        if ($validator->passes()) {
            return true;
        } else {            
            static::$validationMessages = $validator->messages();
            return false;
        }
    }
    
    public static function validationErrors(){
        return static::$validationMessages;
    }
    
    public function jsonSerialize()
    {
        // Return attributes with mutations applied
        return $this->toArray();
    }
}
