<?php namespace Ferrydg\CommandAuthority\Authority;

/**
 * RuleRepository collections contain and interact with Rule instances
 *
 * @package Authority
 */
class RuleRepository extends Collection
{
    /**
     * Add a rule to the collection
     *
     * @return void
     */
    public function add(Rule $rule)
    {
        return $this->push($rule);
    }

    /**
     * Get all rules only relevant to the given action and resource
     *
     * @param  string $action Action to check against
     * @param  string $resource Resource to check against
     * @return Collection
     */
    public function getRelevantRules($resource)
    {
        return $this->filter(function($rule) use ($resource) {
            return $rule->isRelevant($resource);
        });
    }
}
