<?php

namespace App\Sample\Infrastructure\Command;

use App\Sample\Domain\Interfaces\Command\{
    CommandInterface,
    CommandBusInterface
};

/**
 * Class CommandBus'
 *
 * @package App\Builder
 */
class CommandBus implements CommandBusInterface
{
    /**
     * Input parameters're
     *
     * @var array
     */
    protected $parameters;

    /**
     * CommandBus constructor's.
     *
     * @param mixed ...$parameters
     */
    public function __construct(...$parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @inheritDoc
     */
    public function run(CommandInterface $command): array
    {
        $command->setInputParameters($this->parameters);

        /**
         * Could have been used in validation process.
         * See description in Infrastructure/Command/Abstracts/CommandAbstract.php, line 45
         */
        // $command->checkInputParameters();

        $command->run();

        return $command->getResponse();
    }
}