## Filament Navigation (`van-ons/filament-navigation`)

A Filament plugin with a `Navigation` resource for building nested menus with drag and drop. Each menu is one row in the `navigations` table (`name`, unique `handle`, `items` as JSON); the site reads it with `Navigation::fromHandle()` and renders it itself. Van Ons' fork of the archived `ryangjchandler/filament-navigation`: v2 is for Filament 4 and 5, v1 for Filament 3. The service provider is auto-discovered.

### Setup

@verbatim
<code-snippet name="Install" lang="bash">
composer require van-ons/filament-navigation:^2.0
php artisan migrate          # migrations load from the package, nothing to publish
php artisan filament:assets  # the builder's CSS and JS
</code-snippet>

<code-snippet name="Register the plugin" lang="php">
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use VanOns\FilamentNavigation\FilamentNavigation;
use VanOns\FilamentNavigation\Filament\Resources\NavigationResource;

public function panel(Panel $panel): Panel
{
    NavigationResource::navigationGroup('Content');

    return $panel->plugin(
        FilamentNavigation::make()
            ->itemType('Page', [
                Select::make('page_id')->options(fn () => Page::pluck('title', 'id'))->required(),
            ])
            ->withExtraFields([
                TextInput::make('classes'),
            ])
    );
}
</code-snippet>
@endverbatim

Coming from `ryangjchandler/filament-navigation`: swap the package and replace the `RyanChandler\FilamentNavigation\` namespace with `VanOns\FilamentNavigation\`.

### Public API

| Class | Purpose |
|---|---|
| `VanOns\FilamentNavigation\FilamentNavigation` | The plugin (id `navigation`). `::make()`, `::get()`, `->itemType(string $name, array\|Closure $fields, ?string $slug = null)`, `->withExtraFields(array\|Closure $schema)`, `->usingModel(class-string)`, `->usingResource(class-string)`. |
| `VanOns\FilamentNavigation\Models\Navigation` | Eloquent model, `$guarded = []`, `items` cast to an array. `Navigation::fromHandle(string $handle): ?static`. |
| `VanOns\FilamentNavigation\Filament\Resources\NavigationResource` | Static setters, called from a service provider or the panel provider: `navigationGroup()`, `navigationSort()`, `navigationIcon()` (from Filament), `navigationLabel()`, `label()`, `pluralLabel()`, `disableTimestamps()`. |
| `VanOns\FilamentNavigation\Filament\Fields\NavigationSelect` | A Filament `Select` of all menus (`name`), storing the menu `id`. |

### Data structure

@verbatim
<code-snippet name="items as stored" lang="json">
{
    "0b6c3f6e-...": {
        "label": "About",
        "type": "external-link",
        "data": { "url": "/about", "target": "" },
        "children": {
            "5f2e9a1d-...": { "label": "Team", "type": "page", "data": { "page_id": 3, "classes": null }, "children": [] }
        }
    }
}
</code-snippet>

<code-snippet name="Render a menu" lang="blade">
@php($menu = \VanOns\FilamentNavigation\Models\Navigation::fromHandle('main-menu'))

<ul>
    @foreach ($menu?->items ?? [] as $item)
        <li>
            <a href="{{ $item['data']['url'] ?? '#' }}" @if (($item['data']['target'] ?? '') === '_blank') target="_blank" @endif>{{ $item['label'] }}</a>
            {{-- recurse into $item['children'] the same way --}}
        </li>
    @endforeach
</ul>
</code-snippet>
@endverbatim

### Conventions and gotchas

- **Items are keyed by UUID**, not a list, and so are `children`. Iterate with `foreach`; do not index with `[0]`. Every item has `label`, `type`, `data` and `children`, which is stored as `[]` when it has none.
- `type` is the item type's slug: `external-link` for the built-in one (fields `url`, `target`), otherwise the `$slug` given to `itemType()` or `Str::slug($name)` (`'Blog post'` → `blog-post`). Resolve each type's `data` into a URL in the app, for instance in a view component or a method on a custom model.
- The fields of an item type and the `withExtraFields()` fields are all stored under `data`, so keep their names distinct.
- `fromHandle()` returns `null` for an unknown handle; handle that on the site. The handle is filled from the name only when a menu is created.
- `config/filament-navigation.php` (`navigation_model`, `navigation_resource`) is not loaded or published by the service provider. Swap the model or resource with `usingModel()` / `usingResource()` on the plugin; a custom resource extends `NavigationResource`.
- `NavigationSelect` always lists the package `Navigation` model and stores the `id`. It has no `optionValue()` method, despite the docs; to store the handle, use a plain `Select` with `Navigation::pluck('name', 'handle')`.
- `FilamentNavigation::get()` resolves the plugin from the current panel, so the resource and its pages only work in a panel that registered it.
- Override texts in `lang/vendor/filament-navigation/{locale}/filament-navigation.php` and views in `resources/views/vendor/filament-navigation/`; the package has no publish tags for them.
