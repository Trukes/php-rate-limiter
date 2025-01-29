<?php
declare(strict_types=1);

namespace Trukes\PhpRateLimiter\Tests\InMemory;

use Psr\SimpleCache\CacheInterface;

final class InMemoryCache implements CacheInterface
{
    private array $items = [];

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->items[$key] ?? null;
    }

    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool
    {
        $this->items[$key] = $value;

        return true;
    }

    public function delete(string $key): bool
    {
        $this->items[$key] = null;

        return true;
    }

    public function clear(): bool
    {
        $this->items = [];

        return true;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        foreach ($this->items as $item) {
            yield $item;
        }
    }

    public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->items[$key] = $value;
        }

        return true;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            $this->items[$key] = null;
        }

        return true;
    }

    public function has(string $key): bool
    {
        return isset($this->items[$key]);
    }
}