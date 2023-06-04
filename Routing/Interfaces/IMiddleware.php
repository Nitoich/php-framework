<?php

namespace Framework\Routing\Interfaces;

use Framework\Http\Interfaces\IRequest;
use Framework\Pipeline\Interfaces\IPipelineStage;

interface IMiddleware extends IPipelineStage
{
    public function __invoke(mixed $input): mixed;
}