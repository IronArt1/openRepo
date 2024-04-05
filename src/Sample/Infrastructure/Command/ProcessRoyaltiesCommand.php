<?php
namespace App\Sample\Infrastructure\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\Sample\Infrastructure\Handlers\ChainOfResponsibilities\RoyaltyFeeHandler\ProjectFeeHandler;
use App\Sample\Infrastructure\Handlers\ChainOfResponsibilities\RoyaltyFeeHandler\SubscriptionFeeHandler;
use App\Sample\Infrastructure\Handlers\ChainOfResponsibilities\RoyaltyFeeHandler\UserFeeHandler;

class ProcessRoyaltiesCommand extends Command
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('test')
            ->addOption('test2', null, InputOption::VALUE_REQUIRED, 'Test description is')
            ->setDescription('test3');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        // initialize some variables here
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        // an example of transactional approach. Any dependencies, like RoyaltyFileEntity would be not
        // present here since there is no need in particular:
        $output->writeln(sprintf('Saving <info>%s</info>', 'test'));
        $ingroovesFile = new RoyaltyFileEntity(
            md5_file('test path'),
            RoyaltyFileEntity::typeFromExtension('test ext'),
            'some period',
            PlatformRegistry::PLATFORM__INGROOVES
        );
        $ingroovesFile->setParent('some parent');
        $ingroovesFile->setState(RoyaltyFileEntity::STATE__IN_PROGRESS);
        $ingroovesFile->setPath('some path');
        $ingroovesFile->setServerId('some server id');

        $workerServerId = 3; // some random number is
        $this->entityManager->transactional(function() use ($ingroovesFile, $workerServerId) {
            $this->entityManager->persist($ingroovesFile);
            $this->entityManager->flush($ingroovesFile);
            $bssTaskParams = $this->bssTaskParamsFactory->createImportRawReportFromFile(
                $ingroovesFile->getId()
            );
            $this->bssClient->addTask(
                $workerServerId,
                BSSClient::BSS_TASK_TYPE_ROYALTY,
                $bssTaskParams
            );
        });

        $output->writeln('Processing tasks were added');
    }

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
            // this is an example of so-called "Chain Of Responsibility" PHP pattern
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
            //...

            // the following is the most complex raw SQL query statement I've created. (don't mind the absence of DQL here)
            // that is just to show I can deal with complex SQL (don't mind setting up all parameters in a straight manner)
            $sql = "SELECT
                rr.artist,
                IF (user_profile.billing_country_code is NULL, 'unknown', user_profile.billing_country_code) as 'Geography',
                GROUP_CONCAT(DISTINCT rr.rp SEPARATOR ', ') as RP,
                GROUP_CONCAT(DISTINCT genres.title SEPARATOR ', ') as 'Genre',
                rr.period,
                SUM(rr.gross) as gross,
                SUM(rr.net) as net,
                SUM(rr.fee) as fee_summary,
                rr.currency
                FROM (  
                    SELECT
                        royalty_report.id,
                        royalty_report.project_id,
                        royalty_report.user_id as artist,
                        (IF (royalty_report.gross_revenue = 0,
                                   IF(royalty_report.user_id = 57100,
                                      royalty_report.amount,
                                      (royalty_report.amount * IF(
                                          (select 
                                               count(id) 
                                           from user_subscription as us 
                                           where us.user_id = royalty_report.user_id 
                                             and us.expired_at > royalty_report.period 
                                             and us.created_at < royalty_report.period
                                          ) > 0,
                                          1,
                                          100 / 92)
                                      )),
                                  royalty_report.gross_revenue)
                        ) AS gross,
                        IF (royalty_report.user_id = 57100, 
                            IF(royalty_report.gross_revenue = 0, royalty_report.amount * 0.5, royalty_report.gross_revenue * 0.5),
                            royalty_report.amount
                        ) as net,
                        (royalty_report.amount * IF(royalty_report.user_id = 57100, 0.5,
                            IF(
                                (
                                    select count(id) 
                                    from user_subscription as us 
                                    where us.user_id = royalty_report.user_id 
                                      and us.expired_at > royalty_report.period 
                                      and us.created_at < royalty_report.period
                                ) > 0,
                                IF(
                                    (
                                        select count(pp.id) 
                                        from projects AS pp
                                        where pp.id=royalty_report.project_id 
                                          and pp.migration_status=2
                                    ) > 0,
                                    0.1, # 10% migrated subscribers
                                    0 # 0% subscribers
                                ),
                                IF(
                                    (
                                        select count(pp.id) 
                                        from projects AS pp 
                                        where pp.id=royalty_report.project_id 
                                          and pp.migration_status=2
                                    ) > 0,
                                    0.18, # 18% migrated nonsubscribers
                                    0.08 # 8% nonsubscribers
                                )
                            )
                        )) AS fee, # we increased fees by 10% of NET
                        (IF(royalty_report.user_id = 57100, '50%',
                            IF(
                                (
                                    select count(id) 
                                    from user_subscription as us 
                                    where us.user_id = royalty_report.user_id 
                                        and us.expired_at > royalty_report.period 
                                        and us.created_at < royalty_report.period
                                ) > 0,
                                IF(
                                    (   
                                        select count(pp.id) 
                                        from projects AS pp 
                                        where pp.id=royalty_report.project_id 
                                            and pp.migration_status=2
                                    ) > 0,
                                    '10%', # 10% migrated subscribers
                                    '0%'   # 0% subscribers
                                ),
                                IF(
                                    (
                                        select count(pp.id) 
                                        from projects AS pp 
                                        where pp.id=royalty_report.project_id 
                                            and pp.migration_status=2
                                    ) > 0,
                                    '18%', # 18% migrated nonsubscribers
                                    '8%'  # 8% nonsubscribers
                                )
                            )
                        )) as rp,
                        DATE_FORMAT(royalty_report.period,'%Y-%m') as period,
                        royalty_report.currency,
                        p.genre_id as genre
                    FROM
                        royalty_report
                    left JOIN projects as p ON royalty_report.project_id = p.id    
                    WHERE
                        DATE_FORMAT(period, '%Y') = '2022'
                        and royalty_report.amount > 0
                ) as rr
            LEFT JOIN genres ON rr.genre = genres.id
            LEFT JOIN user_profile ON rr.artist = user_profile.user_id
            GROUP BY rr.artist, rr.period, rr.currency;";

            $stmt = $this->entityManager->getConnection()->prepare($sql);
            $stmt->execute([
                // ALL parameters from the query above must be obviously here, for example:
                ResourceEntity::TYPE_DISTRIBUTION_TRACK,
                ProjectEntity::TYPE_DISTRIBUTION,
                $user->getId(),
                ParticipatorEntity::TYPE_MAIN_ARTIST,
                ParticipatorEntity::TYPE_LEGACY_MAIN_ARTIST,
                ParticipatorEntity::TYPE_PAY_PER_PROJECT_ARTIST,
                DistributionProjectEntity::STATUS_TAKE_DOWN
                // ...
            ]);

            // and another type of SQL is just in case:
            $sql = "SET @date = '2023-04-15';
                SET @period = 'P04 23(Apr 23)';
                SELECT
                    rr1.period,
                    SUM(IF(
                        rr1.currency='usd', 
                        (ir1.ir_gross - IF(rr1.ingrooves_fee_percent > 0, ir1.ir_gross * rr1.ingrooves_fee_percent / 100, 0)) * 0.08,
                        ''
                    )) as fee_usd,
                    SUM(IF(
                        rr1.currency='eur',
                        (ir1.ir_gross - IF(rr1.ingrooves_fee_percent > 0, ir1.ir_gross * rr1.ingrooves_fee_percent / 100, 0)) * IF(rr1.user_id=57100, 0.5, 0.08),
                         ''
                    )) as fee_eur
                FROM royalty_report as rr1
                LEFT JOIN (
                    SELECT
                        ir.royalty_report_id,
                        SUM(ir.usd_net_revenue_to_client) as ir_gross,
                        SUM(ir.usd_net_revenue_to_client) - SUM(ir.usd_net_revenue_to_client) * 0.08 as ir_calculated_net_revenue
                    FROM ingrooves_royalty ir
                    WHERE ir.period = @period
                    GROUP BY ir.royalty_report_id
                ) as ir1 on rr1.id = ir1.royalty_report_id
                WHERE DATE_FORMAT(rr1.period, '%Y-%m') = DATE_FORMAT(@date, '%Y-%m')
                  and rr1.user_id not in (
                    SELECT distinct us.user_id
                    FROM user_subscription AS us
                    WHERE us.start_at < DATE_SUB(@date, INTERVAL 1 MONTH)
                      AND us.expired_at > DATE_FORMAT(@date, '%Y-%m-%d')
                      AND us.type in('music_distribution', 'm_a_p', 'mondotunes_radio')
                      AND us.user_id != 57100
                ) GROUP BY rr1.currency;";

            return $stmt->fetchAll();
    }
}