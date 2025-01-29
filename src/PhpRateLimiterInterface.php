<?php
declare(strict_types=1);

namespace Trukes\PhpRateLimiter;


interface PhpRateLimiterInterface
{
    public function hit(string $key, int $incremental = 1): void;

    public function reset(string $key): void;
}