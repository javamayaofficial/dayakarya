<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class IntegrationSettings
{
    private const CACHE_KEY = 'dayakarya.integration-settings';

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::all()[$key] ?? $default;
    }

    public static function setMany(array $settings): void
    {
        if (! static::tableExists()) {
            return;
        }

        DB::transaction(function () use ($settings): void {
            foreach ($settings as $key => $value) {
                Setting::query()->updateOrCreate(
                    ['key' => $key],
                    ['value' => static::normalize($value)]
                );
            }
        });

        Cache::forget(self::CACHE_KEY);
    }

    public static function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function (): array {
            if (! static::tableExists()) {
                return [];
            }

            return Setting::query()
                ->orderBy('key')
                ->pluck('value', 'key')
                ->all();
        });
    }

    private static function normalize(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        return trim((string) $value);
    }

    private static function tableExists(): bool
    {
        try {
            return Schema::hasTable('settings');
        } catch (Throwable) {
            return false;
        }
    }
}
