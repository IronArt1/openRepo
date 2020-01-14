<?php

namespace App\Tests\Sample\Infrastructure\Command;

use App\Sample\Domain\Types\{
    TweetLimit,
    TwitterName
};
use PHPUnit\Framework\TestCase;
use App\Sample\Infrastructure\Command\CommandBus;
use App\Sample\Infrastructure\Command\ShoutCommand;

/**
 * Class CommandBusTest's
 *
 * @package App\Tests\Sample\Infrastructure\Command
 */
class CommandBusTest extends TestCase
{
    /**
     * @covers ::run()
     */
    public function testRun(): void
    {
        $twitterName = $this->createMock(TwitterName::class);
        $tweetLimit = $this->createMock(TweetLimit::class);

        $shoutCommandMock = $this->getMockBuilder(ShoutCommand::class)
            ->disableOriginalConstructor()
            ->getMock();

        $shoutCommandMock->expects($this->once())
            ->method('setInputParameters');

        $shoutCommandMock->expects($this->once())
            ->method('run');

        $shoutCommandMock->expects($this->atLeast(2))
            ->method('getResponse')
            ->willReturn(['test']);

        $commandBus = new CommandBus($twitterName, $tweetLimit);
        $commandBus->run($shoutCommandMock);
        $this->assertEquals(['test'], $shoutCommandMock->getResponse());
    }

}
