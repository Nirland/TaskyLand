<?php

/* 
 * Bindings services for IoC
 * 
 * @author Nirland
 */

/* 
 * Password Generator binding
 * 
 */
App::singleton('passgen', function()
{
    return new Nirland\TaskyLand\Services\PasswordGenerator();
});
