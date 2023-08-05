<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use ImageService\ImageService;
use ImageModifier\CropModifier;
use ImageModifier\ResizeModifier;

class ImageServiceTest extends TestCase
{
    public function testCropModifier(): void
    {
        // Test the crop-modifier.
        $imageService = new ImageService(__DIR__ . '/../images');
        $imageService->addModifier(new CropModifier());

        // Test image crop with valid parameters.
        $params = ['image' => 'example.jpg', 'crop' => '200x200'];
        $this->expectOutputRegex('/^http:\/\/localhost\/images\/modified\/crop_/');
        $imageService->handleRequest($params);

        // Test image crop with invalid parameters.
        $params = ['image' => 'example.jpg', 'crop' => 'invalid_size'];
        $this->expectOutputRegex('/^Error: Invalid modifier or parameters./');
        $imageService->handleRequest($params);

        // Test image crop with missing parameters.
        $params = ['image' => 'example.jpg'];
        $this->expectOutputRegex('/^Error: Invalid modifier or parameters./');
        $imageService->handleRequest($params);
    }

    public function testResizeModifier(): void
    {
        // Test the resize-modifier.
        $imageService = new ImageService(__DIR__ . '/../images');
        $imageService->addModifier(new ResizeModifier());

        // Test image resize with valid parameters.
        $params = ['image' => 'example.jpg', 'resize' => '200x200'];
        $this->expectOutputRegex('/^http:\/\/localhost\/images\/modified\/resize_/');
        $imageService->handleRequest($params);

        // Test image resize with invalid parameters.
        $params = ['image' => 'example.jpg', 'resize' => 'invalid_size'];
        $this->expectOutputRegex('/^Error: Invalid modifier or parameters./');
        $imageService->handleRequest($params);

        // Test image resize with missing parameters.
        $params = ['image' => 'example.jpg'];
        $this->expectOutputRegex('/^Error: Invalid modifier or parameters./');
        $imageService->handleRequest($params);
    }

    // Add more test cases for other modifiers if needed.
}
