<?php

declare(strict_types=1);

namespace JanWennrich\BoardGames\Command;

use JanWennrich\BoardGames\BggApiClientProxy;
use JanWennrich\BoardGames\HtmlGeneratorInterface;
use JanWennrich\BoardGames\OwnedBoardgamesLoaderInterface;
use JanWennrich\BoardGames\PlayedBoardgamesLoaderInterface;
use JanWennrich\BoardGames\WishlistedBoardgamesLoaderInterface;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
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
        private readonly BggApiClientProxy $bggApiClient
    ) {
        parent::__construct();
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument(description: 'BoardGameGeek username to generate the portfolio for')]
        string $bggUsername,
        #[Option(
            description: 'Authenticate via BoardGameGeek API authorization token (see: https://boardgamegeek.com/using_the_xml_api)',
            name: 'bgg-token'
        )]
        string $bggToken = "",
        #[Option(
            description: 'Authenticate via password of the given BoardGameGeek user (limits functionality, use token instead)',
            name: 'bgg-password'
        )]
        string $bggPassword = "",
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

        $isAuthenticated = false;

        if ($bggToken !== '' && $bggPassword !== '') {
            $io->error('The options "--bgg-token" and "--bgg-password" are mutually exclusive. You may only use one of them at a time.');

            return Command::FAILURE;
        }

        if ($bggToken !== "") {
            $this->bggApiClient->authenticateWithToken($bggToken);
            $isAuthenticated = true;
        }

        if ($bggPassword !== "") {
            $this->bggApiClient->authenticateWithPassword($bggUsername, $bggPassword);
            $io->warning(
                'Authenticating via password, functionality is limited. Authenticate via API token for all features. See README.md for more information.',
            );

            $isAuthenticated = true;
        }

        if (!$isAuthenticated) {
            $io->error('Authentication via API token or password required.');

            return Command::FAILURE;
        }

        $io->info('Querying wishlisted boardgames…');
        $wishlistedBoardgames = $this->wishlistedBoardgamesLoader->getForUser($bggUsername);

        $io->info('Querying played boardgames…');
        if ($bggToken === "") {
            $io->warning('Plays are loaded without thumbnails due to restricted access via password authentication. Authenticate via API token to resolve this. See README.md for more information.');
            $playedBoardgames = $this->playedBoardgamesLoader->getForUserWithoutApiToken($bggUsername);
        } else {
            $playedBoardgames = $this->playedBoardgamesLoader->getForUser($bggUsername);
        }

        $io->info('Querying owned boardgames…');
        $ownedBoardgames = $this->ownedBoardgamesLoader->getForUser($bggUsername);

        $html = $this->htmlGenerator->generateHtml(
            $ownedBoardgames,
            $playedBoardgames,
            $wishlistedBoardgames,
            $bggUsername,
        );
        $io->info("Generated HTML from queried data");

        file_put_contents("{$buildDirectory}/index.html", $html);
        $io->success(sprintf("Portfolio successfully created in '%s'", realpath($buildDirectory)));

        return Command::SUCCESS;
    }
}
