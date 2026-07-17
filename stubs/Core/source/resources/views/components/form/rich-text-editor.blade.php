{{--
    Headless TipTap + Alpine.js WYSIWYG editor, themed entirely with this
    kit's own UI — the toolbar is <x-form.rich-text-toolbar :bound="true">
    (see components/ui/richtext/*), so the toolbar's visuals live in exactly
    one place whether it's shown bare on examples/ui/data-display or wired
    to a real editor here. All editor logic (extensions, commands, active-
    state getters) lives in resources/js/rich-text-editor.js, registered as
    the `richTextEditor` Alpine.data component.

    A hidden textarea ($refs.textarea, the one carrying `name`) is kept in
    sync with editor.getHTML() on every update, so the surrounding <form>
    submits the rendered HTML with no extra JS on the caller's side.
--}}
@props([
    'name' => null,
    'id' => null,
    'value' => null,
    'placeholder' => 'Start writing...',
    'minHeight' => 200,
    'maxHeight' => 600,
    'plain' => false,
    'disabled' => false,
])

@php($id = $id ?? 'rte_'.uniqid())

<div
    x-data="richTextEditor(@js(['value' => $value ?? '', 'placeholder' => $placeholder, 'disabled' => $disabled]))"
    {{ $attributes->class(['pointer-events-none opacity-60' => $disabled]) }}
>
    <x-form.rich-text-toolbar :plain="$plain" bound class="w-full" />

    <div
        x-ref="element"
        x-show="! sourceMode"
        @click="focusEnd($event)"
        id="{{ $id }}"
        style="min-height: {{ $minHeight }}px; max-height: {{ $maxHeight }}px"
        class="form-input form-input-default mt-2 cursor-text overflow-y-auto [&_.ProseMirror]:outline-none"
    ></div>

    <div
        x-ref="source"
        x-show="sourceMode"
        x-cloak
        @click="focusSource($event)"
        style="min-height: {{ $minHeight }}px; max-height: {{ $maxHeight }}px"
        class="form-input form-input-default mt-2 cursor-text overflow-y-auto !p-0"
    ></div>

    <textarea x-ref="textarea" class="hidden" @if($name) name="{{ $name }}" @endif>{{ $value }}</textarea>
</div>
