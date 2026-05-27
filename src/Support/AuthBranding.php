<?php

declare(strict_types=1);

namespace Componist\Auth\Support;

final class AuthBranding
{
    public static function hasLogo(): bool
    {
        return self::logoUrl() !== null;
    }

    public static function logoUrl(): ?string
    {
        $path = config('componist_auth.logo.path');

        if (! is_string($path) || trim($path) === '') {
            return null;
        }

        $path = trim($path);

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    }

    public static function logoAlt(): string
    {
        $alt = config('componist_auth.logo.alt');

        if (is_string($alt) && trim($alt) !== '') {
            return trim($alt);
        }

        return (string) config('app.name', 'Laravel');
    }

    public static function logoHref(): ?string
    {
        $href = config('componist_auth.logo.href');

        if (! is_string($href) || trim($href) === '') {
            return null;
        }

        $href = trim($href);

        if (str_starts_with($href, '/') || filter_var($href, FILTER_VALIDATE_URL)) {
            return $href;
        }

        return route($href);
    }

    public static function logoHeight(): string
    {
        $height = config('componist_auth.logo.height');

        if (is_string($height) && trim($height) !== '') {
            return trim($height);
        }

        return '2.5rem';
    }

    public static function brandName(): string
    {
        $name = config('componist_auth.logo.brand_name');

        if (is_string($name) && trim($name) !== '') {
            return trim($name);
        }

        return (string) config('app.name', 'Laravel');
    }

    public static function showBrandName(): bool
    {
        return (bool) config('componist_auth.logo.show_brand_name', true);
    }
}
