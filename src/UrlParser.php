<?php
declare(strict_types=1);

namespace ImageService;

class UrlParser
{
    public function parse(string $url): array
    {
        $urlParts = explode('/', trim($url, '/'));
        $params = [];

        // Extract the image name from the URL.
        if (count($urlParts) >= 2) {
            $params['image'] = $urlParts[1];
        }

        // Extract the crop or resize modifier from the URL.
        if (count($urlParts) >= 3) {
            $modifierParts = explode('x', $urlParts[2]);
            if (count($modifierParts) === 2) {
                $params[$urlParts[0]] = $urlParts[2]; // Simple <modifier> => <width>x<height> format
            }
        }

        return $params;
    }
}
