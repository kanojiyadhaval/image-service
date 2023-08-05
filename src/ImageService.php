<?php
declare(strict_types=1);

namespace ImageService;

use ImageModifier\ImageModifierInterface;

class ImageService
{
    private string $basePath;
    private string $modifiedPath;
    private array $modifiers = [];

    public function __construct(string $basePath)
    {
        // Use DIRECTORY_SEPARATOR to ensure path compatibility across different platforms
        $this->basePath = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->modifiedPath = $this->basePath . 'modified' . DIRECTORY_SEPARATOR;
    }

    public function addModifier(ImageModifierInterface $modifier): void
    {
        $this->modifiers[] = $modifier;
    }

    public function handleRequest(array $params): void
    {
        if (!isset($params['image'])) {
            $this->sendErrorResponse(400, 'Image parameter is missing.');
            return;
        }

        $imagePath = $this->basePath . $params['image'];

        if (!file_exists($imagePath)) {
            $this->sendErrorResponse(404, 'Image not found.');
            return;
        }

        foreach ($this->modifiers as $modifier) {
            if ($modifier->supports($params)) {
                $modifiedImage = $modifier->modifyImage($imagePath, $params);

                if ($modifiedImage) {
                    // Use the modifiedPath property to build the modified image URL
                    $modifiedImageUrl = $this->getModifiedImageUrl($modifiedImage);
                    $this->redirect($modifiedImageUrl);
                    return;
                } else {
                    $this->sendErrorResponse(400, 'Invalid modifier or parameters.');
                    return;
                }
            }
        }

        $this->sendErrorResponse(400, 'No valid modifier found.');
        return;
    }

    private function sendErrorResponse(int $statusCode, string $message): void
    {
        http_response_code($statusCode);
        echo 'Error: ' . $message;
    }

    private function getModifiedImageUrl(string $modifiedImage): string
    {
        // Use proper URL building to avoid concatenation issues
        return 'http://' . $_SERVER['HTTP_HOST'] . '/images/modified/' . $modifiedImage;
    }

    private function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
