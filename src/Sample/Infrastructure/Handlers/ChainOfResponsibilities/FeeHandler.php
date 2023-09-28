<?php
// an example is; don not pay attention to specifics like "use"
namespace App\Sample\Infrastructure\Handlers\ChainOfResponsibilities;

use App\User\Entity as UserEntity;
use App\Handlers\ChainOfResponsibilities\Exception\FeeChainException;

abstract class FeeHandler
{
    /**
     * @var FeeHandler|null
     */
    private $successor;

    /**
     * @param FeeHandler|null $handler
     */
    public function __construct(FeeHandler $handler = null)
    {
        $this->successor = $handler;
    }

    /**
     * @param UserEntity $user
     *
     * @return mixed
     * @throws FeeChainException
     */
    final public function handle(UserEntity $user)
    {
        try {
            $processed = $this->processing($user);
        } catch (\Exception $e) {
            throw new FeeChainException($e->getMessage() . PHP_EOL . $e->getTraceAsString(), $e->getCode());
        }

        if (is_null($processed)) {
            if(!is_null($this->successor)) {
                $processed = $this->successor->handle($user);
            }
        }

        return $processed;
    }

    /**
     * @param UserEntity $user
     * @return mixed
     */
    abstract protected function processing(UserEntity $user);
}
