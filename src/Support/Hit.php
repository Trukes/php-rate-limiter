<?php
declare(strict_types=1);

namespace Trukes\PhpRateLimiter\Support;

use DateTimeInterface;
use DateTimeImmutable;
use InvalidArgumentException;

final class Hit
{
    private function __construct(private string $key, private int $hits, private DateTimeInterface $lastTime)
    {
    }

    public function key(): string
    {
        return $this->key;
    }

    public function hits(): int
    {
        return $this->hits;
    }

    public function lastTime(): DateTimeInterface
    {
        return $this->lastTime;
    }

    public static function fromArray(array $value): self
    {
        if(!array_key_exists('key', $value)){
            throw new InvalidArgumentException('The key is required.');
        }

        return new self(
            key: $value['key'],
            hits: $value['hits'] ?? 0,
            lastTime: $value['lastTime'] ?? new DateTimeImmutable(),
        );
    }
}