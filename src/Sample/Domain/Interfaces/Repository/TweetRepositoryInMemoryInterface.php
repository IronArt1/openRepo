<?php

namespace App\Sample\Domain\Interfaces\Repository;

use App\Sample\Domain\Types\TweetLimit;
use App\Sample\Domain\Types\TwitterName;

/**
 * Interface TweetRepositoryInMemoryInterface's
 *
 * @package App\Sample\Domain\Interfaces\Repository
 */
interface TweetRepositoryInMemoryInterface
{
    /**
     * Gets an array of random user's tweets
     *
     * @param TwitterName $twitterName
     * @param TweetLimit $tweetLimit
     * @return array
     *
     * @throws \Exception
     */
    public function searchTweetsByTwitterName(
        TwitterName $twitterName,
        TweetLimit $tweetLimit
    );
}
