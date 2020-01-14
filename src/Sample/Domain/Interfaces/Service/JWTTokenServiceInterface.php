<?php

namespace App\Sample\Domain\Interfaces\Service;

/**
 * Interface JWTTokenServiceInterface
 * @package App\Interfaces\Service
 */
interface JWTTokenServiceInterface
{
    public const HEADER = [
        "alg" => "HS256",
        "typ" => "JWT"
    ];

    /**
     * Generate a JWT for a customer
     *
     * @param array $payload
     * @return string
     */
    public function generateToken(array $payload): string;

    /**
     * Verify a signature
     *
     * @param array $parts
     * @return \stdClass
     */
    public function verifySignature(array $parts): \stdClass;

    /**
     * Since it is a preferable way to deal with a Hash it has been copied from lcobucci/jwt library
     *
     * PHP < 5.6 timing attack safe hash comparison
     *
     * @param string $expected
     * @param string $generated
     *
     * @return bool
     */
    public function hashEquals($expected, $generated): bool;
}
