#!/usr/bin/env php
<?php

require_once $_composer_autoload_path ?? __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Psr7\HttpFactory;
use JanWennrich\BoardGameGeekApi\Client;
use JanWennrich\BoardGames\Command\GeneratorCommand;
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
    Environment::class => \DI\factory(
        fn(): Environment => new Environment(new FilesystemLoader(__DIR__ . '/../templates'))
    ),
    HtmlGeneratorInterface::class => \DI\get(HtmlGenerator::class),
    PlayedBoardgamesLoaderInterface::class => \DI\get(PlayedBoardgamesLoader::class),
    OwnedBoardgamesLoaderInterface::class => \DI\get(OwnedBoardgamesLoader::class),
    WishlistedBoardgamesLoaderInterface::class => \DI\get(WishlistedBoardgamesLoader::class),
    \GuzzleHttp\Client::class => static function (CookieJar $cookieJar): \GuzzleHttp\Client {
        return new \GuzzleHttp\Client(['cookies' => $cookieJar]);
    },
    Client::class => static function (\GuzzleHttp\Client $guzzleClient, HttpFactory $httpFactory): Client {
        return new Client($guzzleClient, $httpFactory, $httpFactory);
    },
]);

/** @var GeneratorCommand $generatorCommand */
$generatorCommand = $diContainer->get(GeneratorCommand::class);
$application->addCommand($generatorCommand);
$application->setDefaultCommand($generatorCommand->getName() ?? 'generate', true);

$application->run();
