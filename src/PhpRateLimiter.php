<?php
declare(strict_types=1);

namespace Trukes\PhpRateLimiter;

use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Trukes\PhpRateLimiter\Exception\LimitExceededException;
use Trukes\PhpRateLimiter\Support\Config;
use Trukes\PhpRateLimiter\Support\Hit;

/**
 * @author Pedro Carmo
 *
 * A lightweight, flexible rate-limiting library for PHP. Manage access limits by user,
 * IP, or custom keys with support for sliding and fixed windows. Compatible with Redis,
 * MySQL, and file-based storage. Ideal for controlling API usage and preventing abuse.
 */
final class PhpRateLimiter implements PhpRateLimiterInterface
{
    /**
     * @param CacheInterface $cache
     * @param Config $config
     */
    public function __construct(private CacheInterface $cache, private Config $config)
    {
    }

    /**
     * @param string $key
     * @param int $incremental
     * @return void
     * @throws InvalidArgumentException
     */
    public function hit(string $key, int $incremental = 1): void
    {
        $cacheItem = $this->cache->get($this->getFullNameKey($key)) ?? [];

        $hit = Hit::fromArray(
            [
                ...$cacheItem,
                'key' => $key
            ]
        );

        if($hit->hits() >= $this->config->maxRequestsByTimeframe()){
            throw new LimitExceededException('Limit Exceeded');
        }

        $this->cache->set($this->getFullNameKey($key), ['hits' => $hit->hits() + $incremental], $this->config->timeframeInSeconds());
    }


    /**
     * @param string $key
     * @return void
     * @throws InvalidArgumentException
     */
    public function reset(string $key): void
    {
        $this->cache->delete($this->getFullNameKey($key));
    }

    /**
     * @param string $key
     * @return string
     */
    private function getFullNameKey(string $key): string
    {
        return sprintf('%s:%s', $this->config->prefix(), $key);
    }
}
