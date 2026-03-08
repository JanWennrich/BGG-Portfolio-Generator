<?php

namespace JanWennrich\BoardGames;

use JanWennrich\BoardGameGeekApi\Client;
use JanWennrich\BoardGameGeekApi\Collection\CollectionItem;
use JanWennrich\BoardGameGeekApi\Query\CollectionQuery;

final readonly class WishlistedBoardgamesLoader implements WishlistedBoardgamesLoaderInterface
{
    public function __construct(
        private Client $bggApiClient,
    ) {}

    /**
     * @param non-empty-string $bggUsername
     * @throws \JanWennrich\BoardGameGeekApi\Exception
     */
    public function getForUser(string $bggUsername): WishlistEntryCollection
    {
        $wishlistedBoardgames = $this->bggApiClient->getCollection(
            $bggUsername,
            new CollectionQuery(isWishlisted: true),
        );

        $wishlistedBoardgames = array_map(
            fn(CollectionItem $collectionItem): WishlistEntry => new WishlistEntry(
                boardgame: new Boardgame(
                    $collectionItem->getName(),
                    $collectionItem->getObjectId(),
                    $collectionItem->getThumbnail(),
                ),
                wantLevel: (int) $collectionItem->getStatus()->getWishlistPriority(),
                lastModified: $collectionItem->getStatus()->getLastModified()
            ),
            $wishlistedBoardgames->getItems(),
        );

        return new WishlistEntryCollection($wishlistedBoardgames);
    }
}
