<?php namespace Ferrydg\CommandAuthority\Evaluators;

use Ferrydg\CommandAuthority\Authority\Challenge;
use Ferrydg\CommandAuthority\Authority\Authority;

class Evaluator
{
    public function __construct($rules, Authority $authority)
    {
        $this->rules = $rules;
        $this->authority = $authority;
        $this->challenge = null;
    }

    public function check(Challenge $challenge)
    {
        $this->challenge = $challenge;

        if (! $this->rules->isEmpty()) {
            $allowed = $this->rules();

            $last = $this->rules->last();

            $condition = $last->getCondition();
            $condition && $condition->bindTo($this);
            $allowed = $allowed || $last->isAllowed($this->authority, $challenge->getResourceValue());
        } else {
            $allowed = false;
        }

        return $allowed;
    }

    protected function rules()
    {
        $authority = $this->authority;

        return $this->rules->reduce(function($result, $rule) use ($authority) {
            $condition = $rule->getCondition();
            $condition && $condition->bindTo($this);
            return $result && $rule->isAllowed($authority, $this->challenge->getResourceValue());
        }, true);
    }
}
