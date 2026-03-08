<?php

namespace JanWennrich\BoardGames;

interface PlayedBoardgamesLoaderInterface
{
    /**
     * @param non-empty-string $bggUsername
     */
    public function getForUser(string $bggUsername): PlayCollection;

    /**
     * @param non-empty-string $bggUsername
     */
    public function getForUserWithoutApiToken(string $bggUsername): PlayCollection;
}
