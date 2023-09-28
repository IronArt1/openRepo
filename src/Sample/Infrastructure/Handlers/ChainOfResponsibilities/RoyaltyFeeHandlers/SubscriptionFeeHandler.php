<?php
// an example is; don not pay attention to specifics like "namespace" or "use"
namespace App\Sample\Infrastructure\Handlers\ChainOfResponsibilities\RoyaltyFeeHandler;

use App\User\Entity as UserEntity;
use App\Sample\Infrastructure\Handlers\ChainOfResponsibilities\FeeHandler;
use App\User\Subscription\SubscriptionRepository;

class SubscriptionFeeHandler extends FeeHandler
{
    /**
     *
     */
    const DO_NOT_APPLY_ANY_FEE = 0;

    /**
     *
     */
    const DEFAULT_FEE_USER_WITH_SUBSCRIPTION = 8;

    /**
     * @var SubscriptionRepository
     */
    protected $subscriptionRepository;

    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     * @param SubscriptionRepository $subscriptionRepository
     * @param FeeHandler|null $successor
     */
    public function __construct(
        array $data,
        SubscriptionRepository $subscriptionRepository,
        FeeHandler $successor = null
    ) {
        parent::__construct($successor);

        $this->data = $data;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * @param UserEntity $user
     * @return int
     */
    protected function processing(UserEntity $user)
    {
        $didUserHaveSubscriptionInPeriod = $this->subscriptionRepository->didUserHaveSubscriptionInPeriod(
            $user,
            $this->data['period'],
            $this->data['periodDuration'],
            $this->data['requiredCoverage']
        );

        return ($didUserHaveSubscriptionInPeriod) ? self::DO_NOT_APPLY_ANY_FEE : self::DEFAULT_FEE_USER_WITH_SUBSCRIPTION;
    }
}
