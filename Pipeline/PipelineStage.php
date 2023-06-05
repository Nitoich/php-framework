<?php

namespace Framework\Pipeline;

abstract class PipelineStage
{
    public function __invoke(mixed $input): mixed
    {
        return $input;
    }
}