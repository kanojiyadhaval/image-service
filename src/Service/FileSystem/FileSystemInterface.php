<?php
declare(strict_types=1);

namespace ImageService\Service\FileSystem;

interface FileSystemInterface
{
    public function fileExists(string $filePath): bool;
    public function getFileContents(string $filePath): string;
}
