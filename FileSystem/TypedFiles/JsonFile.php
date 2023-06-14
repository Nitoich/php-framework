<?php

namespace Framework\FileSystem\TypedFiles;

class JsonFile extends \Framework\FileSystem\File
{
    public function getArray(): array
    {
        return json_decode($this->getContent(), true);
    }
}