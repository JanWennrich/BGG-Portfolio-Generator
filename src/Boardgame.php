<?php

namespace JanWennrich\BoardGames;

class Boardgame
{
    public function __construct(
        public string $title,
        public string $thumbnailUrl,
        public int $bggId
    ) {
    }

    public function getBggUrl(): string
    {
        return sprintf('https://boardgamegeek.com/boardgame/%s', $this->bggId);
    }
}
