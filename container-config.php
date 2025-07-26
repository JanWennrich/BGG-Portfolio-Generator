<?php

use JanWennrich\BoardGames\OwnedBoardgamesLoader;
use JanWennrich\BoardGames\OwnedBoardgamesLoaderInterface;
use JanWennrich\BoardGames\PlayedBoardgamesLoader;
use JanWennrich\BoardGames\PlayedBoardgamesLoaderInterface;
use JanWennrich\BoardGames\WishlistedBoardgamesLoader;
use JanWennrich\BoardGames\WishlistedBoardgamesLoaderInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once __DIR__ . "/vendor/autoload.php";

return [
    'bgg.username' => \DI\env('BGG_USERNAME'),
    'serialization.path.boardgames.played' => 'assets/boardgames-played.serialized.txt',
    'serialization.path.boardgames.owned' => 'assets/boardgames-owned.serialized.txt',
    'serialization.path.boardgames.wishlisted' => 'assets/boardgames-wishlisted.serialized.txt',
    Environment::class => \DI\factory(function () {
        return new Environment(new FilesystemLoader(__DIR__ . '/templates'));
    }),
    PlayedBoardgamesLoaderInterface::class => \DI\get(PlayedBoardgamesLoader::class),
    OwnedBoardgamesLoaderInterface::class => \DI\get(OwnedBoardgamesLoader::class),
    WishlistedBoardgamesLoaderInterface::class => \DI\get(WishlistedBoardgamesLoader::class),
];
