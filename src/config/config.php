<?php

return array(

    'initialize' => function ($authority) {
            $authority->setEvaluator('\Ferrydg\CommandAuthority\Evaluators\LastOrEvaluator');

            $user = $authority->getCurrentUser();

            $authority->allowDecorated('execute', 'TestCommand', function ($authority, $a_user) {
                return 1 == 1;
            }, ['DoNothingDecorator'], [new AttribuutFilter(array('a', 'c.e', '^f'))]);

            $authority->allowDecorated('execute', 'TestCommand', function ($authority, $a_user) {
                return 1 == 2;
            }, ['DoNothingDecorator'], ['DoNothingDecorator']);
    }

);