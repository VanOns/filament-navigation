<?php

namespace VanOns\FilamentNavigation\Filament\Resources\NavigationResource\Pages;

use Filament\Resources\Pages\EditRecord;
use VanOns\FilamentNavigation\Filament\Resources\NavigationResource\Pages\Concerns\HandlesNavigationBuilder;
use VanOns\FilamentNavigation\FilamentNavigation;

class EditNavigation extends EditRecord
{
    use HandlesNavigationBuilder;

    protected ?array $mountedActionData = null;
    
    public static function getResource(): string
    {
        return FilamentNavigation::get()->getResource();
    }
}
