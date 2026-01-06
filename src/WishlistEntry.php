<?php

namespace JanWennrich\BoardGames;

final readonly class WishlistEntry
{
    public function __construct(
        public Boardgame $boardgame,
        public int $wantLevel,
        public \DateTimeImmutable $lastModified
    ) {
    }
}
