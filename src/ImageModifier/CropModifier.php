<?php
declare(strict_types=1);

namespace ImageModifier;

class CropModifier implements ImageModifierInterface
{
    private array $supportedFormats = [
        'jpeg' => 'imagecreatefromjpeg',
        'jpg'  => 'imagecreatefromjpeg',
        'png'  => 'imagecreatefrompng',
        'gif'  => 'imagecreatefromgif',
    ];

    public function supports(array $params): bool
    {
        return isset($params['crop']);
    }

    public function modifyImage(string $imagePath, array $params): ?string
    {
        $cropSize = explode('x', $params['crop']);
        if (count($cropSize) === 2) {
            list($width, $height) = $cropSize;
            $width = intval($width); // Convert to integer
            $height = intval($height); // Convert to integer

            // Get the original image extension
            $originalExtension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

            // Check if the image format is supported
            if (!isset($this->supportedFormats[$originalExtension])) {
                return null; // Unsupported image format
            }

            // Create the image based on the original extension
            $imageFunction = $this->supportedFormats[$originalExtension];
            $image = $imageFunction($imagePath);

            $croppedImage = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => $width, 'height' => $height]);

            if ($croppedImage !== false) {
                // Generate the modified image path with the same directory as the original image
                $imageDirectory = dirname($imagePath);
                $modifiedImage = $imageDirectory . '/modified/' . uniqid('crop_', true) . "_{$width}x{$height}." . $originalExtension;

                // Save the modified image with the original extension
                switch ($originalExtension) {
                    case 'jpeg':
                    case 'jpg':
                        imagejpeg($croppedImage, $modifiedImage);
                        break;
                    case 'png':
                        imagepng($croppedImage, $modifiedImage);
                        break;
                    case 'gif':
                        imagegif($croppedImage, $modifiedImage);
                        break;
                }

                imagedestroy($croppedImage);
                imagedestroy($image);
                return basename($modifiedImage);
            }

            imagedestroy($image);
        }

        return null;
    }
}
