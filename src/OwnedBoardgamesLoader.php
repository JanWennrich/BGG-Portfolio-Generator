<?php

namespace JanWennrich\BoardGames;

use JanWennrich\BoardGameGeekApi\Client;
use JanWennrich\BoardGameGeekApi\Collection\CollectionItem;
use JanWennrich\BoardGameGeekApi\Query\CollectionQuery;

final readonly class OwnedBoardgamesLoader implements OwnedBoardgamesLoaderInterface
{
    public function __construct(
        private Client $bggApiClient,
    ) {}

    /**
     * @param non-empty-string $bggUsername
     * @throws \JanWennrich\BoardGameGeekApi\Exception
     */
    public function getForUser(string $bggUsername): BoardgameCollection
    {
        $ownedBoardgames = $this->bggApiClient->getCollection($bggUsername, new CollectionQuery(isOwned: true));

        $ownedBoardgames = array_map(
            fn(CollectionItem $collectionItem): Boardgame => new Boardgame(
                $collectionItem->getName(),
                $collectionItem->getObjectId(),
                $collectionItem->getThumbnail()
            ),
            $ownedBoardgames->getItems(),
        );

        return new BoardgameCollection($ownedBoardgames);
    }
}
