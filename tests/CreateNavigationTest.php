<?php

use Livewire\Livewire;
use Pest\Expectation;

use function Pest\Laravel\assertDatabaseHas;

use VanOns\FilamentNavigation\Filament\Resources\NavigationResource\Pages\CreateNavigation;
use VanOns\FilamentNavigation\Models\Navigation;

it('can create a navigation menu', function () {
    Livewire::test(CreateNavigation::class)
        ->set('data.name', 'Foo')
        ->call('create')
        ->assertHasNoErrors();

    assertDatabaseHas(Navigation::class, [
        'name' => 'Foo',
        'handle' => 'foo',
    ]);
});

it('can create a navigation menu with items', function () {
    Livewire::test(CreateNavigation::class)
        ->set('data.name', 'Foo')
        ->call('createItem')
        ->set('mountedActions.0.data', [
            'label' => 'Bar',
            'type' => 'external-link',
            'data' => [
                'url' => '/bar',
            ],
        ])
        ->call('callMountedAction')
        ->call('create')
        ->assertHasNoErrors()
        ->assertSuccessful();

    expect(Navigation::first())
        ->toBeInstanceOf(Navigation::class)
        ->name->toBe('Foo')
        ->handle->toBe('foo')
        ->items
            ->toHaveLength(1)
            ->sequence(
                fn (Expectation $item) => $item
                    ->toHaveKey('label', 'Bar')
                    ->toHaveKey('type', 'external-link')
                    ->data->toMatchArray([
                        'url' => '/bar',
                    ])
            );
});

it('registers the type-dependent data fields as soon as a type is selected', function () {
    $component = Livewire::test(CreateNavigation::class)
        ->set('data.name', 'Foo')
        ->call('createItem')
        ->set('mountedActions.0.data.type', 'external-link');

    expect($component->get('mountedActions.0.data.data'))
        ->toHaveKey('url')
        ->toHaveKey('target');
});
