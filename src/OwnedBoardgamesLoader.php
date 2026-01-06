<?php

namespace JanWennrich\BoardGames;

use JanWennrich\BoardGameGeekApi\Collection\Item;

class OwnedBoardgamesLoader implements OwnedBoardgamesLoaderInterface
{
    public function __construct(
        private readonly BggApiClientProxy $bggApiClient,
    ) {
    }

    public function getForUser(string $bggUsername): BoardgameCollection
    {
        $ownedBoardgames = $this->bggApiClient->getCollection([
            'username' => $bggUsername,
            'own' => 1,
        ]);

        $ownedBoardgames = array_map(
            fn(Item $collectionItem): Boardgame => new Boardgame(
                $collectionItem->getName(),
                (int)$collectionItem->getObjectId(),
                $collectionItem->getThumbnail()
            ),
            $ownedBoardgames->getIterator()->getArrayCopy(),
        );

        return new BoardgameCollection($ownedBoardgames);
    }
}
