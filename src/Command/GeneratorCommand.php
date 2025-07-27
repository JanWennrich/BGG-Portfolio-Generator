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
        #[Argument] string $bggUsername,
    ): int {
        $io->title("BoardGameGeek Portfolio Generator");

        $buildDirectory = __DIR__ . '/../../public';

        $io->info(
            sprintf(
                "Generating portfolio for BGG user '%s' into '%s'…",
                $bggUsername,
                realpath($buildDirectory),
            ),
        );

        if (!is_dir($buildDirectory) && !@mkdir($buildDirectory)) {
            $io->error(sprintf('Build directory "%s" could not be created', $buildDirectory));

            return Command::FAILURE;
        }

        $io->info('Querying wishlisted boardgames…');
        $wishlistedBoardgames = $this->wishlistedBoardgamesLoader->getForUser($bggUsername);

        $io->info('Querying played boardgames…');
        $playedBoardgames = $this->playedBoardgamesLoader->getForUser($bggUsername);

        $io->info('Querying owned boardgames…');
        $ownedBoardgames = $this->ownedBoardgamesLoader->getForUser($bggUsername);

        $html = $this->htmlGenerator->generateHtml(
            $ownedBoardgames,
            $playedBoardgames,
            $wishlistedBoardgames,
            $bggUsername,
        );
        $io->info("Generated HTML from queried data");

        file_put_contents("$buildDirectory/index.html", $html);
        $io->success("Portfolio successfully created in '$buildDirectory'");

        return Command::SUCCESS;
    }
}
