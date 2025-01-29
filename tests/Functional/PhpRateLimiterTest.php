<?php
declare(strict_types=1);

namespace Trukes\PhpRateLimiter\Tests\Functional;

use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Trukes\PhpRateLimiter\PhpRateLimiter;
use Trukes\PhpRateLimiter\Support\Config;
use Trukes\PhpRateLimiter\Tests\InMemory\InMemoryCache;

final class PhpRateLimiterTest extends TestCase
{
    protected CacheInterface $inMemoryCache;

    public function testHitWithNoExistingKey(): void
    {
        $phpRateLimiter = new PhpRateLimiter($this->inMemoryCache, new Config('test_prefix', 10, 10));

        $phpRateLimiter->hit('phpratelimiter.test');

        self::assertTrue($this->inMemoryCache->has('test_prefix:phpratelimiter.test'));
    }

    public function testHitWithExistingKeyAndIncrement(): void
    {
        $phpRateLimiter = new PhpRateLimiter($this->inMemoryCache, new Config('test_prefix', 10, 10));

        $phpRateLimiter->hit('phpratelimiter.test');
        $phpRateLimiter->hit('phpratelimiter.test');

        $data = $this->inMemoryCache->get('test_prefix:phpratelimiter.test');

        self::assertCount(1, $data);

    }

    public function testDeleteKey(): void
    {
        $phpRateLimiter = new PhpRateLimiter($this->inMemoryCache, new Config('test_prefix', 10, 10));

        $phpRateLimiter->hit('phpratelimiter.test');

        $this->inMemoryCache->delete('test_prefix:phpratelimiter.test');

        self::assertEmpty($this->inMemoryCache->get('test_prefix:phpratelimiter.test'));
    }

    public function __construct()
    {
        parent::__construct();

        $this->inMemoryCache = new InMemoryCache();
    }
}