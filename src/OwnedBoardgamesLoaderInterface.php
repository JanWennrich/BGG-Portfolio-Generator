<?php

declare(strict_types=1);

namespace JanWennrich\BoardGames;

interface OwnedBoardgamesLoaderInterface
{
    /**
     * @param non-empty-string $bggUsername
     */
    public function getForUser(string $bggUsername): BoardgameCollection;
}
