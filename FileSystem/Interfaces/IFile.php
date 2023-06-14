<?php

namespace Framework\FileSystem\Interfaces;

interface IFile
{
    public function getSize(): int;
    public function getPath(): string;
    public function getContent(): string;
    public function setContent(string $content): bool;
    public function appendContent(string $content): bool;
    public function move(string $path): bool;
    public function getName(): string;
    public function getExtension(): string;
    public function rename(string $name): bool;
}