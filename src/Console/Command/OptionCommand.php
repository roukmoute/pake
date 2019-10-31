<?php

declare(strict_types=1);

namespace Pake\Console\Command;

use Pake\Pake;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class OptionCommand extends HelpCommand
{
    protected static $defaultName = 'options';

    private $totalWidthTasks;

    private $key;

    protected function configure()
    {
        $this
            ->addOption(
                'tasks',
                'T',
                InputOption::VALUE_NONE,
                'Show all tasks, then exit.'
            )
            ->setDescription('List of all the standard options used in pake.')
        ;

        $this->setup();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('tasks')) {
            $output->write('<comment>Tasks:</comment>', true);

            foreach (Pake::tasks() as $key => $task) {
                $name = $task->name();

                $output->write(
                    sprintf(
                        '  <info>%s</info>  %s%s',
                        $name,
                        str_repeat(' ', $this->totalWidthTasks - \mb_strlen($name)),
                        $task->description()
                    ),
                    true
                );
            }
        }

        if (mb_strlen((string) $input) === 0) {
            foreach (Pake::tasks() as $task) {
                if ($task->name() !== 'default') {
                    continue;
                }

                $tasks = $task->call()();

                foreach ($tasks as $key => $tasked) {
                    $this->key = $key;

                    if ($tasked === 'default') {
                        continue;
                    }

                    Pake::tasks()->offsetGet($key)->call()();
                }

                return true;
            }

            throw new \Exception(
                'pake aborted! Don\'t know how to build task \'default\' (See the list of available tasks with `pake --tasks`)'
            );
        }
    }

    private function setup(): void
    {
        $this->totalWidthTasks = 0;

        foreach (Pake::tasks() as $task) {
            $this->totalWidthTasks = max($this->totalWidthTasks, Helper::strlen($task->name()));
        }
    }

    /**
     * This method is used when a task use exit function.
     * This allows to continue the other functions that have not yet been called.
     */
    public function __destruct()
    {
        if ($this->key === null) {
            return;
        }
        $arrayKeys = array_keys(Pake::tasks()->getArrayCopy());

        for ($i = $this->key + 1; $i <= end($arrayKeys); ++$i) {
            $task = Pake::tasks()->offsetGet($i);

            if ($task->name() === 'default') {
                continue;
            }

            $task->call()();
        }

        unset($this->key);
    }
}
