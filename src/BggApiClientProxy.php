<?php

namespace JanWennrich\BoardGames;

use JanWennrich\BoardGameGeekApi\Client;
use JanWennrich\BoardGameGeekApi\Collection;
use JanWennrich\BoardGameGeekApi\Thing;

class BggApiClientProxy
{
    public function __construct(private Client $bggApiClient)
    {
    }

    public function authenticateWithPassword(string $bggUsername, string $bggPassword): void
    {
        $this->bggApiClient->login($bggUsername, $bggPassword);
    }

    public function authenticateWithToken(string $bggToken): void
    {
        $this->bggApiClient->setAuthorization($bggToken);
    }

    /**
     * @param int[] $ids
     * @param bool $stats
     * @return Thing[]
     * @throws \JanWennrich\BoardGameGeekApi\Exception
     */
    public function getThings(array $ids, bool $stats = false): array
    {
        return $this->bggApiClient->getThings($ids, $stats);
    }

    /**
     * @param mixed[] $params
     * @throws \JanWennrich\BoardGameGeekApi\Exception
     */
    public function getCollection(array $params): Collection
    {
        return $this->bggApiClient->getCollection($params);
    }

    /**
     * @param mixed[] $params
     * @return \JanWennrich\BoardGameGeekApi\Play[]
     * @throws \JanWennrich\BoardGameGeekApi\Exception
     */
    public function getPlays(array $params): array
    {
        return $this->bggApiClient->getPlays($params);
    }
}
