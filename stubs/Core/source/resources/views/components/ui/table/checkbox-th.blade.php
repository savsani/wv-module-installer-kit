{{--
    Header "select all" checkbox for bulk-select tables — reuses the same
    checkbox visual as <x-form.checkbox> (via <x-form.checkbox-box>).
    `model`/`indeterminate` are raw Alpine expressions:

        <x-ui.table.checkbox-th model="allSelected" indeterminate="someSelected" @change="toggleSelectAll()" />
--}}
@props(['model', 'indeterminate' => null])

<th scope="col" class="w-10 px-4 py-3.5">
    <label class="flex cursor-pointer items-center justify-center">
        <input
            type="checkbox"
            x-bind:checked="{!! $model !!}"
            @if($indeterminate) x-bind:indeterminate="{!! $indeterminate !!}" @endif
            {{ $attributes->merge(['class' => 'sr-only']) }}
        />
        <x-form.checkbox-box :checked="$model" :indeterminate="$indeterminate" />
    </label>
</th>
