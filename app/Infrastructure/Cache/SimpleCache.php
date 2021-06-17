<?php
namespace App\Infrastructure\Cache;

use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\CacheInterface;

class SimpleCache implements CacheInterface
{
    public function get($key, $default = null)
    {
        return Cache::get($key, $default);
    }

    public function set($key, $value, $ttl = null): bool
    {
        return Cache::put($key, $value, $ttl);
    }

    public function delete($key): bool
    {
        return Cache::forget($key);
    }

    public function clear(): bool
    {
        return false;
    }

    public function getMultiple($keys, $default = null): array
    {
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = $this->get($key, $default);
        }

        return $data;
    }

    public function setMultiple($values, $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }

        return true;
    }

    public function deleteMultiple($keys): bool
    {
        foreach ($keys as $key) {
            if (!$this->delete($key)) {
                return false;
            }
        }

        return true;
    }

    public function has($key): bool
    {
        return Cache::has($key);
    }
}
