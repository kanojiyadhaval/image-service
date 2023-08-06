<?php
declare(strict_types=1);

namespace ImageService\Modifier;

interface ImageProcessorInterface
{
    public function supports(array $params): bool;
    public function process(string $imagePath, array $params): ?string;
}
