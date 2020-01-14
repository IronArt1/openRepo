<?php

namespace App\Sample\Domain\Model;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Sample\Infrastructure\Formatter\UpperFormatter;

/**
 * Class Tweet
 *
 * @package App\Sample\Domain\Model
 */
final class Tweet
{
    /**
     * A text of a certain tweet's
     *
     * @Groups("main")
     * @var string
     */
    private $text;

    /**
     * Tweet constructor's.
     *
     * @param string $text
     * @throws \InvalidArgumentException
     */
    public function __construct(string $text)
    {
        if (empty($text)) {
            throw new \InvalidArgumentException('Tweet can not be empty.');
        }

        // here we must have an original version of a tweet
        $this->text = $text;
    }

    /**
     * Gets a text of a certain tweet.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Make proper changes for JSON output
     *
     * @param UpperFormatter $formatter
     * @return string
     */
    public function makeFormattedOutput(UpperFormatter $formatter): string
    {
        return $formatter->applyUpperCaseAndExclamationMark($this->getText());
    }
}
