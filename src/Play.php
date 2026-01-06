<?php

namespace JanWennrich\BoardGames;

final readonly class Play
{
    public function __construct(
        public Boardgame $boardgame,
        public \DateTimeInterface $playDateTime
    ) {}
}
