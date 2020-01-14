<?php

namespace App\Tests\Unit\Sample\Infrastructure\Repository;


class TweetRepositoryInMemoryTest extends \Codeception\Test\Unit
{
    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * A test case's.
     */
    public function testProcessing()
{
    $upperFormatter = new UpperFormatter();
    $result = $upperFormatter->applyUpperCaseAndExclamationMark(self::DOT_CASE);

    // assert that your formatter applied all necessary changes!
    $this->assertEquals(self::TEST_RESULT, $result);
}
}