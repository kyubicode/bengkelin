<?php

namespace App\Filament\Concerns;

use Illuminate\Support\Str;

trait HasNavigationPermission
{
    public static function getPermissionKey(): string
    {
        return Str::snake(
            Str::plural(str_replace('Resource', '', class_basename(static::class)))
        );
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::userHasAccess();
    }

    public static function canViewAny(): bool
    {
        return static::userHasAccess();
    }

    protected static function userHasAccess(): bool
    {
        $user = auth()->user();

        if (! $user || ! $user->role) {
            return false;
        }

        return $user->role->hasPermission(static::getPermissionKey());
    }
}