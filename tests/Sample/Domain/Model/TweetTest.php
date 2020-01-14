<?php

namespace App\Tests\Sample\Domain\Model;

use PHPUnit\Framework\TestCase;
use App\Sample\Domain\Model\Tweet;

/**
 * Class TweetTest's
 *
 * @package App\Tests\Sample\Domain\Model
 */
class TweetTest extends TestCase
{
    /**
     * A test value holder's.
     */
    const TEST = 'test';

    /**
     * A test for too few arguments case's.
     */
    public function testTooFewArgumentsCase()
    {
        $this->expectException('ArgumentCountError');
        $this->expectExceptionCode(0);
        $this->expectExceptionMessageRegExp('/Too few arguments to function .*/');

        new Tweet();
    }

    /**
     * A test for an empty case's.
     */
    public function testEmptyCase()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('Tweet can not be empty.');

        new Tweet('');
    }

    /**
     * A test for a positive case's.
     */
    public function testPositiveCase()
    {
        $tweet = new Tweet(self::TEST);
        $this->assertEquals(Tweet::class, get_class($tweet));
        $this->assertEquals($tweet->getText(), self::TEST);
    }
}
