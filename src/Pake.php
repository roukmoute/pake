<?php

declare(strict_types=1);

namespace Pake;

class Pake
{
    private static $tasks;

    private static $desc;

    public static function desc(string $description)
    {
        self::$desc = $description;
    }

    public static function task(string $name, callable $callable)
    {
        if (self::$tasks === null) {
            self::$tasks = new TaskCollection();
        }

        self::$tasks->append(new Task($name, $callable, self::$desc));
        self::$desc = null;
    }

    public static function tasks(): TaskCollection
    {
        return self::$tasks;
    }
}
