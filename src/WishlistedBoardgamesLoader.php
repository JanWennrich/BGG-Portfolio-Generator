<?php

namespace JanWennrich\BoardGames;

use JanWennrich\BoardGameGeekApi\Collection\Item;

class WishlistedBoardgamesLoader implements WishlistedBoardgamesLoaderInterface
{
    public function __construct(
        private readonly BggApiClientProxy $bggApiClient,
    ) {
    }

    public function getForUser(string $bggUsername): WishlistEntryCollection
    {
        $wishlistedBoardgames = $this->bggApiClient->getCollection([
            'username' => $bggUsername,
            'wishlist' => 1,
        ]);

        $wishlistedBoardgames = array_map(
            fn(Item $collectionItem) => new WishlistEntry(
                boardgame: new Boardgame(
                    $collectionItem->getName(),
                    (int)$collectionItem->getObjectId(),
                    $collectionItem->getThumbnail(),
                ),
                wantLevel: (int)$collectionItem->getStatus()->getWishlistPriority(),
                lastModified: $collectionItem->getStatus()->getLastModified() ?? new \DateTimeImmutable()
            ),
            $wishlistedBoardgames->getIterator()->getArrayCopy(),
        );

        return new WishlistEntryCollection($wishlistedBoardgames);
    }
}
