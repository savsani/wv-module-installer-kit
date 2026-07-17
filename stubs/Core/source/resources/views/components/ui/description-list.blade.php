{{--
    Label/value rows for a detail or "view" panel:

        <x-ui.description-list>
            <x-ui.description-list.item label="Category">{{ $product->category }}</x-ui.description-list.item>
            <x-ui.description-list.item label="Price"><span x-text="'$' + viewRow.price"></span></x-ui.description-list.item>
        </x-ui.description-list>
--}}
<dl {{ $attributes->merge(['class' => 'space-y-3 text-sm']) }}>
    {{ $slot }}
</dl>
