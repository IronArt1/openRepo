<?php

namespace App\Sample\Domain\Interfaces\Command;

/**
 * Interface CommandBusInterface's
 *
 * @package App\Sample\Domain\Interfaces\Command
 */
interface CommandBusInterface
{
    /**
     * A general flow for processing data and making a response
     *
     * @param CommandInterface $command
     * @return array
     */
    public function run(CommandInterface $command): array;
}
