<?php namespace Ferrydg\CommandAuthority;

class Restriction extends Rule
{
    public function isAllowed($authority, $resourceValue = null)
    {
        return ! $this->checkCondition($authority, $resourceValue);
    }
}
