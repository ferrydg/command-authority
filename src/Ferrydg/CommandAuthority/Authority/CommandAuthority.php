<?php namespace Ferrydg\CommandAuthority;

use Authority\Authority;
use Authority\Challenge;
use Eur\Webservice\Authority\Conditions\Condition;

class CommandAuthority extends Authority {

    protected $lastEvaluator = null;

    public function can($action, $resource, $resourceValue = null)
    {
        $challenge = new Challenge($action, $resource, $resourceValue);

        $rules = $this->rulesFor($challenge->getAction(), $challenge->getResource());

        $this->lastEvaluator = new $this->evaluator($rules, $this);
        return $this->lastEvaluator->check($challenge);
    }

    public function allow($action, $resource, $condition = null)
    {
        if ($condition != null && ! $condition instanceof Condition) throw new \InvalidArgumentException('condition not an instance of Condition');

        return $this->addRule(new CommandPrivilege($action, $resource, $condition));
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