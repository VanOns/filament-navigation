<?php

use Livewire\Livewire;
use VanOns\FilamentNavigation\Filament\Resources\NavigationResource\Pages\CreateNavigation;

it('seeds item field keys when a whole mounted action sync leaves data null', function () {
    $component = Livewire::test(CreateNavigation::class)
        ->set('data.name', 'Foo')
        ->call('createItem');

    $instance = $component->instance();
    $instance->mountedActions[0]['data'] = [
        'label' => 'Bar',
        'type' => 'external-link',
        'data' => null,
    ];

    $instance->updatedInteractsWithSchemas('mountedActions.0');

    expect($instance->mountedActions[0]['data']['data'])
        ->toBeArray()
        ->toMatchArray([
            'url' => null,
            'target' => null,
        ]);
});

it('leaves item data alone when the mounted action already has an array', function () {
    $component = Livewire::test(CreateNavigation::class)
        ->set('data.name', 'Foo')
        ->call('createItem');

    $instance = $component->instance();
    $instance->mountedActions[0]['data'] = [
        'label' => 'Bar',
        'type' => 'external-link',
        'data' => [
            'url' => '/bar',
            'target' => '_blank',
        ],
    ];

    $instance->updatedInteractsWithSchemas('mountedActions.0');

    expect($instance->mountedActions[0]['data']['data'])->toMatchArray([
        'url' => '/bar',
        'target' => '_blank',
    ]);
});
