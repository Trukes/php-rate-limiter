# Php Rate Limiter

[![Latest Version on Packagist](https://img.shields.io/packagist/v/trukes/php-rate-limiter.svg?style=flat-square)](https://packagist.org/packages/trukes/php-rate-limiter)
[![Total Downloads](https://img.shields.io/packagist/dt/trukes/php-rate-limiter.svg?style=flat-square)](https://packagist.org/packages/trukes/php-rate-limiter)
![GitHub Actions](https://github.com/trukes/php-rate-limiter/actions/workflows/main.yml/badge.svg)

PhpRateLimiter is a simple and flexible rate-limiting library for PHP. It allows you to manage access limits based on custom keys (such as users, IPs, etc.) and can be easily integrated with any injectable storage backend (e.g., Redis, MySQL, etc.). It supports both fixed and sliding time windows. Ideal for controlling API usage and preventing abuse.

## Installation

You can install the package via composer:

```bash
composer require trukes/php-rate-limiter
```

## Usage

```php
use Trukes\PhpRateLimiter\PhpRateLimiter;
use Psr\SimpleCache\CacheInterface;
use Trukes\PhpRateLimiter\Support\Config;
use Trukes\PhpRateLimiter\Exception\LimitExceededException;

// Example with a PSR-16 compatible cache implementation
$cache = // Your cache implementation (e.g., Redis, file-based cache, etc.)
$config = new Config();

// Instantiate the rate limiter
$rateLimiter = new PhpRateLimiter($cache, $config);

$key = 'user_123'; // Custom key (e.g., user ID, IP, etc.)

try {
    $rateLimiter->hit($key); // Increment the hit count
} catch (LimitExceededException $e) {
    echo 'Limit exceeded: ' . $e->getMessage();
}

// Reset the counter for a key
$rateLimiter->reset($key);
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email pedro.m.a.carmo@gmail.com instead of using the issue tracker.

## Credits

-   [Pedro Carmo](https://github.com/trukes)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com) by [Beyond Code](http://beyondco.de/).
