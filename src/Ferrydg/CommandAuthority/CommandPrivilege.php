<?php namespace Ferrydg\CommandAuthority;

use Authority\Privilege;

class CommandPrivilege extends Privilege {

    public function isAllowed($authority, $resourceValue = null)
    {
        return $this->condition ? $this->condition->check($resourceValue) : true;
    }
}