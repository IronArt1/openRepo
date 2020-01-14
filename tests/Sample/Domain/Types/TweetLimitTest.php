<?php

namespace App\Tests\Sample\Domain\Types;

use PHPUnit\Framework\TestCase;
use App\Sample\Domain\Types\TweetLimit;

/**
 * Class TweetLimitTest's
 *
 * @package App\Tests\Sample\Domain\Types
 */
class TweetLimitTest extends TestCase
{
    /**
     * A test value holder's.
     */
    const TEST = 5;

    /**
     * A test for too few arguments case's.
     */
    public function testTooFewArgumentsCase()
    {
        $this->expectException('ArgumentCountError');
        $this->expectExceptionCode(0);
        $this->expectExceptionMessageRegExp('/Too few arguments to function .*/');

        new TweetLimit();
    }

    /**
     * A test for a negative case's.
     */
    public function testNegativeCase()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('A limit of tweets must be equal or less than 10.');

        new TweetLimit(-1);
    }

    /**
     * A test for a excessive case's.
     */
    public function testExcessiveCase()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('A limit of tweets must be equal or less than 10.');

        new TweetLimit(11);
    }

    /**
     * A test for a positive case's.
     */
    public function testPositiveCase()
    {
        $tweetLimit = new TweetLimit(self::TEST);
        $this->assertEquals(TweetLimit::class, get_class($tweetLimit));
        $this->assertEquals($tweetLimit->getLimit(), self::TEST);
    }
}
