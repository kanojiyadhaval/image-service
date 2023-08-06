<?php
declare(strict_types=1);

namespace ImageService\Service;

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

        // Extract additional parameters from the URL
        $additionalParams = [];
        foreach ($urlParts as $index => $part) {
            if ($index < 3) {
                continue; // Skip the first three parts (modifier, image, and modifier value)
            }
            if (strpos($part, '=') !== false) {
                list($key, $value) = explode('=', $part, 2);
                $additionalParams[$key] = $value;
            } else {
                $additionalParams[] = $part;
            }
        }

        if (!empty($additionalParams)) {
            $params[$urlParts[0]] .= '?' . implode('&', $additionalParams);
        }

        return $params;
    }


}
