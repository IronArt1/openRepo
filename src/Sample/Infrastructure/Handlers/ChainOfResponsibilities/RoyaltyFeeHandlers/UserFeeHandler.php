<?php
// an example is; don not pay attention to specifics like "namespace" or "use"
namespace App\Sample\Infrastructure\Handlers\ChainOfResponsibilities\RoyaltyFeeHandler;

use Doctrine\ORM\EntityRepository;
use App\User\Entity as UserEntity;
use App\Sample\Infrastructure\Handlers\ChainOfResponsibilities\FeeHandler;
use App\User\UserSpecifyFee\UserSpecifyFeeEntity;

class UserFeeHandler extends FeeHandler
{
    /**
     * @var EntityRepository
     */
    private $userFeeRepository;

    /**
     * @param EntityRepository $userFeeRepository
     * @param FeeHandler|null $successor
     */
    public function __construct(EntityRepository $userFeeRepository, FeeHandler $successor = null)
    {
        parent::__construct($successor);

        $this->userFeeRepository = $userFeeRepository;
    }

    protected function processing(UserEntity $user)
    {
        $userFee = $this->userFeeRepository->findOneByUser($user);

        return ($userFee instanceof UserSpecifyFeeEntity) ? $userFee->getFee() : null;
    }
}
