<?php

namespace Framework\FileSystem;

class File implements Interfaces\IFile
{

    protected array $file_info = [];

    public function __construct(
        protected string $path
    )
    {
        $this->file_info = pathinfo($this->path);
    }

    public function getSize(): int
    {
        return filesize($this->path);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getContent(): string
    {
        return file_get_contents($this->path);
    }

    public function setContent(string $content): bool
    {
        // TODO: Implement setContent() method.
    }

    public function appendContent(string $content): bool
    {
        // TODO: Implement appendContent() method.
    }

    public function move(string $path): bool
    {
        // TODO: Implement move() method.
    }

    public function getName(): string
    {
        return $this->file_info['filename'];
    }

    public function getExtension(): string
    {
        return $this->file_info['extension'];
    }

    public function rename(string $name): bool
    {
        // TODO: Implement rename() method.
    }
}