<?php

spl_autoload_register(function ($class_name) {
    $file_path = __DIR__ . "/../{$class_name}.php";
    if(file_exists($file_path))
    {
        include_once $file_path;
    }
});

\Framework\DI\Container::getInstance()->singleton(\Framework\Http\Request::class);

function response(mixed $data = null): \Framework\Http\Response
{
    return new \Framework\Http\Response($data);
}