<?php namespace Ferrydg\CommandAuthority\Authority;

class Restriction extends Rule
{
    public function isAllowed($authority, $resourceValue = null)
    {
        return ! $this->checkCondition($authority, $resourceValue);
    }
}
