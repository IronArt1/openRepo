<?php

namespace App\Sample\Domain\Types;

/**
 * Class TweetLimit
 *
 * @package App\Sample\Domain\Types
 */
class TweetLimit
{
    /**
     * A minimum amount of tweets'
     */
    private const MINIMUM_AMOUNT_OF_TWEETS = 1;

    /**
     * A maximum amount of tweets'
     */
    private const MAX_AMOUNT_OF_TWEETS = 10;

    /**
     * A limit of certain tweets's
     *
     * @var int
     */
    private $limit;

    /**
     * TweetLimit constructor's.
     *
     * @param int $limit
     * @throws \InvalidArgumentException
     */
    public function __construct(int $limit)
    {
        if ($limit < self::MINIMUM_AMOUNT_OF_TWEETS || $limit > self::MAX_AMOUNT_OF_TWEETS) {
            throw new \InvalidArgumentException('A limit of tweets must be equal or less than 10.');
        }

        $this->limit = $limit;
    }

    /**
     * Gets a limit of tweets.
     *
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }
}
