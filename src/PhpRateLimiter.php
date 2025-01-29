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
 * A lightweight, simple and flexible rate-limiting library for PHP.
 * It allows you to manage access limits based on custom keys (such as users, IPs, etc.)
 * using any injectable storage backend (e.g., Redis, MySQL, etc.).
 * It supports both fixed and sliding time windows, and can be adapted to any storage system
 * that implements the CacheInterface from PSR-16.
 * Ideal for controlling API usage and preventing abuse.
 *
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
