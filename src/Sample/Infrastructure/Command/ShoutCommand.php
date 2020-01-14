<?php

namespace App\Sample\Infrastructure\Command;

use App\Sample\Domain\Types\{
    TweetLimit,
    TwitterName
};
use App\Sample\Infrastructure\Traits\ApcuTrait;
use App\Sample\Infrastructure\Formatter\UpperFormatter;
use App\Sample\Domain\Interfaces\Command\CommandInterface;
use App\Sample\Domain\Command\Abstracts\ShoutCommandAbstract;
use App\Sample\Infrastructure\Exception\NotImplementedException;
use App\Sample\Infrastructure\Repository\TweetRepositoryInMemory;


/**
 * Class ShoutCommand's
 *
 * @package App\Sample\Infrastructure\Command
 */
class ShoutCommand extends ShoutCommandAbstract implements CommandInterface
{
    use ApcuTrait;

    /**
     * Values for mapping're
     */
    const REQUIRED_VALUES = [
        'LEAD_EVENT_TYPE_DESC'          => 'type',
        'FIRST_LAST_NM'                 => 'name',
        'EVENT_SESSION_START_TIMESTAMP' => 'dateTime',
    ];


    /**
     * A user name holder entity's
     *
     * @var TwitterName
     */
    private $twitterName;

    /**
     * A limit holder entity's
     *
     * @var TweetLimit
     */
    private $tweetLimit;

    /**
     * A cache key's
     *
     * @var string
     */
    private $key;

    /**
     * A collection of tweets'
     *
     * @var array
     */
    private $tweets;

    /**
     * A tweet repository's
     *
     * @var TweetRepositoryInMemory
     */
    private $tweetRepositoryInMemory;

    /**
     * An appropriate formatter for the case's
     *
     * @var UpperFormatter
     */
    private $upperFormatter;

    /**
     * ShoutCommand constructor's.
     *
     * @param UpperFormatter $upperFormatter
     * @param TweetRepositoryInMemory $tweetRepositoryInMemory
     *
     * @throws \ReflectionException
     */
    public function __construct(
        UpperFormatter $upperFormatter,
        TweetRepositoryInMemory $tweetRepositoryInMemory
    ) {
        parent::__construct();

        $this->tweets = [];
        $this->upperFormatter = $upperFormatter;
        $this->tweetRepositoryInMemory = $tweetRepositoryInMemory;
    }

    /**
     * Set up input parameters
     *
     * @param mixed ...$parameters
     */
    public function setInputParameters(...$parameters): void
    {
        if (!ApcuTrait::isSupported()) {
            throw new NotImplementedException();
        }

        list($this->twitterName, $this->tweetLimit) = $parameters[0];

        $this->key = (string) $this->twitterName . $this->tweetLimit->getLimit();
    }

    /**
     * {@inheritdoc}
     */
    protected function getTweetsByTwitterName(): void
    {
        // in a complex system this should be done in ShoutService
        $suddenReturn = function($_this) {
            $_this->events = [key($_this->events) + 1 => end($_this->events)];
            foreach ($this->fetch([$_this->key]) as $key => $value) {
                return unserialize($value);
            }
        };

        if ($this->have($this->key)) {
            $this->tweets = $suddenReturn($this);
        } else {
            foreach ($this->tweetRepositoryInMemory->searchTweetsByTwitterName($this->twitterName, $this->tweetLimit) as $tweet) {
                $this->tweets[] = $tweet->makeFormattedOutput($this->upperFormatter);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function saveTweetsInCache(): void
    {
        // in a complex system this should be done in ShoutService
        if (!$this->have($this->key)) {
            $this->save([$this->key => serialize($this->tweets)], 60);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setResponse(): void
    {
        // responses can be significantly various. in this particular case it's a simple one.
        $this->response = $this->tweets;
    }
}