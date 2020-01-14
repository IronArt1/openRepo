<?php

namespace App\Sample\Domain\Interfaces\Command;

/**
 * Interface CommandInterface's
 *
 * @package App\Sample\Domain\Interfaces\Command
 */
interface CommandInterface
{
    /**
     * Setting up input parameters from the request
     *
     * @param mixed ...$parameters
     */
    public function setInputParameters(...$parameters): void;

    /**
     * In case of excessive validation's. See description in
     * Infrastructure/Command/Abstracts/CommandAbstract.php line 45
     */
    // public function checkInputParameters(): void;

    /**
     * Calling events so as to create a general flow
     */
    public function run(): void;

    /**
     * Gets a certain response for a controller
     *
     * @return array
     */
    public function getResponse(): array;
}
