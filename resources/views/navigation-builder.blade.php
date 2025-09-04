<x-filament::fieldset class="filament-navigation">
    <div wire:key="navigation-items-wrapper">
        <div
            class="space-y-2"
            x-data="navigationSortableContainer({
                statePath: @js('data.items')
            })"
            data-sortable-container
        >
            @php
                $items = $get('items') ?? [];
            @endphp

            @forelse($items as $uuid => $item)
                <x-filament-navigation::nav-item :statePath="'data.items.' . $uuid" :item="$item" />
            @empty
                <div @class([
                    'w-full bg-white rounded-lg border border-gray-300 px-3 py-2 text-left',
                    'dark:bg-[#242426] dark:border-[#464648]',
                ])>
                    {{ __('filament-navigation::filament-navigation.items.empty') }}
                </div>
            @endforelse
        </div>
    </div>

    <div class="flex justify-end mt-2">
        <x-filament::button wire:click="createItem" type="button" size="sm">
            {{ __('filament-navigation::filament-navigation.items.add-item') }}
        </x-filament::button>
    </div>
</x-filament::fieldset>
