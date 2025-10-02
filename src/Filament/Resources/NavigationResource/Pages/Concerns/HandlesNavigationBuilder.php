<?php

namespace VanOns\FilamentNavigation\Filament\Resources\NavigationResource\Pages\Concerns;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use VanOns\FilamentNavigation\FilamentNavigation;

trait HandlesNavigationBuilder
{
    public ?string $mountedItem = null;

    public array $mountedItemData = [];

    public ?string $mountedChildTarget = null;

    public function sortNavigation(string $targetStatePath, array $targetItemsStatePaths): void
    {
        $items = [];

        foreach ($targetItemsStatePaths as $targetItemStatePath) {
            $item = data_get($this, $targetItemStatePath);
            $uuid = Str::afterLast($targetItemStatePath, '.');

            $items[$uuid] = $item;
        }

        data_set($this, $targetStatePath, $items);
    }

    public function addChild(string $statePath): void
    {
        $this->mountedChildTarget = $statePath;

        $this->mountedItem = null;
        $this->mountedActionData = [];
        
        $this->mountAction('item');
    }

    public function removeItem(string $statePath): void
    {
        $uuid = Str::afterLast($statePath, '.');

        $parentPath = Str::beforeLast($statePath, '.');
        $parent = data_get($this, $parentPath);

        data_set($this, $parentPath, Arr::except($parent, $uuid));
    }

    public function editItem(string $statePath): void
    {
        $this->mountedItem = $statePath;
        $this->mountedItemData = Arr::except(data_get($this, $statePath), 'children');

        $this->mountAction('item');
    }

    public function createItem(): void
    {
        $this->mountedItem = null;
        $this->mountedItemData = [];

        $this->mountAction('item');
    }

    protected function getActions(): array
    {
        return [
            Action::make('item')
                ->mountUsing(function (Schema $schema) {
                    if (! $this->mountedItem) {
                        return;
                    }

                    $schema->fill($this->mountedItemData);
                })
                ->view('filament-navigation::hidden-action')
                ->schema([
                    TextInput::make('label')
                        ->label(__('filament-navigation::filament-navigation.items-modal.label'))
                        ->required(),
                    Select::make('type')
                        ->label(__('filament-navigation::filament-navigation.items-modal.type'))
                        ->options(function () {
                            $types = FilamentNavigation::get()->getItemTypes();

                            return array_combine(array_keys($types), Arr::pluck($types, 'name'));
                        })
                        ->afterStateUpdated(function ($state, Select $component, Set $set): void {
                            if (! $state) {
                                return;
                            }

                            $set('data', []);
                        })
                        ->reactive(),
                    Group::make()
                        ->statePath('data')
                        ->whenTruthy('type')
                        ->schema(function (Get $get) {
                            $type = $get('type');

                            return FilamentNavigation::get()->getItemTypes()[$type]['fields'] ?? [];
                        }),
                    Group::make()
                        ->statePath('data')
                        ->visible(fn (Component $component) => $component->evaluate(FilamentNavigation::get()->getExtraFields()) !== [])
                        ->schema(fn () => FilamentNavigation::get()->getExtraFields()),
                ])
                ->modalWidth('md')
                ->action(function (array $data) {
                    if ($this->mountedItem) {
                        data_set($this, $this->mountedItem, array_merge(data_get($this, $this->mountedItem), $data));

                        $this->mountedItem = null;
                        $this->mountedItemData = [];
                    } elseif ($this->mountedChildTarget) {
                        $children = data_get($this, $this->mountedChildTarget . '.children', []);

                        $children[(string) Str::uuid()] = [
                            ...$data,
                            ...['children' => []],
                        ];

                        data_set($this, $this->mountedChildTarget . '.children', $children);

                        $this->mountedChildTarget = null;
                    } else {
                        $this->data['items'][(string) Str::uuid()] = [
                            ...$data,
                            ...['children' => []],
                        ];
                    }
                })
                ->modalSubmitActionLabel(__('filament-navigation::filament-navigation.items-modal.btn'))
                ->label(__('filament-navigation::filament-navigation.items-modal.title')),
        ];
    }
}
