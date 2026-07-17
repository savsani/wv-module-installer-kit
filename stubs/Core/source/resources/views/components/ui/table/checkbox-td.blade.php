{{--
    Row checkbox cell for bulk-select tables — reuses the same checkbox
    visual as <x-form.checkbox> (via <x-form.checkbox-box>). `model` is a
    raw Alpine expression:

        <x-ui.table.checkbox-td model="isSelected(product.id)" @change="toggleSelect(product.id)" />
--}}
@props(['model'])

<td class="w-10 px-4 py-4">
    <label class="flex cursor-pointer items-center justify-center">
        <input
            type="checkbox"
            x-bind:checked="{!! $model !!}"
            {{ $attributes->merge(['class' => 'sr-only']) }}
        />
        <x-form.checkbox-box :checked="$model" />
    </label>
</td>
