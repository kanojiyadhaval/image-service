<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use ImageService\Modifier\CropModifier;
use ImageService\Service\FileSystem\LocalFileSystem;
use ImageService\Service\ErrorHandling\InvalidParameterException;

class CropModifierTest extends TestCase
{
    private string $testImagePath;
    private string $testModifiedPath;

    protected function setUp(): void
    {
        $this->testImagePath = realpath(__DIR__ . '/../test_images') . '/';
        $this->testModifiedPath = $this->testImagePath . 'modified/';
    }

    public function testCropImage()
    {
        $fileSystem = new LocalFileSystem();
        $cropModifier = new CropModifier($fileSystem);

        // Prepare test image and modified image paths
        $originalImagePath = $this->testImagePath . 'image.png';
        $modifiedImageFilename = 'modified_image.jpg';
        $modifiedImagePath = $this->testModifiedPath . $modifiedImageFilename;

        // Crop the test image and save it to the modified directory
        $params = ['crop' => '200x200'];
        $modifiedImage = $cropModifier->process($originalImagePath, $params);

        // Assert that the modified image file exists in the modified directory
        $this->assertFileExists($this->testModifiedPath . $modifiedImage);
    }

    public function testInvalidCropDimensions()
    {
        $this->expectException(InvalidParameterException::class);

        $fileSystem = new LocalFileSystem();
        $cropModifier = new CropModifier($fileSystem);

        // Prepare test image and modified image paths
        $originalImagePath = $this->testImagePath . 'image.png';

        // Try to crop the test image with invalid dimensions
        $params = ['crop' => 'invalid_dimensions'];
        $cropModifier->process($originalImagePath, $params);
    }
}
