<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use ImageService\Service\UrlParser;

class UrlParserTest extends TestCase
{
    private UrlParser $urlParser;

    protected function setUp(): void
    {
        $this->urlParser = new UrlParser();
    }

    public function testParseCrop()
    {
        $url = '/crop/image.png/300x200';
        $params = $this->urlParser->parse($url);

        $this->assertEquals(['image' => 'image.png', 'crop' => '300x200'], $params);
    }

    public function testParseResize()
    {
        $url = '/resize/image.png/300x200';
        $params = $this->urlParser->parse($url);

        $this->assertEquals(['image' => 'image.png', 'resize' => '300x200'], $params);
    }

    public function testParseWithAdditionalParameters()
    {
        $url = '/crop/image.png/300x200?param1=value1&param2=value2';
        $params = $this->urlParser->parse($url);

        $this->assertEquals(['image' => 'image.png', 'crop' => '300x200?param1=value1&param2=value2'], $params);
    }

    public function testParseWithInvalidUrl()
    {
        $url = '/crop/image.png/300x200?param1=value1&param2=value2&invalid';
        $params = $this->urlParser->parse($url);

        $this->assertEquals([
            'image' => 'image.png',
            'crop' => '300x200?param1=value1&param2=value2&invalid'
        ], $params);
    }


    public function testParseWithInvalidModifierFormat()
    {
        $url = '/custom_modifier/image.png/200x';
        $params = $this->urlParser->parse($url);

        $this->assertEquals(['image' => 'image.png', 'custom_modifier' => '200x'], $params);
    }

    public function testParseWithInvalidImageFormat()
    {
        $url = '/crop/image.webp/200x200';
        $params = $this->urlParser->parse($url);

        $this->assertEquals(['image' => 'image.webp', 'crop' => '200x200'], $params);
    }
}
