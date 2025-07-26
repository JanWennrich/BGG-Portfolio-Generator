<?php

namespace JanWennrich\BoardGames;

interface OwnedBoardgamesLoaderInterface
{
    public function getForUser(string $bggUsername): BoardgameCollection;
}
