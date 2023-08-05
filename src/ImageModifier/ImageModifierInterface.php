<?php
declare(strict_types=1);

namespace ImageModifier;

interface ImageModifierInterface
{
    public function supports(array $params): bool;

    public function modifyImage(string $imagePath, array $params): ?string;
}
