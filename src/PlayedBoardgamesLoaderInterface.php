<?php

namespace JanWennrich\BoardGames;

interface PlayedBoardgamesLoaderInterface
{
    public function getForUser(string $bggUsername): PlayCollection;

    public function getForUserWithoutApiToken(string $bggUsername): PlayCollection;
}
