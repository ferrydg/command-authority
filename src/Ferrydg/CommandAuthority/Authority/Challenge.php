<?php namespace Ferrydg\CommandAuthority\Authority;

class Challenge
{
    protected $resource;
    protected $resourceValue;

    public function __construct($resource, $resourceValue = null)
    {
        if (is_string($resource)) {
            $this->resource      = $resource;
            $this->resourceValue = $resourceValue;
        } else {
            $this->resource      = get_class($resource);
            $this->resourceValue = $resource;
        }
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function getResourceValue()
    {
        return $this->resourceValue;
    }

    public function getResourcePair()
    {
        return [$this->getResource(), $this->getResourceValue()];
    }
}
