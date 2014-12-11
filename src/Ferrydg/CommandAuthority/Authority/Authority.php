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

    public function can($action, $resource, $resourceValue = null)
    {
        $challenge = new Challenge($action, $resource, $resourceValue);

        $rules = $this->rulesFor($challenge->getAction(), $challenge->getResource());

        $this->lastEvaluator = new $this->evaluator($rules, $this);
        return $this->lastEvaluator->check($challenge);
    }

    public function cannot($action, $resource, $resourceValue = null)
    {
        return ! $this->can($action, $resource, $resourceValue);
    }

    public function allow($action, $resource, $condition = null)
    {
        if ($condition != null && ! $condition instanceof Condition) throw new \InvalidArgumentException('condition not an instance of Condition');

        return $this->addRule(new Privilege($action, $resource, $condition));
    }

    public function deny($action, $resource, $condition = null)
    {
        return $this->addRule(new Restriction($action, $resource, $condition));
    }

    protected function addRule(Rule $rule)
    {
        $this->rules->add($rule);
        return $rule;
    }

    public function addAlias($name, $actions)
    {
        $alias = new RuleAlias($name, $actions);
        $this->aliases[$name] = $alias;
        return $alias;
    }

    public function setCurrentUser($currentUser)
    {
        $this->user = $currentUser;
    }

    public function rulesFor($action, $resource)
    {
        $aliases = $this->namesForAction($action);
        return $this->rules->getRelevantRules($aliases, $resource);
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function namesForAction($action)
    {
        $actions = array($action);

        foreach ($this->aliases as $key => $alias) {
            if ($alias->includes($action)) {
                $actions[] = $key;
            }
        }

        return $actions;
    }

    public function resolveResourcePair($resource, $resourceValue = null)
    {
        if (! is_string($resource)) {
            $resourceValue = $resource;
            $resource      = get_class($resourceValue);
        }

        return [$resource, $resourceValue];
    }

    public function getAliases()
    {
        return $this->aliases;
    }

    public function getAlias($name)
    {
        return $this->aliases[$name];
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
