<?php namespace Ferrydg\CommandAuthority\Authority;

class Privilege extends Rule
{
    public function isAllowed($authority, $resourceValue = null)
    {
        return $this->condition ? $this->condition->check($resourceValue) : true;
    }
}
