<?php

use PhpCsFixer\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

task(
    'default',
    function () {
        return ['fix'];
    }
);

desc('PHP Coding Standards Fixer');
task(
    'fix',
    function () {
        $application = new Application();
        $application->setAutoExit(false);
        $application->run(new ArrayInput(['fix']));
    }
);