<?php

namespace JanWennrich\BoardGames;

class Play
{
    public function __construct(
        public Boardgame $boardgame,
        public \DateTimeInterface $playDateTime
    ) {
    }
}
