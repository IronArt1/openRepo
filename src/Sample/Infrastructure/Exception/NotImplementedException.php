<?php

namespace App\Sample\Infrastructure\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class NotImplementedException's
 *
 * @package App\Sample\Infrastructure\Exception
 */
class NotImplementedException extends HttpException
{
    public function __construct()
    {
        parent::__construct(
            Response::HTTP_NOT_IMPLEMENTED,
            "Please, install/enable APCu extension."
        );
    }
}
