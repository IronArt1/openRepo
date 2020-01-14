<?php

namespace App\Sample\Infrastructure\Formatter;

use App\Sample\Domain\Interfaces\Formatter\UpperFormatterInterface;

/**
 * Class UpperFormatter's
 *
 * @package App\Sample\Infrastructure\Formatter
 */
final class UpperFormatter implements UpperFormatterInterface
{
    /**
     * Makes appropriate changes to a tweet.
     *
     * @param string $text
     * @return string|string[]|null
     */
    public function applyUpperCaseAndExclamationMark(string $text): string
    {
        return preg_replace("/(\b\.\s)$|([?.\s]+$)|([A-Z’]$)/", '$3!', strtoupper($text));
    }
}