<?php

namespace Framework\FileSystem;

use Framework\FileSystem\Interfaces\IDirectory;
use Framework\FileSystem\Interfaces\IFile;
use Framework\FileSystem\Interfaces\IFileManager;
use Framework\FileSystem\TypedFiles\JsonFile;
use Framework\FileSystem\TypedFiles\PhpFile;

class FileManager implements Interfaces\IFileManager
{

    protected array $catalog = [
        'files' => [],
        'directories' => []
    ];

    public function __construct(
        protected string $current_path,
        protected ?IFileManager $back = null
    )
    {
        $this->scan();
    }

    protected function scan(): void
    {
        $scan = static::scanDirectory($this->current_path);
        foreach ($scan as $file) {
            if (is_dir($this->current_path . "/$file")) {
                $this->catalog['directories'][] = $file;
                continue;
            }
            $this->catalog['files'][] = $this->getFile($file);
        }
    }

    public static function open(string $path): IFileManager
    {
        return new static($path);
    }

    public static function scanDirectory(string $path): array
    {
        $scan = scandir($path);
        unset($scan[0]);
        unset($scan[1]);
        return array_values($scan);
    }

    public function go(string $directory_name): static
    {
        return new static($this->current_path . "/$directory_name", $this);
    }

    public function getCurrentPath(): string
    {
        return $this->current_path;
    }

    public function move(string $name, string $path): static
    {
        $this->copy($name, $path);
        unlink($this->current_path . "/$name");
        return $this;
    }

    public function getFile(string $name): IFile
    {
        $extension = explode('.', $name)[array_key_last(explode('.', $name))];
        return match ($extension) {
            "php" => new PhpFile($this->current_path . "/$name"),
            "json" => new JsonFile($this->current_path . "/$name"),
            default => new File($this->current_path . "/$name"),
        };
    }

    public function back(): static
    {
        return $this->back ?? $this;
    }

    public function getTotalSize(): int
    {
        $size = 0;
        /** @var IFile $file */
        foreach ($this->catalog['files'] as $file)
        {
            $size += $file->getSize();
        }

        foreach ($this->catalog['directories'] as $directory)
        {
            $size += static::open($this->current_path . "/$directory")->getTotalSize();
        }

        return $size;
    }

    public function copy(string $name, string $path): static
    {
        copy($this->current_path . "/$name", $path);
        $this->scan();
        return $this;
    }

    public function recursiveActionOnAllFiles(\Closure $handler, ?string $filter_extension = null): static
    {
        foreach ($this->catalog['directories'] as $directory)
        {
            static::open($this->current_path . "/$directory")->recursiveActionOnAllFiles($handler, $filter_extension);
        }

        /** @var IFile $file */
        foreach ($this->catalog['files'] as $file)
        {
            if(!is_null($filter_extension) && $file->getExtension() != $filter_extension) { continue; }
            $handler($file);
        }

        return $this;
    }
}