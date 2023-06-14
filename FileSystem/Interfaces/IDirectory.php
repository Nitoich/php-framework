<?php

namespace Framework\FileSystem\Interfaces;

interface IDirectory
{
    public function getPath(): string;
    public function getName(): string;
    public function getTotalSize(): string;
    public function getFilesCount(): int;
    public function getDirectoryCount(): int;
    public function move(string $path): bool;
    public function rename(string $name): bool;
    public function getDirectory(string $name): IDirectory;
    public function getFile(string $name): IFile;
}