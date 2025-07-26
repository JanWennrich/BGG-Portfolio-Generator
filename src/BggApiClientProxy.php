<?php

namespace JanWennrich\BoardGames;

use Exception;
use Nataniel\BoardGameGeek\Client;
use Nataniel\BoardGameGeek\CollectionItem;
use Nataniel\BoardGameGeek\Thing;

class BggApiClientProxy
{
    public function __construct(private Client $bggApiClient)
    {
    }

    /**
     * @return mixed[]
     */
    private function retryUntilNonEmptyArrayIsReturned(
        callable $callable,
        int $maxRetries = 3,
        int $sleepTimeInSeconds = 2,
    ): array {
        $retries = 0;

        while ($retries < $maxRetries) {
            if ($retries > 0) {
                sleep($sleepTimeInSeconds);
            }

            try {
                $result = $callable();
            } catch (Exception) {
                $result = [];
            }

            if (is_array($result) && $result !== []) {
                return $result;
            }

            $retries++;
        }

        return [];
    }

    /**
     * Calls {@see Client::getThings()} and retries until the result is not an empty array.
     *
     * @param int[] $ids
     * @param bool $stats
     * @return Thing[]
     */
    public function getThings(array $ids, bool $stats = false): array
    {
        return $this->retryUntilNonEmptyArrayIsReturned(fn() => $this->bggApiClient->getThings($ids, $stats));
    }

    /**
     * Calls {@see Client::getCollection()} and retries until the result is not an empty array.
     *
     * @param mixed[] $params
     * @return CollectionItem[]
     */
    public function getCollection(array $params): array
    {
        return $this->retryUntilNonEmptyArrayIsReturned(fn() => $this->bggApiClient->getCollection($params));
    }

    /**
     * Calls {@see Client::getPlays()} and retries until the result is not an empty array.
     *
     * @param mixed[] $params
     * @return \Nataniel\BoardGameGeek\Play[]
     */
    public function getPlays(array $params): array
    {
        return $this->retryUntilNonEmptyArrayIsReturned(fn() => $this->bggApiClient->getPlays($params));
    }
}
