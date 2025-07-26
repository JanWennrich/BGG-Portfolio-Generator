<?php

namespace JanWennrich\BoardGames;

use Ramsey\Collection\Sort;
use Twig\Environment;

final readonly class HtmlGenerator
{
    public function __construct(
        private Environment $twig,
    ) {
    }

    public function generateHtml(
        BoardgameCollection $boardgamesOwned,
        PlayCollection $boardgamesPlayed,
        WishlistEntryCollection $wishlistedBoardgames,
        string $bggUsername
    ): string {
        $ownedBoardgamesGroupedByFirstLetter = [];

        foreach ($boardgamesOwned as $ownedBoardgame) {
            $ownedBoardgamesGroupedByFirstLetter[mb_strtolower($ownedBoardgame->title[0])][] = $ownedBoardgame;
        }

        $playsGroupedByDate = [];

        foreach ($boardgamesPlayed as $play) {
            $playsGroupedByDate[$play->playDateTime->format('d.m.y')][] = $play->boardgame;
        }

        $wishlistedBoardgames = $wishlistedBoardgames->sort('lastModified', Sort::Descending);

        $wishlistedBoardgamesGroupedByWantLevel = [];

        foreach ($wishlistedBoardgames as $wishlistedBoardgame) {
            $wishlistedBoardgamesGroupedByWantLevel[$wishlistedBoardgame->wantLevel][] = $wishlistedBoardgame->boardgame;
        }

        ksort($wishlistedBoardgamesGroupedByWantLevel);

        $wishlistedBoardgamesGroupedByTextualWantLevel = [];

        foreach ($wishlistedBoardgamesGroupedByWantLevel as $wantLevel => $boardgames) {
            $textualWantLevel = match ($wantLevel) {
                1 => 'Must have',
                2 => 'Love to have',
                3 => 'Like to have',
                4 => 'Thinking about it',
                5 => 'Do not buy this',
                default => 'Unknown',
            };

            $wishlistedBoardgamesGroupedByTextualWantLevel[$textualWantLevel] = $boardgames;
        }

        $params = [
            'bggUsername' => $bggUsername,
            'playsGroupedByDate' => $playsGroupedByDate,
            'ownedBoardgamesGroupedByFirstLetter' => $ownedBoardgamesGroupedByFirstLetter,
            'wishlistedBoardgamesGroupedByTextualWantLevel' => $wishlistedBoardgamesGroupedByTextualWantLevel,
        ];

        return $this->twig->render('page.twig', $params);
    }
}
