<?php

namespace App\Tests\Sample\Domain\Types;

use PHPUnit\Framework\TestCase;
use App\Sample\Domain\Types\TwitterName;

/**
 * Class TwitterNameTest's
 *
 * @package App\Tests\Sample\Domain\Types
 */
class TwitterNameTest extends TestCase
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

        new TwitterName();
    }

    /**
     * A test for an empty case's.
     */
    public function testEmptyCase()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('TwitterName can not be empty.');

        new TwitterName('');
    }

    /**
     * A test for less than tree symbols case's.
     */
    public function testLessThanThreeSymbolsCase()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('TwitterName should be more than 3 symbols.');

        new TwitterName(' ');
    }

    /**
     * A test for a positive case's.
     */
    public function testPositiveCase()
    {
        $twitterName = new TwitterName(self::TEST);
        $this->assertEquals(TwitterName::class, get_class($twitterName));
        $this->assertEquals((string) $twitterName, self::TEST);
    }
}
