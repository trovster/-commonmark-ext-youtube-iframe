<?php

namespace Surface\CommonMark\Ext\YouTubeIframe;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\ConfigurableExtensionInterface;
use League\CommonMark\Extension\ExtensionInterface;
use League\Config\ConfigurationBuilderInterface;
use Nette\Schema\Expect;
use Surface\CommonMark\Ext\YouTubeIframe\Iframe;
use Surface\CommonMark\Ext\YouTubeIframe\Processor;
use Surface\CommonMark\Ext\YouTubeIframe\Renderer;
use Surface\CommonMark\Ext\YouTubeIframe\Url\Parsers\Full;
use Surface\CommonMark\Ext\YouTubeIframe\Url\Parsers\Short;
use Surface\CommonMark\Ext\YouTubeIframe\Url\Parsers\WithoutWww;

final class Extension implements ExtensionInterface, ConfigurableExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $width = $this->getWidth($environment);
        $height = $this->getHeight($environment);
        $fullScreen = $this->allowFullscreen($environment);
        $processors = $this->getProcessors($environment, $width, $height);

        $environment
            ->addEventListener(DocumentParsedEvent::class, new Processor($processors))
            ->addRenderer(Iframe::class, new Renderer(
                $width,
                $height,
                $fullScreen
            ));
    }

    public function configureSchema(ConfigurationBuilderInterface $builder): void
    {
        $builder->addSchema('youtube_iframe', Expect::structure([
            'width' => Expect::int(800),
            'height' => Expect::int(600),
            'allowfullscreen' => Expect::bool(true),
            'full_url' => Expect::bool(true),
        ]));
    }

    protected function getProcessors(EnvironmentBuilderInterface $environment, int $width, int $height): array
    {
        if (! $this->supportFullUrl($environment)) {
            return [
                new Short($width, $height),
            ];
        }

        return [
            new Short($width, $height),
            new Full($width, $height),
            new WithoutWww($width, $height),
        ];
    }

    protected function supportFullUrl(EnvironmentBuilderInterface $environment): bool
    {
        if ($environment->getConfiguration()->exists('youtube_iframe.full_url')) {
            return (bool) $environment->getConfiguration()->get('youtube_iframe.full_url');
        }

        return true;
    }

    protected function getWidth(EnvironmentBuilderInterface $environment): int
    {
        if ($environment->getConfiguration()->exists('youtube_iframe.width')) {
            return (int) $environment->getConfiguration()->get('youtube_iframe.width');
        }

        return 800;
    }

    protected function getHeight(EnvironmentBuilderInterface $environment): int
    {
        if ($environment->getConfiguration()->exists('youtube_iframe.height')) {
            return (int) $environment->getConfiguration()->get('youtube_iframe.height');
        }

        return 600;
    }

    protected function allowFullscreen(EnvironmentBuilderInterface $environment): bool
    {
        if ($environment->getConfiguration()->exists('youtube_iframe.allowfullscreen')) {
            return (bool) $environment->getConfiguration()->get('youtube_iframe.allowfullscreen');
        }

        return true;
    }
}
