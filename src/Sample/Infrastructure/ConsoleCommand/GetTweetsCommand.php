<?php

namespace App\Sample\Infrastructure\ConsoleCommand;

use App\Sample\Domain\Types\{
    TwitterName,
    TweetLimit
};
use Symfony\Component\Console\Input\{
    InputOption,
    InputArgument,
    InputInterface,
    InputDefinition
};
use App\Sample\Infrastructure\Command\{
    CommandBus,
    ShoutCommand
};
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Console\Output\OutputInterface;
use App\Sample\Infrastructure\Formatter\UpperFormatter;
use App\Sample\Infrastructure\Repository\TweetRepositoryInMemory;

/**
 * Class GetTweetsCommand's
 * php bin/console twitter-account:get-tweets Trump 5
 * Although there is a chance that you have to enable APCu in php.ini since different ini-file can be loaded.
 *
 * @package App\Sample\Infrastructure\ConsoleCommand
 */
class GetTweetsCommand extends Command
{
    protected const TWITTER_NAME = 'twitterName';
    protected const TWEET_LIMIT = 'tweetLimit';

    protected static $defaultName = 'twitter-account:get-tweets';

    /**
     * @var TweetRepositoryInMemory $tweetRepositoryInMemory
     */
    private $tweetRepositoryInMemory;

    /**
     * @var UpperFormatter $upperFormatter
     */
    private $upperFormatter;

    /**
     * GetTweetsCommand constructor's.
     *
     * @param TweetRepositoryInMemory $tweetRepositoryInMemory
     * @param UpperFormatter $upperFormatter
     * @param string|null $name
     */
    public function __construct(
        TweetRepositoryInMemory $tweetRepositoryInMemory,
        UpperFormatter $upperFormatter,
        ?string $name = null
    ) {
        parent::__construct($name);

        $this->upperFormatter = $upperFormatter;
        $this->tweetRepositoryInMemory = $tweetRepositoryInMemory;
    }

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Gets limited amount of tweets by twitterName.')
            ->setDefinition(
                new InputDefinition(
                    array(
                        new InputArgument(
                            self::TWITTER_NAME,
                            InputOption::VALUE_REQUIRED,
                            'A certain twitter name is.'
                        ),
                        new InputArgument(
                            self::TWEET_LIMIT,
                            InputOption::VALUE_REQUIRED,
                            'A desired amount of tweets is (0-10).'
                        )
                    )
                )
            );
    }

    /**
     * Executes a command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     * @throws \ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $commandBus = new CommandBus(
            new TwitterName($input->getArgument(self::TWITTER_NAME)),
            new TweetLimit($input->getArgument(self::TWEET_LIMIT))
        );

        $shoutCommand = new ShoutCommand(
            $this->upperFormatter,
            $this->tweetRepositoryInMemory
        );

        $response = $commandBus->run($shoutCommand);

        $io->success(new JsonResponse($response));
    }
}
