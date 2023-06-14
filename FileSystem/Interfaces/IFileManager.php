<?php

namespace Framework\FileSystem\Interfaces;

interface IFileManager
{
    public static function open(string $path): IFileManager;
    public function go(string $directory_name): static;
    public function back(): static;
    public function getCurrentPath(): string;
    public function move(string $name, string $path): static;
    public function copy(string $name, string $path): static;
//    public function getDirectory(string $name): IDirectory;
    public function getFile(string $name): IFile;
    public function getTotalSize(): int;
    public function recursiveActionOnAllFiles(\Closure $handler, ?string $filter_extension = null): static;
}