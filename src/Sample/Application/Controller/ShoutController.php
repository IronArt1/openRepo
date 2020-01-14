<?php

namespace App\Sample\Application\Controller;

use App\Sample\Domain\Types\{
    TwitterName,
    TweetLimit
};
use Symfony\Component\HttpFoundation\{
    Request,
    JsonResponse
};
use App\Sample\Infrastructure\Command\{
    CommandBus,
    ShoutCommand
};
use App\Sample\Infrastructure\Formatter\UpperFormatter;
use App\Sample\Infrastructure\Repository\TweetRepositoryInMemory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ShoutController
 *
 * @package App\Sample\Application\Controller
 */
final class ShoutController extends AbstractController
{
    /**
     * Gets a certain amount of tweets by a tweeter name
     *
     * @param Request $request
     * @param string $twitterName
     * @param UpperFormatter $upperFormatter
     * @param TweetRepositoryInMemory $tweetRepositoryInMemory
     *
     * @return JsonResponse
     * @throws \ReflectionException
     */
    public function index(
        Request $request,
        string $twitterName,
        UpperFormatter $upperFormatter,
        TweetRepositoryInMemory $tweetRepositoryInMemory
    ) {
        $commandBus = new CommandBus(
            new TwitterName($twitterName),
            new TweetLimit((int) $request->query->get('limit'))
        );

        $shoutCommand = new ShoutCommand($upperFormatter, $tweetRepositoryInMemory);

        $response = $commandBus->run($shoutCommand);

        $response = new JsonResponse($response);
        $response->setEncodingOptions($response->getEncodingOptions() | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return $response;

        /**
         * I like the thing with 'group' but it can not be implemented here...
         */
//        return $this->json(
//            $response,
//            200,
//            [],
//            [
//            //    'groups' => ['main']
//            ]
//        );
    }
}
