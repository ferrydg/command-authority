<?php namespace Ferrydg\CommandAuthority;

use Ferrydg\CommandAuthority\Facades\Authority;
use Illuminate\Foundation\Application;
use InvalidArgumentException;
use Laracasts\Commander\CommandBus;
use Laracasts\Commander\CommandTranslator;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthorityCommandBus implements CommandBus {

    /**
     * @var CommandBus
     */
    protected $bus;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var CommandTranslator
     */
    protected $commandTranslator;

    /**
     * List of optional decorators for command bus.
     *
     * @var array
     */
    protected $decorators = [];

    function __construct(CommandBus $bus, Application $app, CommandTranslator $commandTranslator)
    {
        $this->bus = $bus;
        $this->app = $app;
        $this->commandTranslator = $commandTranslator;
    }

    /**
     * Decorate the command bus with any executable actions.
     *
     * @param  string $className
     * @return mixed
     */
    public function decorate($className)
    {
        $this->decorators[] = $className;
    }

    /**
     * Execute a command with validation and authorization.
     *
     * @param $command
     * @return mixed
     */
    public function execute($command)
    {
        // If a validator is "registered," we will
        // first trigger it, before moving forward.
        $this->validateCommand($command);

        // Next, we'll execute any registered decorators.
        $this->executeDecorators($command, $this->decorators);

        // Check authorization
        if (Authority::cannot($command))
        {
            throw new UnauthorizedHttpException('Unauthorized');
        }
        $rules = [Authority::getLastEvaluator()->getEffectiveRules()];

        // Execute authorization input decorators
        foreach ($rules as $rule)
        {
            if ($rule->getCondition())
            {
                $this->executeDecorators($command, $rule->getCondition()->getInputDecorators());
            }
        }

        // Pass through to the handler class.
        $command->result = $this->bus->execute($command);

        // Execute authorization output decorators
        foreach ($rules as $rule)
        {
            if ($rule->getCondition())
            {
                $this->executeDecorators($command, $rule->getCondition()->getOutputDecorators());
            }
        }

        return $command->result;
    }

    /**
     * If appropriate, validate command data.
     *
     * @param $command
     */
    protected function validateCommand($command)
    {
        $validator = $this->commandTranslator->toValidator($command);

        if (class_exists($validator))
        {
            $this->app->make($validator)->validate($command);
        }
    }

    /**
     * Execute all registered decorators
     *
     * @param  object $data
     * @param  array $decorators
     * @throws \InvalidArgumentException
     * @return null
     */
    protected function executeDecorators($data, $decorators = array())
    {
        foreach ($decorators as $decorator)
        {
            $instance = is_string($decorator) ? $this->app->make($decorator) : $decorator;

            if ( ! $instance instanceof CommandBus)
            {
                $message = 'The class to decorate must be an implementation of Laracasts\Commander\CommandBus';

                throw new InvalidArgumentException($message);
            }

            $instance->execute($data);
        }
    }

}
