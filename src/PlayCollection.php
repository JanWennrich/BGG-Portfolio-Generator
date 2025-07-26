<?php

namespace JanWennrich\BoardGames;

use Ramsey\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<Play>
 */
class PlayCollection extends AbstractCollection
{
    /**
     * @return class-string
     */
    public function getType(): string
    {
        return Play::class;
    }
}
