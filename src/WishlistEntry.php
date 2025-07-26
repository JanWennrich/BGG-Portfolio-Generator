<?php

namespace JanWennrich\BoardGames;

class WishlistEntry
{
    public function __construct(
        public Boardgame $boardgame,
        public int $wantLevel,
        public \DateTimeImmutable $lastModified
    ) {
    }
}
