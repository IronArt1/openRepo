<?php

namespace App\Tests\Sample\Application\Controller;

use App\Sample\Infrastructure\Traits\ApcuTrait;


/**
 * Class ShoutControllerTest's
 *
 * @package App\Tests\Sample\Application\Controller
 */
class ShoutControllerTest extends AbstractControllerTest
{
    use ApcuTrait;

    /**
     * It's a case when we have a response of 5 tweets,
     * all of characters are in upper case and the last symbol is !
     */
    public function testPositiveScenario(): void
    {
        //$client = static::createClient();
        $this->client->request('GET', 'http://www.my.sample.com/shout/Trump?limit=5');

        $response = $this->client->getResponse();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($response->getContent());

        $content = json_decode($response->getContent());
        $this->assertIsArray($content);
        $this->assertEquals(5, count($content));

        foreach ($content as $entry) {
            preg_match('~[A-Z\W]+~u', $entry, $matched);
            $this->assertEquals(strlen($entry), strlen($matched[0]));
            $this->assertEquals('!', substr($entry, -1));
        }
    }

    /**
     * It's a case when we have maximum amount of tweets - 10
     */
    public function testMaxAmountScenario(): void
    {
        //$client = static::createClient();
        $this->client->request('GET', 'http://www.my.sample.com/shout/Melania?limit=10');

        $response = $this->client->getResponse();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($response->getContent());

        $content = json_decode($response->getContent());
        $this->assertIsArray($content);
        $this->assertEquals(10, count($content));

        foreach ($content as $entry) {
            preg_match('~[A-Z\W]+~u', $entry, $matched);
            $this->assertEquals(strlen($entry), strlen($matched[0]));
            $this->assertEquals('!', substr($entry, -1));
        }
    }

    /**
     * It's a case when we have maximum amount of tweets - 1
     */
    public function testMinAmountScenario(): void
    {
        //$client = static::createClient();
        $this->client->request('GET', 'http://www.my.sample.com/shout/Donald?limit=1');

        $response = $this->client->getResponse();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($response->getContent());

        $content = json_decode($response->getContent());
        $this->assertIsArray($content);
        $this->assertEquals(1, count($content));

        foreach ($content as $entry) {
            preg_match('~[A-Z\W]+~u', $entry, $matched);
            $this->assertEquals(strlen($entry), strlen($matched[0]));
            $this->assertEquals('!', substr($entry, -1));
        }
    }

    /**
     * It's a case when we have not integer as limit
     */
    public function testWrongIntegerScenario(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('A limit of tweets must be equal or less than 10.');

        //$client = static::createClient();
        $this->client->request('GET', 'http://www.my.sample.com/shout/Trump?limit=D');
    }

    /**
     * It's a case when we have zero amount of tweets
     */
    public function testZeroAmountScenario(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('A limit of tweets must be equal or less than 10.');

        //$client = static::createClient();
        $this->client->request('GET', 'http://www.my.sample.com/shout/Trump?limit=0');
    }

    /**
     * It's a case when we have limit more than 10
     */
    public function testLimitMoreThan10(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('A limit of tweets must be equal or less than 10.');

        //$client = static::createClient();
        $this->client->request('GET', 'http://www.my.sample.com/shout/Trump?limit=11');
    }

    /**
     * It's a case when we have limit more than 10
     */
    public function testLimitLessThan0(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('A limit of tweets must be equal or less than 10.');

        //$client = static::createClient();
        $this->client->request('GET', 'http://www.my.sample.com/shout/Trump?limit=-1');
    }

    /**
     * It's a case when we have twitter name empty
     */
    public function testTwitterName(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('TwitterName should be more than 3 symbols.');

        //$client = static::createClient();
        $this->client->request('GET', 'http://www.my.sample.com/shout/" "?limit=5');
    }

    /**
     * A test case for calling different collections of tweets'
     */
    public function testPositiveScenarioForDifferentTwitterNames(): void
    {
        //$client = static::createClient();
        $this->client->request('GET', 'http://www.my.sample.com/shout/Trump?limit=5');

        $response = $this->client->getResponse();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($response->getContent());

        $content = json_decode($response->getContent());
        $this->assertIsArray($content);
        $this->assertEquals(5, count($content));

        $this->client->request('GET', 'http://www.my.sample.com/shout/Melania?limit=5');

        $response = $this->client->getResponse();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($response->getContent());

        $contentSecond = json_decode($response->getContent());
        $this->assertIsArray($contentSecond);
        $this->assertEquals(5, count($contentSecond));

        $this->assertNotEquals($content, $contentSecond);
    }

    /**
     * It's a case when we have username empty
     */
    public function testWrongTwitterName(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('Sorry Hillary does not have any tweets yet.');

        //$client = static::createClient();
        $this->client->request('GET', 'http://www.my.sample.com/shout/Hillary?limit=5');
    }

    /**
     * It's a case for APCu's storing ability
     */
    public function testAPCuScenario(): void
    {
        //$client = static::createClient();
        $this->client->request('GET', 'http://www.my.sample.com/shout/Trump?limit=5');

        $response = $this->client->getResponse();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($response->getContent());

        $content = json_decode($response->getContent());
        $this->assertIsArray($content);
        $this->assertEquals(5, count($content));

        $this->assertTrue($this->have('Trump5'));
        foreach ($this->fetch(['Trump5']) as $key => $value) {
            $cached = unserialize($value);
        }
        $this->assertEquals($content, $cached);
    }

    /**
     * It's a case for APCu's storing ability during 30 seconds
     */
    public function testAPCu30secondsScenario(): void
    {
        //$client = static::createClient();
        $this->client->request('GET', 'http://www.my.sample.com/shout/Eric?limit=5');

        $response = $this->client->getResponse();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($response->getContent());

        $content = json_decode($response->getContent());
        $this->assertIsArray($content);
        $this->assertEquals(5, count($content));

        sleep(30);
        $this->assertTrue($this->have('Eric5'));
        foreach ($this->fetch(['Eric5']) as $key => $value) {
            $cached = unserialize($value);
        }
        $this->assertEquals($content, $cached);
    }

    /**
     * It's a case for APCu's storing ability during 60 seconds
     */
    public function testAPCu60secondsScenario(): void
    {
        //$client = static::createClient();
        $this->client->request('GET', 'http://www.my.sample.com/shout/Donald?limit=5');

        $response = $this->client->getResponse();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($response->getContent());

        $content = json_decode($response->getContent());
        $this->assertIsArray($content);
        $this->assertEquals(5, count($content));

        sleep(60);
        $this->assertTrue($this->have('Donald5'));
        foreach ($this->fetch(['Donald5']) as $key => $value) {
            $cached = unserialize($value);
        }
        $this->assertEquals($content, $cached);
    }

    /**
     * It's a case for APCu's storing ability during 150 seconds
     */
    public function testAPCu150secondsScenario(): void
    {
        //$client = static::createClient();
        $this->client->request('GET', 'http://www.my.sample.com/shout/Tiffany?limit=5');

        $response = $this->client->getResponse();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($response->getContent());

        $content = json_decode($response->getContent());
        $this->assertIsArray($content);
        $this->assertEquals(5, count($content));
        $this->assertTrue($this->have('Tiffany5'));
        foreach ($this->fetch(['Tiffany5']) as $key => $value) {
            $cached = unserialize($value);
        }
        $this->assertEquals($content, $cached);

        sleep(150);
        $this->fetch(['Tiffany5']);
        /**
         * Skipping negative scenario since APCu has some issues with cleaning properly itself
         * when PHP_SAPI === cli
         */
        // $this->assertFalse($this->have('Tiffany5'));
    }
}
