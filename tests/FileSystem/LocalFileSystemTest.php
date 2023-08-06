<?php
declare(strict_types=1);

namespace ImageService\Service\FileSystem;

use PHPUnit\Framework\TestCase;

class LocalFileSystemTest extends TestCase
{
    private $testImagePath;
    private $testModifiedPath;

    protected function setUp(): void
    {
        $this->testImagePath = realpath(__DIR__ . '/../test_images') . '/';
        $this->testModifiedPath = $this->testImagePath . 'modified/';
    }

    public function testFileExists()
    {
        $fileSystem = new LocalFileSystem();

        // Test if the file exists
        $existingFilePath = $this->testImagePath . 'image.png';
        $this->assertTrue($fileSystem->fileExists($existingFilePath));

        // Test if the file does not exist
        $nonExistingFilePath = $this->testImagePath . 'non_existing_image.jpg';
        $this->assertFalse($fileSystem->fileExists($nonExistingFilePath));
    }

    public function testGetFileContents()
    {
        $fileSystem = new LocalFileSystem();
        
        // Test getting the contents of an existing file
        $existingFilePath = $this->testImagePath . 'image.png';
        $expectedContents = file_get_contents($existingFilePath);
        $this->assertEquals($expectedContents, $fileSystem->getFileContents($existingFilePath));

        // Test getting the contents of a non-existing file
        $nonExistingFilePath = $this->testImagePath . 'non_existing_image.jpg';
        $this->expectException(\InvalidArgumentException::class);
        $fileSystem->getFileContents($nonExistingFilePath);
    }
}
