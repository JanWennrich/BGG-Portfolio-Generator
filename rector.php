<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/bin',
        __DIR__ . '/src',
        __DIR__ . '/templates',
    ])
    ->withPhpSets(php83: true) // upto PHP 8.3
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        typeDeclarationDocblocks: true,
        privatization: true,
        instanceOf: true,
        earlyReturn: true,
    )
    ->withComposerBased(twig: true)
    ->withFluentCallNewLine()
    ->withPHPStanConfigs([__DIR__ . '/phpstan.dist.neon'])
    ->withSkip(
        [
            ClosureToArrowFunctionRector::class => [
                // Using arrow functions degrades readability when constructing large objects
                __DIR__ . '/bin/bgg-portfolio-generator.php',
            ],
        ],
    );
