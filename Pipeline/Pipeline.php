<?php

namespace Framework\Pipeline;

use Framework\Pipeline\Interfaces\IPipelineStage;

class Pipeline
{
    protected array $stages = [];
    public function pipe(IPipelineStage $stage): static
    {
        $this->stages[] = $stage;
        return $this;
    }

    public function process(mixed $input): mixed
    {
        foreach ($this->stages as $stage)
        {
            $input = $stage($input);
        }

        return $input;
    }
}