{{--
    Cover image uploader with an optional Cropper.js crop step, reusing this
    kit's own <x-form.file-input> for selection and <x-ui.modal> for the crop
    dialog. The file picker is name-less (never submitted) — its native
    `change` event is caught on the wrapping div and handed to `onPick()`,
    which reads whatever is left in `event.target.files`: file-input's own
    validate() already cleared it before this bubbles up if the type/size was
    rejected, so no duplicate validation lives here. A separate hidden input
    carries the real `name` and is submitted; its FileList is populated
    programmatically with either the cropped result or, when cropping is
    disabled, the original file untouched — see resources/js/cover-image.js.

    The picker is wrapped in `<template x-if>` rather than plain `x-show` so
    removing an image gives it a fresh Alpine/DOM instance instead of
    leaving a stale filename in file-input's own local state.

    <x-form.cover-image name="cover_image" :aspect-ratio="16 / 9" :crop-width="1280" :crop-height="720" />

    Pass `:crop-enabled="false"` to skip the crop modal entirely (plain
    upload + preview + remove). Pass `:aspect-ratio="null"` (the default)
    for a freeform crop selection — the preview thumbnail itself falls back
    to a 16:9 shape when no aspect ratio is set, purely for a consistent
    layout.
--}}
@props([
    'name' => null,
    'id' => null,
    'value' => null,
    'aspectRatio' => 16 / 9,
    'cropWidth' => null,
    'cropHeight' => null,
    'cropEnabled' => true,
    'accept' => null,
    'extensions' => null,
    'maxSize' => null,
    'disabled' => false,
])

@php
    $id ??= 'cover_image_'.uniqid();
    $previewRatio = $aspectRatio ?? (16 / 9);

    if (! $accept && ! $extensions) {
        $accept = 'image/*';
    }
@endphp

<div
    x-data="coverImage({
        value: @js($value),
        aspectRatio: @js($aspectRatio),
        cropWidth: @js($cropWidth),
        cropHeight: @js($cropHeight),
        cropEnabled: @js($cropEnabled),
    })"
    {{ $attributes }}
>
    <div
        x-show="previewUrl"
        x-cloak
        class="relative inline-block max-w-full overflow-hidden rounded-control border border-gray-300 bg-gray-50 dark:border-gray-700 dark:bg-gray-800/50"
        style="aspect-ratio: {{ $previewRatio }}; height: 8rem"
    >
        <img :src="previewUrl" class="h-full w-full object-cover" alt="Cover image preview" />

        <button
            type="button"
            @click="remove()"
            @disabled($disabled)
            aria-label="Remove cover image"
            class="absolute right-1.5 top-1.5 flex h-6 w-6 items-center justify-center rounded-pill bg-black/50 text-white transition hover:bg-red-600 focus:outline-none disabled:cursor-not-allowed disabled:opacity-60"
        >
            <x-icon.x-mark class="h-3.5 w-3.5" />
        </button>
    </div>

    <template x-if="!previewUrl">
        <div @change="onPick($event)">
            <x-form.file-input
                id="{{ $id }}"
                :accept="$accept"
                :extensions="$extensions"
                :max-size="$maxSize"
                :disabled="$disabled"
            />
        </div>
    </template>

    <p x-show="error" x-cloak x-text="error" class="mt-1.5 text-xs text-red-600 dark:text-red-400"></p>

    <input type="file" x-ref="output" @if($name) name="{{ $name }}" @endif class="hidden" />

    <x-ui.modal
        show="cropModalOpen"
        title="Crop cover image"
        description="Drag to reposition, use the handles to resize the selection, then apply."
        max-width="lg"
        :close-on-backdrop="false"
        on-close="cancelCrop()"
    >
        <div class="h-[420px] w-full overflow-hidden rounded-control bg-gray-100 dark:bg-gray-800/60">
            <img x-ref="cropperImage" alt="Image to crop" class="hidden" />
        </div>

        <x-slot:footer>
            <x-ui.button variant="secondary" style="outline" type="button" @click="cancelCrop()">Cancel</x-ui.button>
            <x-ui.button variant="primary" type="button" @click="applyCrop()">Apply crop</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
