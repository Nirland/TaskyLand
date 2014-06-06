<?php

/* 
 * Event bindings
 * 
 * @author Nirland
 */

if (Config::get('app.debug') === true){
    Event::listen("illuminate.query", function($query, $bindings, $time, $name){
        \Log::info($time.' '.$query);        
    });
}
