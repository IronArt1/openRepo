<?php

namespace App\Tests\Sample\Application\Controller;

//use Doctrine\ORM\EntityManager;
//use Doctrine\ORM\Tools\SchemaTool;
//use AppBundle\DataFixtures\JobFixtures;
use Symfony\Bundle\FrameworkBundle\Client;
//use AppBundle\DataFixtures\ServiceFixtures;
//use AppBundle\DataFixtures\ZipcodeFixtures;
//use Doctrine\Bundle\FixturesBundle\Fixture;
//use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AbstractControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
abstract class AbstractControllerTest extends WebTestCase
{
    /**
//     * @var EntityManager
     */
//    protected $entityManager;

    /**
     * @var Client
     */
    protected $client;

    /**
//     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function setUp()
    {
        /**
         * In order to avoid calling static::createClient() every time let's use the following
         * Also it could have been used to interact with DB
         */
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
//        $this->entityManager = $container->get('doctrine')->getManager();

//        $schemaTool = new SchemaTool($this->entityManager);
//        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

//        $schemaTool->dropSchema($metadata);
//        $schemaTool->createSchema($metadata);

        $this->client = $container->get('test.client');
    }

    protected function loadServiceFixtures()
    {
        //$this->load(new ServiceFixtures());
    }

//    private function load(Fixture $fixture){
//        return $fixture->load($this->entityManager);
//    }

    public function tearDown()
    {
        parent::tearDown();

//        $purger = new ORMPurger($this->entityManager);
//        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
//        $purger->purge();
    }
}
