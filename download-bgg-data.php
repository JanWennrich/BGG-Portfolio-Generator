<?php

use Crell\Serde\SerdeCommon;
use JanWennrich\BoardGames\OwnedBoardgamesLoader;
use JanWennrich\BoardGames\PlayedBoardgamesLoader;
use JanWennrich\BoardGames\WishlistedBoardgamesLoader;
use JanWennrich\BoardGames\WishlistedBoardgamesLoaderInterface;

require_once __DIR__ . '/vendor/autoload.php';

$containerConfig = require_once __DIR__ . "/container-config.php";

$diContainer = new \DI\Container($containerConfig);

$bggUsername = $diContainer->get('bgg.username');

$wishlistedBoardgames = $diContainer->get(WishlistedBoardgamesLoaderInterface::class)->getForUser($bggUsername);
if (!$wishlistedBoardgames->isEmpty()) {
    $wishlistedBoardgamesSerialized = serialize($wishlistedBoardgames);
    $wishlistedBoardgamesSerializationPath = $diContainer->get('serialization.path.boardgames.wishlisted');
    file_put_contents($wishlistedBoardgamesSerializationPath, $wishlistedBoardgamesSerialized);
}

$playedBoardgames = $diContainer->get(PlayedBoardgamesLoader::class)->getForUser($bggUsername);
if (!$playedBoardgames->isEmpty()) {
    $playedBoardgamesSerialized = serialize($playedBoardgames);
    $playedBoardgamesSerializationPath = $diContainer->get('serialization.path.boardgames.played');
    file_put_contents($playedBoardgamesSerializationPath, $playedBoardgamesSerialized);
}

$ownedBoardgames = $diContainer->get(OwnedBoardgamesLoader::class)->getForUser($bggUsername);
if (!$ownedBoardgames->isEmpty()) {
    $ownedBoardgamesSerialized = serialize($ownedBoardgames);
    $ownedBoardgamesSerializationPath = $diContainer->get('serialization.path.boardgames.owned');
    file_put_contents($ownedBoardgamesSerializationPath, $ownedBoardgamesSerialized);
}
