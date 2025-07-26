<?php

namespace JanWennrich\BoardGames;

class PlayedBoardgamesLoader implements PlayedBoardgamesLoaderInterface
{
    public function __construct(
        private readonly BggApiClientProxy $bggApiClient,
        private readonly BoardgameThumbnailLoader $thumbnailLoader,
    ) {
    }

    /**
     * @throws \Nataniel\BoardGameGeek\Exception|\DateMalformedStringException
     */
    public function getForUser(string $bggUsername): PlayCollection
    {
        $bggPlays = $this->bggApiClient->getPlays(['username' => $bggUsername]);

        $bggPlayedGamesIds = array_map(fn($bggPlay) => $bggPlay->getObjectId(), $bggPlays);

        $playedGamesThumbnails = $this->thumbnailLoader->getForBggGameIds($bggPlayedGamesIds);

        $plays = array_map(
            function (\Nataniel\BoardGameGeek\Play $bggPlay) use ($playedGamesThumbnails) {
                return new Play(
                    new Boardgame($bggPlay->getObjectName(), $playedGamesThumbnails[$bggPlay->getObjectId()], $bggPlay->getObjectId()),
                    new \DateTimeImmutable($bggPlay->getDate()),
                );
            },
            $bggPlays,
        );

        return new PlayCollection($plays);
    }
}
