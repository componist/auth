<?php

declare(strict_types=1);

namespace Componist\Auth\Support;

use Illuminate\Support\Facades\URL;

/**
 * Builds absolute URLs pinned to config('app.url') so Host-header poisoning
 * cannot rewrite password-reset or email-verification links.
 */
final class CanonicalUrl
{
    /**
     * @param  callable(): string  $generate
     */
    public static function usingAppUrl(callable $generate): string
    {
        $appUrl = rtrim((string) config('app.url'), '/');

        if ($appUrl === '') {
            return $generate();
        }

        URL::forceRootUrl($appUrl);

        try {
            return $generate();
        } finally {
            URL::forceRootUrl('');
        }
    }
}
