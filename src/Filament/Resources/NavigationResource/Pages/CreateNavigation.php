<?php

namespace VanOns\FilamentNavigation\Filament\Resources\NavigationResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use VanOns\FilamentNavigation\Filament\Resources\NavigationResource\Pages\Concerns\HandlesNavigationBuilder;
use VanOns\FilamentNavigation\FilamentNavigation;

class CreateNavigation extends CreateRecord
{
    use HandlesNavigationBuilder;

    protected ?array $mountedActionData = null;

    public static function getResource(): string
    {
        return FilamentNavigation::get()->getResource();
    }
}
