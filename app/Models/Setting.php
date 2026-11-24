<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public const THEME_KEY = 'theme';

    public const THEME_LIGHT = 'light';

    public const THEME_DARK = 'dark';

    /**
     * @var array<int, string>
     */
    public const ALLOWED_THEMES = [
        self::THEME_LIGHT,
        self::THEME_DARK,
    ];

    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, ?string $default = null): ?string
    {
        $setting = static::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);

        if ($key === self::THEME_KEY) {
            self::resetThemeCache();
        }
    }

    public static function forget(string $key): void
    {
        static::where('key', $key)->delete();

        if ($key === self::THEME_KEY) {
            self::resetThemeCache();
        }
    }

    public static function getSiteTheme(bool $fresh = false): string
    {
        $request = app()->bound('request') ? request() : null;

        if (! $fresh && $request && $request->attributes->has('_site_theme')) {
            return (string) $request->attributes->get('_site_theme');
        }

        try {
            $value = static::get(self::THEME_KEY, self::THEME_LIGHT);
        } catch (\Throwable $e) {
            return self::THEME_LIGHT;
        }

        $normalized = is_string($value) ? strtolower(trim($value)) : self::THEME_LIGHT;
        $resolved = in_array($normalized, self::ALLOWED_THEMES, true)
            ? $normalized
            : self::THEME_LIGHT;

        if ($request) {
            $request->attributes->set('_site_theme', $resolved);
        }

        return $resolved;
    }

    public static function setSiteTheme(string $theme): string
    {
        $normalized = strtolower(trim($theme));
        if (! in_array($normalized, self::ALLOWED_THEMES, true)) {
            throw new \InvalidArgumentException('Theme must be either light or dark.');
        }

        static::set(self::THEME_KEY, $normalized);

        if (app()->bound('request')) {
            request()->attributes->set('_site_theme', $normalized);
        }

        return $normalized;
    }

    public static function resetThemeCache(): void
    {
        if (app()->bound('request')) {
            request()->attributes->remove('_site_theme');
        }
    }
}
