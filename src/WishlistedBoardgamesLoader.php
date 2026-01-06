<?php

namespace JanWennrich\BoardGames;

use JanWennrich\BoardGameGeekApi\Collection\Item;

final readonly class WishlistedBoardgamesLoader implements WishlistedBoardgamesLoaderInterface
{
    public function __construct(
        private BggApiClientProxy $bggApiClient,
    ) {
    }

    public function getForUser(string $bggUsername): WishlistEntryCollection
    {
        $wishlistedBoardgames = $this->bggApiClient->getCollection([
            'username' => $bggUsername,
            'wishlist' => 1,
        ]);

        $wishlistedBoardgames = array_map(
            fn(Item $collectionItem): WishlistEntry => new WishlistEntry(
                boardgame: new Boardgame(
                    $collectionItem->getName(),
                    $collectionItem->getObjectId(),
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
