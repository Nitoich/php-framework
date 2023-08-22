<?php

spl_autoload_register(function ($class_name) {
    $file_path = __DIR__ . "/../{$class_name}.php";
    if(file_exists($file_path))
    {
        include_once $file_path;
    }
});

function response(mixed $data = null): \Framework\Http\Response
{
    return new \Framework\Http\Response($data);
}

$app = new \Framework\WebKernel();
$app->setContainer(new \Framework\DI\Container());

function app(): \Framework\WebKernel
{
    global $app;
    return $app;
}