<?php
declare(strict_types=1);

namespace Trukes\PhpRateLimiter\Support;

final class Config
{
    public function __construct(private string $prefix, private int $maxRequestsByTimeframe, private int $timeframeInSeconds)
    {
    }

    public function prefix(): string
    {
        return $this->prefix;
    }

    public function maxRequestsByTimeframe(): int
    {
        return $this->maxRequestsByTimeframe;
    }

    public function timeframeInSeconds(): int
    {
        return $this->timeframeInSeconds;
    }
}
