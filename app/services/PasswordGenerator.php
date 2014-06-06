<?php namespace Nirland\TaskyLand\Services;

/**
 * Simple Password generator service
 *
 * @author Nirland
 */
class PasswordGenerator {
    
    const ALPHABET = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!_';
    
    public function make($length = 8){
        $password = substr( str_shuffle(self::ALPHABET ), 0, $length );
        return $password;
    }
}
