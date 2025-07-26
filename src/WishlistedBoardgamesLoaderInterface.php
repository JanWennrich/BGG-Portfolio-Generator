<?php

namespace JanWennrich\BoardGames;

interface WishlistedBoardgamesLoaderInterface
{
    public function getForUser(string $bggUsername): WishlistEntryCollection;
}
