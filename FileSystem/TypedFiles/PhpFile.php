<?php

namespace Framework\FileSystem\TypedFiles;

class PhpFile extends \Framework\FileSystem\File
{
    public function include(): mixed
    {
        return include($this->path);
    }

    public function require(): void
    {
        require($this->path);
    }
}