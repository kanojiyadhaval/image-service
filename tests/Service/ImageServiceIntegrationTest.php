<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use ImageService\Service\ImageService;
use ImageService\Modifier\CropModifier;
use ImageService\Modifier\ResizeModifier;
use ImageService\Service\FileSystem\LocalFileSystem;

class ImageServiceIntegrationTest extends TestCase
{
    private ImageService $imageService;
    private string $testImagePath;
    private string $testModifiedPath;

    protected function setUp(): void
    {
        $this->testImagePath = realpath(__DIR__ . '/../test_images') . '/';
        $this->testModifiedPath = $this->testImagePath . 'modified/';

        // Create an instance of LocalFileSystem
        $fileSystem = new LocalFileSystem();

        // Create an instance of ImageService with the base path and LocalFileSystem
        $this->imageService = new ImageService($this->testImagePath, $fileSystem);

        // Add the supported modifiers to ImageService
        $this->imageService->addModifier(new CropModifier($fileSystem));
        $this->imageService->addModifier(new ResizeModifier($fileSystem));
    }

    public function testCropImage()
    {
        // Prepare test image and modified image paths
        $originalImagePath = $this->testImagePath . 'image.png';

        // Set the resize parameters
        $params = ['image' => 'image.png', 'crop' => '300x300'];

        // Handle the request
        $modifiedImage = $this->imageService->handleRequest($params);

        // Assert that the modified image is not null
        $this->assertNotNull($modifiedImage);

        // Construct the path to the modified image file
        $modifiedImagePath = $this->testModifiedPath . $modifiedImage;

        // Assert that the modified image file exists in the modified directory
        $this->assertFileExists($modifiedImagePath);
    }

    public function testResizeImage()
    {
        // Prepare test image and modified image paths
        $originalImagePath = $this->testImagePath . 'image.png';

        // Set the resize parameters
        $params = ['image' => 'image.png', 'resize' => '300x300'];

        // Handle the request
        $modifiedImage = $this->imageService->handleRequest($params);

        // Assert that the modified image is not null
        $this->assertNotNull($modifiedImage);

        // Construct the path to the modified image file
        $modifiedImagePath = $this->testModifiedPath . $modifiedImage;

        // Assert that the modified image file exists in the modified directory
        $this->assertFileExists($modifiedImagePath);
    }
}
