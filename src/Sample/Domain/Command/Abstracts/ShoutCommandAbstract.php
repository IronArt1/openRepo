<?php

namespace App\Sample\Domain\Command\Abstracts;

// could have been used for validation
//use App\Interfaces\Controller\ControllerInterface;

use App\Sample\Infrastructure\Command\Abstracts\CommandAbstract;

/**
 * Class ShoutCommandAbstract
 * A road map for Shout Command's
 *
 * @package App\Sample\Domain\Command\Abstracts
 */
abstract class ShoutCommandAbstract extends CommandAbstract
{
    // Could have been used for validation. See description in
    // Infrastructure/Command/Abstracts/CommandAbstract.php line 45
    /**
     * Validation parameters for POST request
     */
    protected const GET_VALIDATION = [
//        'twitterName' => ControllerInterface::STRING_TYPE_HOLDER,
//        'tweetLimit' => ControllerInterface::INT_TYPE_HOLDER,
    ];

    /**
     * ShoutCommandAbstract constructor's.
     *
     * @throws \ReflectionException
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets tweets from a repository by twitterName
     *
     * an event 1st's
     *
     * @throws \Exception
     */
    abstract protected function getTweetsByTwitterName(): void;

    /**
     * Saves tweets in the cache.
     *
     * In this particular case we could have used Redis or Memcache (OpCache also),
     * but since we do not have any restrictions on the matter, the simplest one
     * will do the trick...
     *
     * an event 2nd's
     *
     * @throws \Throwable
     */
    abstract protected function saveTweetsInCache(): void;
}
