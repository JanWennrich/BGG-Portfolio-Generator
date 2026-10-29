<?php

declare(strict_types=1);

namespace JanWennrich\BoardGames;

final readonly class Play
{
    public function __construct(
        public Boardgame $boardgame,
        public \DateTimeInterface $playDateTime
    ) {}
}
