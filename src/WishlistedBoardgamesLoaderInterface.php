<?php

declare(strict_types=1);

namespace JanWennrich\BoardGames;

interface WishlistedBoardgamesLoaderInterface
{
    /**
     * @param non-empty-string $bggUsername
     */
    public function getForUser(string $bggUsername): WishlistEntryCollection;
}
