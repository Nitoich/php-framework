<?php

namespace Framework\Support\Middlewares;

use Framework\Config;
use Framework\DB\ORM\Abstractions\Model;
use Framework\DB\QueryBuilder;
use Framework\DI\Container;
use Framework\Http\Controller;
use Framework\Http\Request;
use Framework\Http\Response;

class IdToModelConverter extends \Framework\Pipeline\PipelineStage
{
    public function __invoke(Request $request, \Closure $next): Response
    {
        $route_params = $request->getParams();
        $handler = $request->getRoute()->getHandler();
        $container = app()->getContainer();
        if (is_array($handler)) {
            $parameters = $container->getMethodParameters($handler[0], $handler[1]);
        } else {
            $parameters = $container->getClosureParameters($handler);
        }
        foreach ($parameters as $parameter)
        {
            if(is_subclass_of($parameter->getType()->getName(), Model::class) && !empty($route_params))
            {
                $field = array_key_first($route_params);
                $value = $route_params[$field];

                $builder = new QueryBuilder($parameter->getType()->getName()::getTableName(), Config::get('db.driver'));

                $result = $builder->where($field, $value)->first();

                $request->container()->bind($parameter->getType()->getName(), !is_null($result) ? new ($parameter->getType()->getName())($result) : null);
            }
        }
        return $next($request);
    }
}