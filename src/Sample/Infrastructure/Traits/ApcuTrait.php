<?php

namespace App\Sample\Infrastructure\Traits;

/**
 * Trait ApcuTrait's
 * That's a copycat of Symfony's ApcuTrait,
 * since we do not have to invent a bicycle again
 * why bother to write something similar...
 *
 * @package App\Sample\Infrastructure\Traits
 */
trait ApcuTrait
{
    /**
     * Checking if APCu is enabled/supported
     *
     * @return bool
     */
    public static function isSupported(): bool
    {
        // may be there are more checks required here...
        return \function_exists('apcu_fetch') && ini_get('apc.enabled');
    }

    /**
     * Fetches values from APCu cache
     *
     * @param array $ids
     *
     * @return \Generator
     * @throws \ErrorException
     */
    protected function fetch(array $ids): iterable
    {
        try {
            foreach (apcu_fetch($ids, $ok) ?: array() as $k => $v) {
                if (null !== $v || $ok) {
                    yield $k => $v;
                }
            }
        } catch (\Error $e) {
            throw new \ErrorException(
                $e->getMessage(),
                $e->getCode(),
                E_ERROR,
                $e->getFile(),
                $e->getLine()
            );
        }
    }

    /**
     * Checks if APCu has a value in cache.
     *
     * @param $id
     * @return bool|string[]
     */
    protected function have($id)
    {
        return apcu_exists($id);
    }

    /**
     * Saves values into APCu cache.
     *
     * @param array $values
     * @param $lifetime
     *
     * @return array
     * @throws \Throwable
     */
    protected function save(array $values, $lifetime): array
    {
        try {
            if (false === $failures = apcu_store($values, null, $lifetime)) {
                $failures = $values;
            }

            return array_keys($failures);
        } catch (\Throwable $e) {
            if (1 === \count($values)) {
                apcu_delete(key($values));
            }

            throw $e;
        }
    }
}
