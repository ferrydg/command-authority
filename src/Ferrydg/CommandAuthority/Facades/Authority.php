<?php
namespace Ferrydg\CommandAuthority\Facades;

use Illuminate\Support\Facades\Facade;

class Authority extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'authority'; }

}