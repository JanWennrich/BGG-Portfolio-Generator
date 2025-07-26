<?php

declare(strict_types=1);

namespace JanWennrich\BoardGames\Command;

use JanWennrich\BoardGames\HtmlGeneratorInterface;
use JanWennrich\BoardGames\OwnedBoardgamesLoaderInterface;
use JanWennrich\BoardGames\PlayedBoardgamesLoaderInterface;
use JanWennrich\BoardGames\WishlistedBoardgamesLoaderInterface;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'generate')]
final class GeneratorCommand extends Command
{
    public function __construct(
        private readonly WishlistedBoardgamesLoaderInterface $wishlistedBoardgamesLoader,
        private readonly PlayedBoardgamesLoaderInterface $playedBoardgamesLoader,
        private readonly OwnedBoardgamesLoaderInterface $ownedBoardgamesLoader,
        private readonly HtmlGeneratorInterface $htmlGenerator,
    ) {
        parent::__construct();
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument] string $bggUsername
    ): int {
        $buildDirectory = __DIR__ . '/../../public';

        if (!is_dir($buildDirectory) && !@mkdir($buildDirectory)) {
            $io->error(sprintf('Build directory "%s" could not be created', $buildDirectory));

            return Command::FAILURE;
        }

        $wishlistedBoardgames = $this->wishlistedBoardgamesLoader->getForUser($bggUsername);
        $playedBoardgames = $this->playedBoardgamesLoader->getForUser($bggUsername);
        $ownedBoardgames = $this->ownedBoardgamesLoader->getForUser($bggUsername);

        $html = $this->htmlGenerator->generateHtml(
            $ownedBoardgames,
            $playedBoardgames,
            $wishlistedBoardgames,
            $bggUsername,
        );

        file_put_contents("$buildDirectory/index.html", $html);

        return Command::SUCCESS;
    }
}
