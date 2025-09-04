<?php

namespace VanOns\FilamentNavigation\Filament\Fields;

use Filament\Forms\Components\Select;
use VanOns\FilamentNavigation\Models\Navigation;

class NavigationSelect extends Select
{
    protected string $optionValueProperty = 'id';

    protected function setUp(): void
    {
        parent::setUp();

        $this->options(function (NavigationSelect $component) {
            return Navigation::pluck('name', $component->getOptionValueProperty());
        });
    }

    public function getOptionValueProperty(): string
    {
        return $this->optionValueProperty;
    }
}
