<?php

use VanOns\FilamentNavigation\Filament\Fields\NavigationSelect;
use VanOns\FilamentNavigation\Models\Navigation;

it('stores the id of a menu, or the property the app picks', function () {
    $navigation = Navigation::create(['name' => 'Main', 'handle' => 'main', 'items' => []]);

    expect(NavigationSelect::make('navigation_id')->getOptions())->toBe([$navigation->id => 'Main'])
        ->and(NavigationSelect::make('navigation_id')->optionValue('handle')->getOptions())->toBe(['main' => 'Main']);
});
