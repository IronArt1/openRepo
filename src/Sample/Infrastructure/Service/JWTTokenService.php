<?php

namespace App\Service;

// This example of codding has nothing to do with current test application. 
// The only purpose is just to have a glimpse on binary stuff.

//use App\Exception\{
//    TokenAlgorithmException,
//    TokenSignatureIsEmptyException,
//    TokenSignatureIsNotValidException
//};
use App\Sample\Domain\Interfaces\Service\JWTTokenServiceInterface;

/**
 * Class JWTTokenService
 * An example of a service is that properly works with JWT tokens.
 *
 * @package App\Service
 */
class JWTTokenService implements JWTTokenServiceInterface
{
    /**
     * A configuration parameter
     *
     * @var
     */
    private $JWTPrivateKey;

    /**
     * JWTTokenService constructor.
     */
    public function __construct($JWTPrivateKey)
    {
        $this->JWTPrivateKey = $JWTPrivateKey;
    }

    /**
     * Generate a JWT for a customer
     *
     * @param array $payload
     * @return string
     */
    public function generateToken(array $payload): string
    {
        //generate a header
        $header = $this->base64url_encode(json_encode(self::HEADER));

        // generate a payload
        $payload += ['exp' => time() + 2*60*60];
        $payload =  $this->base64url_encode(json_encode($payload));

        // generate a signature
        $signature = $this->generateSignature($header, $payload);

        return "$header.$payload.$signature";
    }

    /**
     * Generate a JWT signature
     *
     * @param string $header
     * @param string $payload
     * @return string
     */
    private function generateSignature(string $header, string $payload): string
    {
        return hash_hmac('sha256', "$header.$payload", $this->JWTPrivateKey);
    }

    /**
     * A 'base64url' variant encoding's
     *
     * @param $data
     * @return string
     */
    private function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * A 'base64url' variant decoding's
     *
     * @param $data
     * @return bool|string
     */
    public function base64url_decode($data)
    {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4 ));
    }

    /**
     * Verify a signature
     *
     * @param array $parts
     * @return \stdClass
     */
    public function verifySignature(array $parts): \stdClass
    {
        $header =  json_decode(base64_decode($parts[0]));

        if (
            !isset(json_decode(base64_decode($parts[0]))->alg)
            || static::HEADER['alg'] !== json_decode(base64_decode($parts[0]))->alg
        ) {
            //throw new TokenAlgorithmException();
        }

        if (empty($parts[2])) {
            //throw new TokenSignatureIsEmptyException();
        }

        $hash = $this->generateSignature($parts[0], $parts[1]);

        if (!$this->hashEquals($parts[2], $hash)) {
            //throw new TokenSignatureIsNotValidException();
        };

        return json_decode($this->base64url_decode($parts[1]));
    }

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
    public function hashEquals($expected, $generated): bool
    {
        $expectedLength = strlen($expected);

        if ($expectedLength !== strlen($generated)) {
            return false;
        }

        $res = 0;

        for ($i = 0; $i < $expectedLength; ++$i) {
            $res |= ord($expected[$i]) ^ ord($generated[$i]);
        }

        return $res === 0;
    }
}
