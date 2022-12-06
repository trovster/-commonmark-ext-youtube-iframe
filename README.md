# YouTube iframe extension

An extension for [league/commonmark](https://github.com/thephpleague/commonmark)
version 2 built using PHP 8.0. This replaces YouTube links with the embed iframe.

The extension supports for the primary YouTube URL, with and without prefixed with
the `www`. It also supports the short shareable URL using the `youtu.be` domain.

Initially based on the [YouTube extension](https://github.com/zoonru/commonmark-ext-youtube-iframe).

## Installation

The project should be installed via Composer:

```bash
composer require surface/commonmark-ext-youtube-iframe
```

## Usage

Configure your CommonMark `Environment` and add the extension.

```php
use League\CommonMark\Environment\Environment;
use League\CommonMark\MarkdownConverter as Converter;
use Surface\CommonMark\Ext\YouTubeIframe\Extension as YouTubeExtension;

$options = [
    'youtube_iframe' => [
        'width' => 800,
        'height' => 600,
    ]
];

$environment = new Environment($options);
$environment->addExtension(new YouTubeExtension());

$converter = new Converter($environment);

echo $converter->convert('[](https://youtu.be/xxx)');
echo $converter->convert('[](https://www.youtube.com/watch?v=xxx)');
echo $converter->convert('[](https://youtu.be/xxx?height=480)');
echo $converter->convert('[](https://www.youtube.com/watch?v=xxx&width=640)');
```

### Dimensions

You can control the dimensions of the videos by using the `width` and
`height` configuration options.

You can also configure the dimensions using query parameters on the embed URL.
You can provide the `height` or `width` or *both*.

```html
?width=640
?height=480
?width=640&height=480
```

### Full URL

You can disable the parsing of full YouTube URLs (with or without the www) by
using the following option. If this option is disabled, only URLs under the
‘short’ domain will be used (`youtu.be`).

```php
$options = [
    'youtube_iframe' => [
        'full_url' => false,
    ]
];
```

## Testing

There are Unit and Integration tests for the project. These can be run using
the following commands:

```bash
composer test
composer run test
composer run test-unit
composer run test-integration
```

There are also scripts to run code sniffer, mess detector and static analysis:

```bash
composer run sniff
composer run mess
composer run stan
```

## Changelog

Please refer to the [CHANGELOG](CHANGELOG.md) for more information on what has
changed recently.

## License

This library is licensed under the MIT license. See the
[License File](LICENSE.md) for more information.
