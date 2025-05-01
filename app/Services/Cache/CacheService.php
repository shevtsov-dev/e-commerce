<?php

declare(strict_types=1);

namespace App\Services\Cache;

use Closure;
use DateTime;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * @param string   $key
     * @param DateTime $expiration
     * @param Closure  $callback
     *
     * @return mixed
     */
    public function remember(string $key, DateTime $expiration, Closure  $callback): mixed
    {
        return Cache::remember($key, $expiration, $callback);
    }

    /**
     * @param string   $key
     * @param mixed    $value
     * @param DateTime $expiration
     *
     * @return void
     */
    public function put(string $key, mixed $value, DateTime $expiration): void
    {
        Cache::put($key, $value, $expiration);
    }
}
