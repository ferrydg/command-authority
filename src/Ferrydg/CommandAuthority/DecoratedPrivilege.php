<?php namespace Ferrydg\CommandAuthority;

use Authority\Privilege;

class DecoratedPrivilege extends Privilege {

    const ALLOW = true;
    const DENY = false;

    /**
     * @var array Decorators to be applied on the input if this rule is effective
     */
    protected $inputDecorators = array();

    /**
     * @var array Decorators to be applied on the output if this rule is effective
     */
    protected $outputDecorators = array();

    /**
     * DecoratedRule constructor
     *
     * @param string $action Action the rule applies to
     * @param string|mixed $resource Name of resource or instance of object
     * @param Closure|null $condition Optional closure to act as a condition
     * @param array $inputDecorators Decorators to execute on input
     * @param array $outputDecorators Decorators to execute on output
     */
    public function __construct($action, $resource, $condition = null, array $inputDecorators = [], array $outputDecorators = [])
    {
        parent::__construct($action, $resource, $condition);
        $this->inputDecorators = $inputDecorators;
        $this->outputDecorators = $outputDecorators;
    }

    public function getInputDecorators()
    {
        return $this->inputDecorators;
    }

    public function getOutputDecorators()
    {
        return $this->outputDecorators;
    }
}