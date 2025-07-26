<?php

namespace JanWennrich\BoardGames;

use Nataniel\BoardGameGeek\CollectionItem;

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
            fn(CollectionItem $collectionItem) => new WishlistEntry(
                boardgame: new Boardgame(
                    $collectionItem->getName(),
                    $collectionItem->getThumbnail(),
                    (int)$collectionItem->getObjectId(),
                ),
                wantLevel: (int)$collectionItem->getStatus()->attributes()->wishlistpriority,
                lastModified: new \DateTimeImmutable($collectionItem->getStatus()->attributes()->lastmodified)
            ),
            $wishlistedBoardgames,
        );

        return new WishlistEntryCollection($wishlistedBoardgames);
    }
}
