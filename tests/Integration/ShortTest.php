<?php

namespace Surface\CommonMark\Ext\YouTubeIframe\Tests\Integration;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use Surface\CommonMark\Ext\YouTubeIframe\Extension;
use Surface\CommonMark\Ext\YouTubeIframe\Tests\TestCase;

/** @group full */
final class ShortTest extends TestCase
{
    protected string $type;
    protected string $uuid;
    protected string $url;
    protected string $embedUrl;

    /** @test */
    public function urlConversion(): void
    {
        $markdown = "[]({$this->url})";

        $environment = Environment::createCommonMarkEnvironment();
        $environment->addExtension(new Extension());
        $converter = new CommonMarkConverter([], $environment);

        $html = $converter->convertToHtml($markdown);

        $this->assertStringContainsString($this->getHtml($this->embedUrl), $html);
    }

    /** @test */
    public function urlConversionWithTimestamp(): void
    {
        $url = "{$this->url}?t=10";
        $embedUrl = "{$this->embedUrl}?start=10";
        $markdown = "[]({$url})";

        $environment = Environment::createCommonMarkEnvironment();
        $environment->addExtension(new Extension());
        $converter = new CommonMarkConverter([], $environment);

        $html = $converter->convertToHtml($markdown);

        $this->assertStringContainsString($this->getHtml($embedUrl), $html);
    }

    /** @test */
    public function urlConversionWithWidth(): void
    {
        $url = "{$this->url}?width=640";
        $markdown = "[]({$url})";

        $environment = Environment::createCommonMarkEnvironment();
        $environment->addExtension(new Extension());
        $converter = new CommonMarkConverter([], $environment);

        $html = $converter->convertToHtml($markdown);

        $this->assertStringContainsString($this->getHtml($this->embedUrl, 640, 600), $html);
    }

    /** @test */
    public function urlConversionWithHeight(): void
    {
        $url = "{$this->url}?height=480";
        $markdown = "[]({$url})";

        $environment = Environment::createCommonMarkEnvironment();
        $environment->addExtension(new Extension());
        $converter = new CommonMarkConverter([], $environment);

        $html = $converter->convertToHtml($markdown);

        $this->assertStringContainsString($this->getHtml($this->embedUrl, 800, 480), $html);
    }

    /** @test */
    public function urlConversionWithWidthAndHeight(): void
    {
        $url = "{$this->url}?width=640&height=480";
        $markdown = "[]({$url})";

        $environment = Environment::createCommonMarkEnvironment();
        $environment->addExtension(new Extension());
        $converter = new CommonMarkConverter([], $environment);

        $html = $converter->convertToHtml($markdown);

        $this->assertStringContainsString($this->getHtml($this->embedUrl, 640, 480), $html);
    }

    /** @test */
    public function urlConversionWithWidthAndHeightTimestamp(): void
    {
        $url = "{$this->url}?width=640&height=480&t=15";
        $embedUrl = "{$this->embedUrl}?start=15";
        $markdown = "[]({$url})";

        $environment = Environment::createCommonMarkEnvironment();
        $environment->addExtension(new Extension());
        $converter = new CommonMarkConverter([], $environment);

        $html = $converter->convertToHtml($markdown);

        $this->assertStringContainsString($this->getHtml($embedUrl, 640, 480), $html);
    }

    protected function setUp(): void
    {
        $this->uuid = uniqid();
        $this->url = "https://youtu.be/{$this->uuid}";
        $this->embedUrl = "https://www.youtube.com/embed/{$this->uuid}";
    }
}
