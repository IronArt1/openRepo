<?php

namespace App\Exception;

use Codeception\Util\HttpCode;

/**
 * Class TokenAlgorithmException.
 */
class TokenAlgorithmException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct(
            HttpCode::BAD_REQUEST,
            "A JWT is not valid."
        );
    }
}