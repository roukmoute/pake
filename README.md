# PHP Make

Pake is a simple task runner.

Pake is a Make-like program implemented in PHP.  
Tasks and dependencies are specified in standard PHP syntax.

Pakefiles (pake's version of Makefiles) are completely defined in standard PHP syntax.

## Installation

These commands requires you to have Composer installed globally.
Open a command console, enter your project directory and execute the following 
commands to download the latest stable version:

```sh
composer require --dev roukmoute/pake
```

## Usage

### Example

First, you must write a `Pakefile` file which contains the build rules.  
Here's a simple example:

```php
<?php

use PhpCsFixer\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

task(
    'default',
    function () {
        return ['fix', 'example'];
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
```

This Pakefile has two tasks:

- A task named `fix`, which – upon invocation – will fix your code to follow 
standards in PHP:
```sh
▶ php ./vendor/bin/pake fix
```
- A task named `default`. This task does nothing by itself, but it has exactly 
one dependency, namely the `fix` task.  
Invoking the `default` task will cause Pake to invoke the `fix` task as well.

Running the `pake` command without any options will cause it to run the 
`default` task in the Pakefile:

```sh
▶ php ./vendor/bin/pake
Loaded config default from ".php_cs.dist".
Using cache file ".php_cs.cache".
....unit fix output here...
```

Type `pake --help` for all available options.