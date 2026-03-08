<?php

namespace JanWennrich\BoardGames;

use JanWennrich\BoardGameGeekApi\Client;
use JanWennrich\BoardGameGeekApi\Exception;

final readonly class PlayedBoardgamesLoader implements PlayedBoardgamesLoaderInterface
{
    public function __construct(
        private Client $bggApiClient,
        private BoardgameThumbnailLoader $thumbnailLoader,
    ) {}

    /**
     * @param non-empty-string $bggUsername
     * @throws Exception|\DateMalformedStringException
     */
    public function getForUser(string $bggUsername): PlayCollection
    {
        $bggPlays = $this->bggApiClient->getPlaysForUser($bggUsername);

        $bggPlayedGamesIds = array_map(
            static fn(\JanWennrich\BoardGameGeekApi\Plays\Play $bggPlay): int => $bggPlay->getItem()->getObjectId(),
            $bggPlays->getPlays(),
        );

        $playedGamesThumbnails = $this->thumbnailLoader->getForBggGameIds($bggPlayedGamesIds);

        $plays = array_map(
            static fn(\JanWennrich\BoardGameGeekApi\Plays\Play $bggPlay): Play => new Play(
                new Boardgame(
                    $bggPlay->getItem()->getName(),
                    $bggPlay->getId(),
                    $playedGamesThumbnails[$bggPlay->getItem()->getObjectId()]
                ),
                $bggPlay->getDate() ?? new \DateTimeImmutable(),
            ),
            $bggPlays->getPlays(),
        );

        return new PlayCollection($plays);
    }

    /**
     * @param non-empty-string $bggUsername
     * @throws Exception
     * @throws \DateMalformedStringException
     */
    public function getForUserWithoutApiToken(string $bggUsername): PlayCollection
    {
        $plays = array_map(
            static fn(\JanWennrich\BoardGameGeekApi\Plays\Play $bggPlay): Play => new Play(
                new Boardgame(
                    $bggPlay->getItem()->getName(),
                    $bggPlay->getId()
                ),
                $bggPlay->getDate() ?? new \DateTimeImmutable(),
            ),
            $this->bggApiClient->getPlaysForUser($bggUsername)->getPlays(),
        );

        return new PlayCollection($plays);
    }
}
