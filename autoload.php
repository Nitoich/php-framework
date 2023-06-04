<?php

spl_autoload_register(function ($class_name) {
    $file_path = $_SERVER['DOCUMENT_ROOT'] . "/{$class_name}.php";
    if(file_exists($file_path))
    {
        include_once $file_path;
    }
});

$container = \Framework\DI\Container::getInstance();
$container->bind(\Framework\Http\Interfaces\IRequest::class, new \Framework\Http\Request());