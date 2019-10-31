<?php

declare(strict_types=1);

namespace Pake;

class Task
{
    /** @var string */
    private $name;

    /** @var callable */
    private $call;

    /** @var string|null */
    private $description;

    public function __construct(string $name, callable $call, ?string $description = null)
    {
        $this->name = $name;
        $this->call = $call;
        $this->description = $description;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function call(): callable
    {
        return $this->call;
    }

    public function description(): ?string
    {
        return $this->description;
    }
}
