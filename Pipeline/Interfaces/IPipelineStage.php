<?php

namespace Framework\Pipeline\Interfaces;

interface IPipelineStage
{
    public function __invoke(mixed $input): mixed;
}