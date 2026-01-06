<?php

namespace JanWennrich\BoardGames;

use Ramsey\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<Boardgame>
 */
final class BoardgameCollection extends AbstractCollection
{
    /**
     * @return class-string
     */
    public function getType(): string
    {
        return Boardgame::class;
    }
}
