<?php

declare(strict_types=1);

namespace Componist\Auth\Support;

use Componist\Auth\Contracts\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

final class ComponistAuthConfig
{
    public static function homeRoute(): string
    {
        return self::string('componist_auth.home');
    }

    public static function loginRoute(): string
    {
        return self::string('componist_auth.routes.login');
    }

    public static function verificationNoticeRoute(): string
    {
        return self::string('componist_auth.routes.verification_notice');
    }

    public static function layoutComponent(): string
    {
        return self::string('componist_auth.layouts-app');
    }

    /**
     * @return class-string<Model&TwoFactorAuthenticatable>
     */
    public static function userModel(): string
    {
        $model = self::string('componist_auth.user_model');

        if (! is_subclass_of($model, Model::class)) {
            throw new InvalidArgumentException("Configured user model [{$model}] must extend Eloquent Model.");
        }

        if (! is_subclass_of($model, Authenticatable::class)) {
            throw new InvalidArgumentException("Configured user model [{$model}] must implement Authenticatable.");
        }

        if (! is_subclass_of($model, TwoFactorAuthenticatable::class)) {
            throw new InvalidArgumentException("Configured user model [{$model}] must implement TwoFactorAuthenticatable.");
        }

        return $model;
    }

    public static function verificationEnabled(): bool
    {
        return self::bool('componist_auth.verification');
    }

    public static function twoFactorEnabled(): bool
    {
        return self::bool('componist_auth.two-factor');
    }

    public static function registerEnabled(): bool
    {
        return (bool) config('componist_auth.features.register', false);
    }

    public static function string(string $key): string
    {
        $value = config($key);

        if (! is_string($value) || $value === '') {
            throw new InvalidArgumentException("Configuration value [{$key}] must be a non-empty string.");
        }

        return $value;
    }

    public static function bool(string $key): bool
    {
        return (bool) config($key, false);
    }
}
