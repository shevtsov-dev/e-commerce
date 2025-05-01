<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\ValueObject\PhpVersion;
use RectorLaravel\Set\LaravelSetList;

return static function (RectorConfig $config): void {
    $config->paths([
        __DIR__,
    ]);

    $config->skip([
        __DIR__ . '/vendor/*',
    ]);

    $config->phpVersion(PhpVersion::PHP_82);

    $config->sets([
        LevelSetList::UP_TO_PHP_82,
        LaravelSetList::LARAVEL_120,
    ]);
};
