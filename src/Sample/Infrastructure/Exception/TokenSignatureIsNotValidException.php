<?php

namespace App\Sample\Infrastructure\Exception;

use Codeception\Util\HttpCode;

/**
 * Class TokenSignatureIsNotValidException.
 */
class TokenSignatureIsNotValidException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct(
            HttpCode::UNAUTHORIZED,
            "A JWT signature is not valid."
        );
    }
}
