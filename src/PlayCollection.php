<?php

namespace JanWennrich\BoardGames;

use Ramsey\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<Play>
 */
final class PlayCollection extends AbstractCollection
{
    /**
     * @return class-string
     */
    public function getType(): string
    {
        return Play::class;
    }
}
