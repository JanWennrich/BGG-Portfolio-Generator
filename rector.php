<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\Config\RectorConfig;
use Rector\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector;
use Rector\Naming\Rector\Class_\RenamePropertyToMatchTypeRector;
use Rector\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector;
use Rector\Naming\Rector\Foreach_\RenameForeachValueVariableToMatchExprVariableRector;
use Rector\Php80\Rector\NotIdentical\MbStrContainsRector;
use Rector\Php82\Rector\Param\AddSensitiveParameterAttributeRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/bin',
        __DIR__ . '/src',
        __DIR__ . '/templates',
    ])
    ->withRules([
        AddSensitiveParameterAttributeRector::class,
        MbStrContainsRector::class,
    ])
    ->withPhpSets(php83: true) // upto PHP 8.3
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        typeDeclarationDocblocks: true,
        privatization: true,
        naming: true,
        instanceOf: true,
        earlyReturn: true,
    )
    ->withComposerBased(twig: true)
    ->withFluentCallNewLine()
    ->withPHPStanConfigs([__DIR__ . '/phpstan.dist.neon'])
    ->withSkip(
        [
            EncapsedStringsToSprintfRector::class,
            RenameForeachValueVariableToMatchExprVariableRector::class,
            RenamePropertyToMatchTypeRector::class,
            RenameParamToMatchTypeRector::class,
            RenameVariableToMatchMethodCallReturnTypeRector::class,
        ],
    );
