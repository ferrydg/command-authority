<?php

return array(

    'initialize' => function ($authority) {
            $authority->setEvaluator('\Ferrydg\CommandAuthority\Evaluators\LastOrEvaluator');

            $user = $authority->getCurrentUser();

            $authority->allow('Example\ExampleCommand', new \Example\ExampleCondition());
    }

);