<?php
// an example is; don not pay attention to specifics like "namespace" or "use"
namespace App\Sample\Infrastructure\Handlers\ChainOfResponsibilities\RoyaltyFeeHandler;

use Doctrine\ORM\EntityRepository;
use App\User\Entity as UserEntity;
use App\Project\DistributionProjectEntity;
use App\Sample\Infrastructure\Handlers\ChainOfResponsibilities\FeeHandler;
use App\Project\ProjectSpecifyFee\ProjectSpecifyFeeEntity;

class ProjectFeeHandler extends FeeHandler
{
    /**
     * @var EntityRepository
     */
    private $projectFeeRepository;

    /**
     * @var DistributionProjectEntity
     */
    private $project;

    /**
     * @param EntityRepository $projectFeeRepository
     * @param DistributionProjectEntity $project
     * @param Handler|null $successor
     */
    public function __construct(
        EntityRepository $projectFeeRepository,
        DistributionProjectEntity $project,
        Handler $successor = null
    ) {
        parent::__construct($successor);

        $this->project = $project;
        $this->projectFeeRepository = $projectFeeRepository;
    }

    /**
     * @param UserEntity $user
     * @return mixed
     */
    protected function processing(UserEntity $user)
    {
        $projectFee = $this->projectFeeRepository->findOneBy([
            'user' => $user,
            'project' => $this->project
        ]);

        return ($projectFee instanceof ProjectSpecifyFeeEntity) ? $projectFee->getFee() : null;
    }
}
