<?php

namespace Framework\Command\Interfaces;

interface ICommand
{
    public function execute(array $args = []): void;
}