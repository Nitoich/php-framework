<?php

namespace Framework\Command;

use Framework\Command\Interfaces\ICommand;

class Invoker
{
    protected array $commands = [];

    public function __construct() {}

    public function bind(string $key, ICommand $command): void
    {
        $this->commands[$key] = $command;
    }

    public function executeCommand(string $key, array $args = []): void
    {
        $this->commands[$key]->execute($args);
    }
}