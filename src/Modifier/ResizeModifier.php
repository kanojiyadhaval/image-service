<?php
declare(strict_types=1);

namespace ImageService\Modifier;

use ImageService\Service\FileSystem\LocalFileSystem;
use ImageService\Service\ErrorHandling\ImageNotFoundException;
use ImageService\Service\ErrorHandling\InvalidParameterException;

class ResizeModifier implements ImageProcessorInterface
{
    private array $supportedFormats = [
        'jpeg' => 'imagecreatefromjpeg',
        'jpg'  => 'imagecreatefromjpeg',
        'png'  => 'imagecreatefrompng',
        'gif'  => 'imagecreatefromgif',
    ];

    private LocalFileSystem $fileSystem;

    public function __construct(LocalFileSystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    public function supports(array $params): bool
    {
        return isset($params['resize']);
    }

    public function process(string $imagePath, array $params): ?string
    {
        // Use the LocalFileSystem object to check if the image exists
        if (!$this->fileSystem->fileExists($imagePath)) {
            throw new ImageNotFoundException('Image not found.');
        }

        $resizeDimensions = explode('x', $params['resize']);
        if (count($resizeDimensions) === 2) {
            list($width, $height) = $resizeDimensions;
            $width = intval($width); // Convert to integer
            $height = intval($height); // Convert to integer

            // Get the original image extension
            $originalExtension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

            // Check if the image format is supported
            if (!isset($this->supportedFormats[$originalExtension])) {
                throw new ImageNotFoundException('Unsupported image format.');
            }

            // Create the image based on the original extension
            $imageFunction = $this->supportedFormats[$originalExtension];
            $image = $imageFunction($imagePath);

            // Create a new image with the desired dimensions
            $resizedImage = imagecreatetruecolor($width, $height);
            imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));

            // Generate the modified image path with the same directory as the original image
            $imageDirectory = dirname($imagePath);
            $modifiedImage = $imageDirectory . '/modified/' . uniqid('resize_', true) . "_{$width}x{$height}." . $originalExtension;

            // Save the modified image with the original extension
            switch ($originalExtension) {
                case 'jpeg':
                case 'jpg':
                    imagejpeg($resizedImage, $modifiedImage);
                    break;
                case 'png':
                    imagepng($resizedImage, $modifiedImage);
                    break;
                case 'gif':
                    imagegif($resizedImage, $modifiedImage);
                    break;
            }

            imagedestroy($resizedImage);
            imagedestroy($image);
            return basename($modifiedImage);
        }

        throw new InvalidParameterException('Invalid resize dimensions.');
    }
}
