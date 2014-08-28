<?php namespace Ferrydg\CommandAuthority;

use Authority\Authority;
use Authority\Challenge;

class CommandAuthority extends Authority {

    protected $lastEvaluator = null;

    public function can($action, $resource, $resourceValue = null)
    {
        $challenge = new Challenge($action, $resource, $resourceValue);

        $rules = $this->rulesFor($challenge->getAction(), $challenge->getResource());

        $this->lastEvaluator = new $this->evaluator($rules, $this);
        return $this->lastEvaluator->check($challenge);
    }

    public function allowDecorated($action, $resource, $condition = null, $inputDecorators = [], $outputDecorators = [])
    {
        return $this->addRule(new DecoratedPrivilege($action, $resource, $condition, $inputDecorators, $outputDecorators));
    }

    public function setEvaluator($evaluator)
    {
        $this->evaluator = $evaluator;
    }

    public function getLastEvaluator()
    {
        return $this->lastEvaluator;
    }

}