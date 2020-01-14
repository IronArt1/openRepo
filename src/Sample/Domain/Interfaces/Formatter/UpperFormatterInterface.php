<?php

namespace App\Sample\Domain\Interfaces\Formatter;

/**
 * Interface UpperFormatterInterface's
 *
 * @package App\Sample\Domain\Interfaces\Formatter
 */
interface UpperFormatterInterface
{
    /**
     * Applies all necessary format requirements.
     *
     * @param string $text
     * @return mixed
     */
    public function applyUpperCaseAndExclamationMark(string $text);
}