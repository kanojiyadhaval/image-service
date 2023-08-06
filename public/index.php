<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use ImageService\Service\ImageService;
use ImageService\Modifier\CropModifier;
use ImageService\Modifier\ResizeModifier;
use ImageService\Service\UrlParser;
use ImageService\Service\FileSystem\LocalFileSystem;
use ImageService\ErrorHandling\ImageNotFoundException;
use ImageService\ErrorHandling\InvalidParameterException;

// Define constants for HTTP response codes
const HTTP_OK = 200;
const HTTP_BAD_REQUEST = 400;
const HTTP_INTERNAL_SERVER_ERROR = 500;

// Get the absolute path to the images directory.
$basePath = realpath(__DIR__ . '/../images');

// Create an instance of the LocalFileSystem class
$fileSystem = new LocalFileSystem();

$imageService = new ImageService($basePath);
$imageService->addModifier(new CropModifier($fileSystem));
$imageService->addModifier(new ResizeModifier($fileSystem));

// Parse the URL to extract the image and modifiers.
$url = $_SERVER['REQUEST_URI'];
$urlParser = new UrlParser();
$params = $urlParser->parse($url);

try {
    // Get the modified image name from the ImageService
    $modifiedImageName = $imageService->handleRequest($params);

    if ($modifiedImageName) {
        // Sanitize the modified image URL before echoing it in the HTML
        $modifiedImageUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/display_image.php?modifiedImage=' . urlencode($modifiedImageName);
        header("Location: $modifiedImageUrl");
    } else {
        // Otherwise, handle the error based on the ImageServiceException type
        http_response_code(HTTP_BAD_REQUEST);
        echo json_encode(['error' => 'Failed to process the image']);
    }
} catch (ImageNotFoundException $exception) {
    http_response_code(HTTP_BAD_REQUEST);
    echo json_encode(['error' => $exception->getMessage()]);
} catch (InvalidParameterException $exception) {
    http_response_code(HTTP_BAD_REQUEST);
    echo json_encode(['error' => $exception->getMessage()]);
} catch (\Throwable $exception) {
    http_response_code(HTTP_INTERNAL_SERVER_ERROR);
    echo 'Internal Server Error';
}
