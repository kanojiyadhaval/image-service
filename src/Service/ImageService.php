<?php
declare(strict_types=1);

namespace ImageService\Service;

use ImageService\Modifier\ImageProcessorInterface;
use ImageService\ErrorHandling\ImageServiceException;
use ImageService\ErrorHandling\InvalidParameterException;
use ImageService\Service\FileSystem\LocalFileSystem;

class ImageService
{
    private string $basePath;
    private array $modifiers = [];
    private LocalFileSystem $fileSystem;

    public function __construct(string $basePath)
    {
        // Use DIRECTORY_SEPARATOR to ensure path compatibility across different platforms
        $this->basePath = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->fileSystem = new LocalFileSystem();
    }

    public function addModifier(ImageProcessorInterface $modifier): void
    {
        $this->modifiers[] = $modifier;
    }

    public function handleRequest(array $params): ?string
    {
        if (!isset($params['image'])) {
            $this->sendErrorResponse(400, 'Image parameter is missing.');
            return null;
        }

        $imagePath = $this->basePath . $params['image'];
        
        // Use the LocalFileSystem object to check if the image exists
        if (!$this->fileSystem->fileExists($imagePath)) {
            $this->sendErrorResponse(404, 'Image not found.');
            return null;
        }

        foreach ($this->modifiers as $modifier) {
            if ($modifier->supports($params)) {
                try {
                    $modifiedImage = $modifier->process($imagePath, $params);

                    if ($modifiedImage) {
                        // Return the modified image instead of constructing the URL
                        return $modifiedImage;
                    } else {
                        $this->sendErrorResponse(500, 'Failed to modify the image.');
                        return null;
                    }
                } catch (InvalidParameterException $exception) {
                    $this->sendErrorResponse(400, $exception->getMessage());
                    return null;
                } catch (ImageServiceException $exception) {
                    $this->sendErrorResponse(400, $exception->getMessage());
                    return null;
                }
            }
        }

        $this->sendErrorResponse(400, 'No valid modifier found.');
        return null;
    }

    private function sendErrorResponse(int $statusCode, string $message): void
    {
        http_response_code($statusCode);
        echo json_encode(['error' => $message]);
    }
}
