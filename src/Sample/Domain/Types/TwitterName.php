<?php

namespace App\Sample\Domain\Types;

/**
 * Class TwitterName
 *
 * @package App\Sample\Domain\Types
 */
class TwitterName
{
    /**
     * A twitter name of a certain user's
     *
     * @var string
     */
    private $twitterName;

    /**
     * TwitterName constructor's.
     *
     * @param string $twitterName
     * @throws \InvalidArgumentException
     */
    public function __construct(string $twitterName)
    {
        if (empty($twitterName)) {
            throw new \InvalidArgumentException('TwitterName can not be empty.');
        }

        // validation can be really various and I think
        // that it does not really matter right now, so let's stop on that...
        if (strlen($twitterName) < 4) {
            throw new \InvalidArgumentException('TwitterName should be more than 3 symbols.');
        }

        $this->twitterName = $twitterName;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->twitterName;
    }
}
