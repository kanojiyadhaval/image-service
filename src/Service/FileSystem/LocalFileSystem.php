<?php
declare(strict_types=1);

namespace ImageService\Service\FileSystem;

class LocalFileSystem implements FileSystemInterface
{
    public function fileExists(string $filePath): bool
    {
        return file_exists($filePath);
    }

    public function getFileContents(string $filePath): string
    {
        if (!$this->fileExists($filePath)) {
            throw new \InvalidArgumentException("File not found: {$filePath}");
        }

        return file_get_contents($filePath);
    }
}
