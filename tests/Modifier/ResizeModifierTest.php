<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use ImageService\Modifier\ResizeModifier;
use ImageService\Service\FileSystem\LocalFileSystem;
use ImageService\Service\ErrorHandling\InvalidParameterException;

class ResizeModifierTest extends TestCase
{
    private string $testImagePath;
    private string $testModifiedPath;

    protected function setUp(): void
    {
        $this->testImagePath = realpath(__DIR__ . '/../test_images') . '/';
        $this->testModifiedPath = $this->testImagePath . 'modified/';
    }

    public function testResizeImage()
    {
        $fileSystem = new LocalFileSystem();
        $resizeModifier = new ResizeModifier($fileSystem);

        // Prepare test image and modified image paths
        $originalImagePath = $this->testImagePath . 'image.png';
        $modifiedImageFilename = 'modified_image.jpg';
        $modifiedImagePath = $this->testModifiedPath . $modifiedImageFilename;

        // Resize the test image and save it to the modified directory
        $params = ['resize' => '200x200'];
        $modifiedImage = $resizeModifier->process($originalImagePath, $params);

        // Assert that the modified image file exists in the modified directory
        $this->assertFileExists($this->testModifiedPath . $modifiedImage);
    }

    public function testInvalidResizeDimensions()
    {
        $this->expectException(InvalidParameterException::class);

        $fileSystem = new LocalFileSystem();
        $resizeModifier = new ResizeModifier($fileSystem);

        // Prepare test image and modified image paths
        $originalImagePath = $this->testImagePath . 'image.png';

        // Try to resize the test image with invalid dimensions
        $params = ['resize' => 'invalid_dimensions'];
        $resizeModifier->process($originalImagePath, $params);
    }
}
