<?php namespace Ferrydg\CommandAuthority;

abstract class Rule
{
    protected $action;
    protected $resource;
    protected $condition;

    const WILDCARD = 'all';

    public function __construct($action, $resource, $condition = null)
    {
        $this->action    = $action;
        $this->resource  = $resource;
        $this->condition = $condition;
    }

    abstract public function isAllowed($authority, $resourceValue = null);

    public function isRelevant($action, $resource)
    {
        return $this->matchesAction($action) && $this->matchesResource($resource);
    }

    public function matchesAction($action)
    {
        return in_array($this->action, (array) $action);
    }

    public function matchesResource($resource)
    {
        return in_array($this->resource, [$resource, static::WILDCARD]);
    }

    public function getAction()
    {
        return $this->action;
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
