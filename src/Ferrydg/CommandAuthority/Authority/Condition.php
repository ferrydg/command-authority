<?php namespace Ferrydg\CommandAuthority\Authority;

/**
 * Class Condition
 * @package Eur\Webservice\Authority\Conditions
 */
abstract class Condition {

	/**
	 * @var array
	 */
    protected $inputDecorators = [];

	/**
	 * @var array
	 */
    protected $outputDecorators = [];

	/**
	 * @param $resourceValue
	 * @return mixed
	 */
    public abstract function check($resourceValue);

	/**
	 * @return array
	 */
    public function getInputDecorators()
    {
        return $this->inputDecorators;
    }

	/**
	 * @return array
	 */
    public function getOutputDecorators()
    {
        return $this->outputDecorators;
    }

}