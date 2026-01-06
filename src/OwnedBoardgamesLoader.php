<?php

namespace JanWennrich\BoardGames;

use JanWennrich\BoardGameGeekApi\Collection\Item;

final readonly class OwnedBoardgamesLoader implements OwnedBoardgamesLoaderInterface
{
    public function __construct(
        private BggApiClientProxy $bggApiClient,
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
                $collectionItem->getObjectId(),
                $collectionItem->getThumbnail()
            ),
            $ownedBoardgames->getIterator()->getArrayCopy(),
        );

        return new BoardgameCollection($ownedBoardgames);
    }
}
