<?php namespace Ferrydg\CommandAuthority\Authority;

abstract class Rule
{
    protected $resource;
    protected $condition;

    const WILDCARD = 'all';

    public function __construct($resource, $condition = null)
    {
        $this->resource  = $resource;
        $this->condition = $condition;
    }

    abstract public function isAllowed($authority, $resourceValue = null);

    public function isRelevant($resource)
    {
        return $this->matchesResource($resource);
    }

    public function matchesResource($resource)
    {
        return in_array($this->resource, [$resource, static::WILDCARD]);
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function getCondition()
    {
        return $this->condition;
    }
}
