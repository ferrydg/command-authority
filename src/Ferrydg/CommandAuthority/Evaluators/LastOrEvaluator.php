<?php namespace Ferrydg\CommandAuthority\Evaluators;

use Authority\Challenge;
use Authority\Evaluator;

class LastOrEvaluator extends Evaluator {

    private $effectiveRules = null;

    public function check(Challenge $challenge)
    {
        $this->challenge = $challenge;

        $allowed = false;
        if (! $this->rules->isEmpty()) {
            $this->effectiveRules = $this->rules();

            if (null != $this->effectiveRules)
            {
                $allowed = true;
            }
        }

        return $allowed;
    }

    public function getEffectiveRules()
    {
        return $this->effectiveRules;
    }

    protected function rules()
    {
        $authority = $this->authority;

        return $this->rules->reduce(function($result, $rule) use ($authority) {
            $condition = $rule->getCondition();
            $condition && $condition->bindTo($this);
            return $rule->isAllowed($authority, $this->challenge->getResourceValue()) ? $rule : $result;
        }, null);
    }
}