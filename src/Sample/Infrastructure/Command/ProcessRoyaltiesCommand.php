<?php
namespace App\Sample\Infrastructure\Command;

// use ...

// just an example of usage of "Chain Of Responsibilities" pattern is
class ProcessRoyaltiesCommand extends AbstractCommand
{
/**
 * @param UserEntity $user
 * @param \DateTime $period
 * @param $net
 * @param DistributionProjectEntity $project
 * @param LabelEntity|null $label
 * @param $currency
 * @param $dryRun
 *
 * @return string
 * @throws FeeChainException
 */
protected function takeFee(
    UserEntity $user,
    \DateTime $period,
               $net,
    DistributionProjectEntity $project,
    LabelEntity $label = null,
    $currency,
    $dryRun
) {
    $feePercentage = (new ProjectFeeHandler(
        $this->projectFeeRepository,
        $project,
        new UserFeeHandler(
            $this->userFeeRepository,
            new SubscriptionFeeHandler(
                [
                    'period' => $period,
                    'periodDuration' => '1 month',
                    'requiredCoverage' => '2 weeks'
                ],
                $this->subscriptionRepository
            ))))->handle($user);

    if ($feePercentage <= 0) {
        return $net;
    }

    $feePercentage /= 100;

    $feeAmount = bcmul($net, $feePercentage, UserBalanceEntity::DIGITS_AFTER_COMMA);

    if ($feeAmount <= 0) {
        return $net;
    }

    $result = bcsub($net, $feeAmount, UserBalanceEntity::DIGITS_AFTER_COMMA);

         ///...
    }

}