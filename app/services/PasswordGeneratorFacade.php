<?php namespace Nirland\TaskyLand\Services\Facades;

use Illuminate\Support\Facades\Facade;
/**
 * Facade for Password Generator service
 *
 * @author Nirland
 */
class PasswordGeneratorFacade extends Facade{
     protected static function getFacadeAccessor() { return 'passgen'; }
}
