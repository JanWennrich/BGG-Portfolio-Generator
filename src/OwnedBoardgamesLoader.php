<?php

namespace JanWennrich\BoardGames;

use Nataniel\BoardGameGeek\CollectionItem;

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
            fn(CollectionItem $collectionItem) => new Boardgame(
                $collectionItem->getName(),
                $collectionItem->getThumbnail(),
                (int) $collectionItem->getObjectId()
            ),
            $ownedBoardgames,
        );

        return new BoardgameCollection($ownedBoardgames);
    }
}
