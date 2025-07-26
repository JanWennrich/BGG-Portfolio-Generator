#!/usr/bin/env php
<?php

require_once $_composer_autoload_path ?? __DIR__ . '/../vendor/autoload.php';

use JanWennrich\BoardGames\HtmlGenerator;
use JanWennrich\BoardGames\HtmlGeneratorInterface;
use JanWennrich\BoardGames\OwnedBoardgamesLoader;
use JanWennrich\BoardGames\OwnedBoardgamesLoaderInterface;
use JanWennrich\BoardGames\PlayedBoardgamesLoader;
use JanWennrich\BoardGames\PlayedBoardgamesLoaderInterface;
use JanWennrich\BoardGames\WishlistedBoardgamesLoader;
use JanWennrich\BoardGames\WishlistedBoardgamesLoaderInterface;
use Symfony\Component\Console\Application;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$application = new Application();

$diContainer = new \DI\Container([
    Environment::class => \DI\factory(function () {
        return new Environment(new FilesystemLoader(__DIR__ . '/../templates'));
    }),
    HtmlGeneratorInterface::class => \DI\get(HtmlGenerator::class),
    PlayedBoardgamesLoaderInterface::class => \DI\get(PlayedBoardgamesLoader::class),
    OwnedBoardgamesLoaderInterface::class => \DI\get(OwnedBoardgamesLoader::class),
    WishlistedBoardgamesLoaderInterface::class => \DI\get(WishlistedBoardgamesLoader::class),
]);

$generatorCommand = $diContainer->get(\JanWennrich\BoardGames\Command\GeneratorCommand::class);
$application->add($generatorCommand);
$application->setDefaultCommand($generatorCommand->getName(), true);

$application->run();
