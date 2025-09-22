<?php

namespace VanOns\FilamentNavigation\Filament\Resources\NavigationResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use VanOns\FilamentNavigation\Filament\Resources\NavigationResource\Pages\Concerns\HandlesNavigationBuilder;
use VanOns\FilamentNavigation\FilamentNavigation;
use VanOns\FilamentNavigation\Traits\FixesLivewireErrorBag;

class CreateNavigation extends CreateRecord
{
    use HandlesNavigationBuilder;
    use FixesLivewireErrorBag;

    public static function getResource(): string
    {
        return FilamentNavigation::get()->getResource();
    }
}
