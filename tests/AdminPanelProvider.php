<?php

namespace VanOns\FilamentNavigation\Tests;

use Filament\Panel;
use Filament\PanelProvider;
use VanOns\FilamentNavigation\FilamentNavigation;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->plugin(FilamentNavigation::make());
    }
}
