<?php

namespace VanOns\FilamentNavigation\Filament\Resources;

use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\View;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use VanOns\FilamentNavigation\FilamentNavigation;
use VanOns\FilamentNavigation\Models\Navigation;

class NavigationResource extends Resource
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bars-3';

    protected static bool $showTimestamps = true;

    private static ?string $workNavigationLabel = null;

    private static ?string $workPluralLabel = null;

    private static ?string $workLabel = null;

    public static function disableTimestamps(bool $condition = true): void
    {
        static::$showTimestamps = ! $condition;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('')->schema([
                    TextInput::make('name')
                        ->label(__('filament-navigation::filament-navigation.attributes.name'))
                        ->reactive()
                        ->debounce()
                        ->afterStateUpdated(function (?string $state, Set $set, string $context) {
                            if (! $state) {
                                return;
                            }

                            if ($context === 'create') {
                                $set('handle', Str::slug($state));
                            }
                        })
                        ->required(),
                    Hidden::make('items')
                        ->default([])       
                        ->dehydrated(true),  
                    Fieldset::make(__('filament-navigation::filament-navigation.attributes.items'))
                        ->schema([
                            View::make('filament-navigation::navigation-builder'),
                        ]),
                ])
                    ->columnSpan([
                        12,
                        'lg' => 8,
                    ]),
                Group::make([
                    Section::make('')->schema([
                            TextInput::make('handle')
                                ->label(__('filament-navigation::filament-navigation.attributes.handle'))
                                ->required()
                                ->unique(column: 'handle', ignoreRecord: true),
                            View::make('filament-navigation::card-divider')
                                ->visible(static::$showTimestamps),
                            TextEntry::make('created_at_display')
                                ->label(__('filament-navigation::filament-navigation.attributes.created_at'))
                                ->visible(static::$showTimestamps)
                                ->state(fn (?Navigation $record) => $record?->created_at)
                                ->dateTime()
                                ->placeholder('—'),
                            TextEntry::make('updated_at_display')
                                ->label(__('filament-navigation::filament-navigation.attributes.updated_at'))
                                ->visible(static::$showTimestamps)
                                ->state(fn (?Navigation $record) => $record?->updated_at)
                                ->dateTime()
                                ->placeholder('—'),
                        ]),
                ])
                    ->columnSpan([
                        12,
                        'lg' => 4,
                    ]),
            ])
            ->columns(12);
    }

    public static function navigationLabel(?string $string): void
    {
        self::$workNavigationLabel = $string;
    }

    public static function pluralLabel(?string $string): void
    {
        self::$workPluralLabel = $string;
    }

    public static function label(?string $string): void
    {
        self::$workLabel = $string;
    }

    public static function getNavigationLabel(): string
    {
        return self::$workNavigationLabel ?? parent::getNavigationLabel();
    }

    public static function getModelLabel(): string
    {
        return self::$workLabel ?? parent::getModelLabel();
    }

    public static function getPluralModelLabel(): string
    {
        return self::$workPluralLabel ?? parent::getPluralModelLabel();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament-navigation::filament-navigation.attributes.name'))
                    ->searchable(),
                TextColumn::make('handle')
                    ->label(__('filament-navigation::filament-navigation.attributes.handle'))
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label(__('filament-navigation::filament-navigation.attributes.created_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(__('filament-navigation::filament-navigation.attributes.updated_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make()
                    ->icon(null),
                DeleteAction::make()
                    ->icon(null),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => NavigationResource\Pages\ListNavigations::route('/'),
            'create' => NavigationResource\Pages\CreateNavigation::route('/create'),
            'edit' => NavigationResource\Pages\EditNavigation::route('/{record}'),
        ];
    }

    public static function getModel(): string
    {
        return FilamentNavigation::get()->getModel();
    }
}
