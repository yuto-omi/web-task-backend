<?php

namespace App\Shared\Support;

use Closure;
use Illuminate\Support\Facades\Cache;

class PublicCache
{
    private const VERSION_PREFIX = 'public_cache_version';

    /**
     * @param  array<int, string>  $tags
     */
    public static function remember(string $key, array $tags, int $ttlSeconds, Closure $callback): mixed
    {
        if (Cache::supportsTags()) {
            return Cache::tags($tags)->remember($key, $ttlSeconds, $callback);
        }

        $versionedKey = self::versionedKey($key, $tags);

        return Cache::remember($versionedKey, $ttlSeconds, $callback);
    }

    /**
     * @param  array<int, string>  $tags
     */
    public static function invalidate(array $tags): void
    {
        if (Cache::supportsTags()) {
            Cache::tags($tags)->flush();

            return;
        }

        foreach ($tags as $tag) {
            $versionKey = self::versionKey($tag);
            if (Cache::has($versionKey)) {
                Cache::increment($versionKey);

                continue;
            }

            Cache::forever($versionKey, 2);
        }
    }

    /**
     * @param  array<int, string>  $tags
     */
    private static function versionedKey(string $key, array $tags): string
    {
        $versions = array_map(
            static fn (string $tag): int => (int) Cache::get(self::versionKey($tag), 1),
            $tags
        );

        return implode(':', $versions).':'.$key;
    }

    private static function versionKey(string $tag): string
    {
        return self::VERSION_PREFIX.':'.$tag;
    }
}
