<?php

namespace JanWennrich\BoardGames;

use Nataniel\BoardGameGeek\Exception;

class BoardgameThumbnailLoader
{
    public function __construct(private BggApiClientProxy $bggApiClient)
    {
    }


    /**
     * Fetches the thumbnails for the given board game IDs from the BGG API.
     *
     * @param int[] $bggGameIds The IDs of the board games to fetch thumbnails for
     * @return string[] A mapping of game ID to thumbnail URL
     *
     * @throws Exception
     */
    public function getForBggGameIds(array $bggGameIds): array
    {
        $bggGameIds = array_unique($bggGameIds);

        $bggThings = [];

        foreach (array_chunk($bggGameIds, 20) as $gameIdsChunk) {
            $bggThings = array_merge($bggThings, $this->bggApiClient->getThings($gameIdsChunk));
        }

        $thumbnails = [];

        foreach ($bggThings as $bggThing) {
            $thumbnails[$bggThing->getId()] = $bggThing->getThumbnail();
        }

        return $thumbnails;
    }
}
