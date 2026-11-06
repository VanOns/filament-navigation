<?php

use Filament\Schemas\Schema;
use VanOns\FilamentNavigation\Filament\Fields\NavigationSelect;
use VanOns\FilamentNavigation\Models\Navigation;

it('stores the id of a menu, or the property the app picks', function () {
    $navigation = Navigation::create(['name' => 'Main', 'handle' => 'main', 'items' => []]);

    expect(NavigationSelect::make('navigation_id')->container(Schema::make())->getOptions())->toBe([$navigation->id => 'Main'])
        ->and(NavigationSelect::make('navigation_id')->container(Schema::make())->optionValue('handle')->getOptions())->toBe(['main' => 'Main']);
});
