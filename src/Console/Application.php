<?php

declare(strict_types=1);

namespace Pake\Console;

use Pake\Console\Command\OptionCommand;
use Pake\Pake;
use Pake\Task;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends BaseApplication
{
    private const DEFAULT_PAKEFILES = [
        'pakefile',
        'Pakefile',
        'pakefile.php',
        'Pakefile.php',
    ];

    private static $logo = <<<TEXT
  _____           _           
 |  __ \         | |          
 | |__) |  ____  | | __   ___ 
 |  ___/  / _  | | |/ /  / _ \
 | |     | (_| | |   <  |  __/
 |_|      \____| |_|\_\  \___|
TEXT;

    public const VERSION = '1.0.0-DEV';

    public function __construct()
    {
        parent::__construct('Pake', self::VERSION);

        $this->rawLoadPakefile();

        $command = new OptionCommand();

        $this->add($command);
        $this->setDefaultCommand($command->getName());
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        try {
            return parent::doRun($input, $output);
        } catch (CommandNotFoundException $exception) {
            if (!in_array(
                $this->getCommandName($input),
                array_map(
                    function (Task $task) {
                        return $task->name();
                    },
                    Pake::tasks()->getArrayCopy()
                )
            )) {
                throw $exception;
            }
            foreach (Pake::tasks() as $task) {
                if ($this->getCommandName($input) === $task->name()) {
                    return $task->call()();
                }
            }
        }
    }

    public function getHelp()
    {
        return self::$logo;
    }

    private function rawLoadPakefile()
    {
        include $this->loadRakefile();
    }

    private function loadRakefile(): string
    {
        $pakefile = $this->findPakefileLocation();
        if (!$pakefile) {
            throw new \RuntimeException(
                sprintf('No Pakefile found (looking for: %s)', implode(', ', self::DEFAULT_PAKEFILES))
            );
        }

        return $pakefile;
    }

    private function findPakefileLocation(): ?string
    {
        $here = getcwd();
        foreach (self::DEFAULT_PAKEFILES as $filename) {
            if (file_exists($here . \DIRECTORY_SEPARATOR . $filename)) {
                return $filename;
            }
        }
    }
}
