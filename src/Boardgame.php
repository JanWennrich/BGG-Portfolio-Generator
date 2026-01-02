<?php

namespace JanWennrich\BoardGames;

class Boardgame
{
    public function __construct(
        public string $title,
        public int $bggId,
        public ?string $thumbnailUrl = null
    ) {
    }

    public function getBggUrl(): string
    {
        return sprintf('https://boardgamegeek.com/boardgame/%s', $this->bggId);
    }
}
