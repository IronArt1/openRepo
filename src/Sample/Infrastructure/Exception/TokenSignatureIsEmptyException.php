<?php

namespace App\Sample\Infrastructure\Exception;

use Codeception\Util\HttpCode;

/**
 * Class TokenSignatureIsEmptyException.
 */
class TokenSignatureIsEmptyException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct(
            HttpCode::UNAUTHORIZED,
            "A JWT must have a signature."
        );
    }
}
