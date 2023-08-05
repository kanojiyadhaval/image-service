<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use ImageService\ImageService;
use ImageModifier\CropModifier;
use ImageModifier\ResizeModifier;
use ImageService\UrlParser;

// Get the absolute path to the images directory.
$basePath = realpath(__DIR__ . '/../images');

$imageService = new ImageService($basePath);
$imageService->addModifier(new CropModifier());
$imageService->addModifier(new ResizeModifier());

// Parse the URL to extract the image and modifiers.
$url = $_SERVER['REQUEST_URI'];
$urlParser = new UrlParser();
$params = $urlParser->parse($url);
$imageService->handleRequest($params);
