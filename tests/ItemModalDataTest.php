<?php

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Livewire\Livewire;
use VanOns\FilamentNavigation\Filament\Resources\NavigationResource\Pages\CreateNavigation;
use VanOns\FilamentNavigation\FilamentNavigation;

it('fills item field keys when a whole mounted action sync leaves data null', function () {
    $component = Livewire::test(CreateNavigation::class)
        ->set('data.name', 'Foo')
        ->call('createItem');

    $action = $component->get('mountedActions.0');
    $action['data'] = [
        'label' => 'Bar',
        'type' => 'external-link',
        'data' => null,
    ];

    $component
        ->set('mountedActions.0', $action)
        ->assertSet('mountedActions.0.data.data.url', null)
        ->assertSet('mountedActions.0.data.data.target', '')
        ->set('mountedActions.0.data.data.url', '/bar')
        ->assertSet('mountedActions.0.data.data.url', '/bar')
        ->assertSet('mountedActions.0.data.data.target', '');
});

it('fills item field keys when only the type field is updated', function () {
    Livewire::test(CreateNavigation::class)
        ->set('data.name', 'Foo')
        ->call('createItem')
        ->set('mountedActions.0.data.type', 'external-link')
        ->assertSet('mountedActions.0.data.data.url', null)
        ->assertSet('mountedActions.0.data.data.target', '');
});

it('leaves item data alone when the mounted action already has an array', function () {
    $component = Livewire::test(CreateNavigation::class)
        ->set('data.name', 'Foo')
        ->call('createItem');

    $action = $component->get('mountedActions.0');
    $action['data'] = [
        'label' => 'Bar',
        'type' => 'external-link',
        'data' => [
            'url' => '/bar',
            'target' => '_blank',
        ],
    ];

    $component
        ->set('mountedActions.0', $action)
        ->assertSet('mountedActions.0.data.data.url', '/bar')
        ->assertSet('mountedActions.0.data.data.target', '_blank');
});

it('fills nested fields, dotted paths, and closure extra fields', function () {
    FilamentNavigation::get()
        ->itemType('Nested', [
            Section::make()->schema([
                TextInput::make('meta.foo')->default('bar'),
            ]),
        ], 'nested')
        ->withExtraFields(fn () => [
            TextInput::make('note')->default('hello'),
        ]);

    try {
        $component = Livewire::test(CreateNavigation::class)
            ->set('data.name', 'Foo')
            ->call('createItem');

        $action = $component->get('mountedActions.0');
        $action['data'] = [
            'label' => 'Bar',
            'type' => 'nested',
            'data' => null,
        ];

        $component
            ->set('mountedActions.0', $action)
            ->assertSet('mountedActions.0.data.data.meta.foo', 'bar')
            ->assertSet('mountedActions.0.data.data.note', 'hello');
    } finally {
        FilamentNavigation::get()->withExtraFields([]);
    }
});
