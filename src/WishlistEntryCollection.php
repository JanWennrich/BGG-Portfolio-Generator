<?php

namespace JanWennrich\BoardGames;

use Ramsey\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<WishlistEntry>
 */
final class WishlistEntryCollection extends AbstractCollection
{
    /**
     * @return class-string
     */
    public function getType(): string
    {
        return WishlistEntry::class;
    }
}
