<?php

namespace JanWennrich\BoardGames;

use JanWennrich\BoardGameGeekApi\Exception;

class PlayedBoardgamesLoader implements PlayedBoardgamesLoaderInterface
{
    public function __construct(
        private readonly BggApiClientProxy $bggApiClient,
        private readonly BoardgameThumbnailLoader $thumbnailLoader,
    ) {
    }

    /**
     * @throws Exception|\DateMalformedStringException
     */
    public function getForUser(string $bggUsername): PlayCollection
    {
        $bggPlays = $this->bggApiClient->getPlays(['username' => $bggUsername]);

        $bggPlayedGamesIds = array_map(fn($bggPlay) => $bggPlay->getObjectId(), $bggPlays);

        $playedGamesThumbnails = $this->thumbnailLoader->getForBggGameIds($bggPlayedGamesIds);

        $plays = array_map(
            static function (\JanWennrich\BoardGameGeekApi\Play $bggPlay) use ($playedGamesThumbnails) {
                return new Play(
                    new Boardgame(
                        $bggPlay->getObjectName(),
                        $bggPlay->getObjectId(),
                        $playedGamesThumbnails[$bggPlay->getObjectId()]
                    ),
                    new \DateTimeImmutable($bggPlay->getDate()),
                );
            },
            $bggPlays,
        );

        return new PlayCollection($plays);
    }

    public function getForUserWithoutApiToken(string $bggUsername): PlayCollection
    {
        $plays = array_map(
            static fn(\JanWennrich\BoardGameGeekApi\Play $bggPlay) => new Play(
                new Boardgame(
                    $bggPlay->getObjectName(),
                    $bggPlay->getObjectId()
                ),
                new \DateTimeImmutable($bggPlay->getDate()),
            ),
            $this->bggApiClient->getPlays(['username' => $bggUsername]),
        );

        return new PlayCollection($plays);
    }
}
