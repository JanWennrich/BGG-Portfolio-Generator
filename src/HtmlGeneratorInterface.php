<?php

declare(strict_types=1);

namespace JanWennrich\BoardGames;

interface HtmlGeneratorInterface
{
    public function generateHtml(
        BoardgameCollection $boardgamesOwned,
        PlayCollection $boardgamesPlayed,
        WishlistEntryCollection $wishlistedBoardgames,
        string $bggUsername,
    ): string;
}