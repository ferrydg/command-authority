<?php namespace Ferrydg\CommandAuthority\Authority;

class Authority
{
    protected $user;
    protected $evaluator;
    protected $rules;
    protected $listener;
    protected $aliases = [];
    protected $lastEvaluator = null;

    public function __construct($currentUser, $evaluator = 'Ferrydg\CommandAuthority\Evaluators\LastOrEvaluator', $listener = null)
    {
        $this->user     = $currentUser;
        $this->evaluator = $evaluator;
        $this->rules    = new RuleRepository;
        $this->listener = $listener ?: null; // new NullListener();
    }

    public function setEvaluator($evaluator)
    {
        $this->evaluator = $evaluator;
    }

    public function getLastEvaluator()
    {
        return $this->lastEvaluator;
    }

    public function can($resource, $resourceValue = null)
    {
        $challenge = new Challenge($resource, $resourceValue);

        $rules = $this->rulesFor($challenge->getResource());

        $this->lastEvaluator = new $this->evaluator($rules, $this);
        return $this->lastEvaluator->check($challenge);
    }

    public function cannot($resource, $resourceValue = null)
    {
        return ! $this->can($resource, $resourceValue);
    }

    public function allow($resource, $condition = null)
    {
        if ($condition != null && ! $condition instanceof Condition) throw new \InvalidArgumentException('condition not an instance of Condition');

        return $this->addRule(new Privilege($resource, $condition));
    }

    public function deny($resource, $condition = null)
    {
        return $this->addRule(new Restriction($resource, $condition));
    }

    protected function addRule(Rule $rule)
    {
        $this->rules->add($rule);
        return $rule;
    }

    public function setCurrentUser($currentUser)
    {
        $this->user = $currentUser;
    }

    public function rulesFor($resource)
    {
        return $this->rules->getRelevantRules($resource);
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function resolveResourcePair($resource, $resourceValue = null)
    {
        if (! is_string($resource)) {
            $resourceValue = $resource;
            $resource      = get_class($resourceValue);
        }

        return [$resource, $resourceValue];
    }

    public function getCurrentUser()
    {
        return $this->user;
    }

    public function user()
    {
        return $this->getCurrentUser();
    }
}
