<?php

declare(strict_types=1);

// Set the HTTP_HOST manually for the test
$_SERVER['HTTP_HOST'] = 'localhost';

// Define the paths to the test images
$testImagePath = realpath(__DIR__ . '/test_images') . '/';
$testModifiedPath = $testImagePath . 'modified/';

// Create the modified directory if it doesn't exist
if (!is_dir($testModifiedPath)) {
    mkdir($testModifiedPath);
}

// Set up autoloading and other configurations if needed
require_once __DIR__ . '/../vendor/autoload.php';


// Define any other configurations or setups for the tests if needed
function cleanupTestImages()
{
    global $testModifiedPath;

    // Remove the modified directory and all its contents (subdirectories and files)
    array_map('unlink', glob($testModifiedPath . '/*'));
    rmdir($testModifiedPath);
}

// Register the cleanup function to be called after the test suite finishes
register_shutdown_function('cleanupTestImages');
