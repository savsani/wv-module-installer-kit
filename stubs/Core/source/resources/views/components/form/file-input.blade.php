{{--
    accept      Raw HTML accept attribute, e.g. "application/pdf" or "image/*,.heic"
    extensions  Shorthand for accept, e.g. "pdf,doc,docx" or ['pdf','doc'] — builds `accept` when it isn't set
    maxSize     Max file size in MB, enforced on selection (not just advisory)

    Both extension/mime type and size are validated client-side on change; invalid files
    are rejected (input cleared) and an inline error message is shown.

    The native file input is visually hidden (sr-only) and driven via a <label>, since
    the browser's own `::-webkit-file-upload-button` pseudo-element can't be reliably
    sized to fill a custom-height control — this gives full layout control instead.
--}}
@props([
    'id' => null,
    'name' => null,
    'accept' => null,
    'extensions' => null,
    'maxSize' => null,
    'multiple' => false,
    'disabled' => false,
])

@php
    $id = $id ?? 'file_'.uniqid();

    if (! $accept && $extensions) {
        $extensionList = is_array($extensions) ? $extensions : array_map('trim', explode(',', $extensions));
        $accept = implode(',', array_map(fn ($e) => '.'.ltrim($e, '.'), $extensionList));
    }

    $acceptTokens = $accept ? array_map('trim', explode(',', $accept)) : [];
@endphp

<div
    x-data="{
        error: null,
        fileName: '',
        allowed: @js($acceptTokens),
        maxBytes: {{ $maxSize ? (float) $maxSize * 1024 * 1024 : 'null' }},

        isAllowedType(file) {
            if (!this.allowed.length) return true;
            const name = file.name.toLowerCase();
            return this.allowed.some(token => {
                token = token.toLowerCase();
                if (token.startsWith('.')) return name.endsWith(token);
                if (token.endsWith('/*')) return file.type.startsWith(token.slice(0, -1));
                return file.type === token;
            });
        },

        validate(e) {
            this.error = null;
            this.fileName = '';
            const files = Array.from(e.target.files);

            for (const file of files) {
                if (!this.isAllowedType(file)) {
                    this.error = 'Unsupported file type. Allowed: ' + this.allowed.join(', ');
                    e.target.value = '';
                    return;
                }
                if (this.maxBytes && file.size > this.maxBytes) {
                    this.error = 'File is too large. Max size is {{ $maxSize }}MB.';
                    e.target.value = '';
                    return;
                }
            }

            this.fileName = files.length > 1 ? files.length + ' files selected' : (files[0]?.name ?? '');
        }
    }"
>
    <label
        for="{{ $id }}"
        class="form-input form-input-default-within flex h-11 items-stretch overflow-hidden !p-0 {{ $disabled ? 'cursor-not-allowed opacity-60' : 'cursor-pointer' }}"
    >
        <input
            type="file"
            id="{{ $id }}"
            @if($name) name="{{ $name }}" @endif
            @if($accept) accept="{{ $accept }}" @endif
            @if($multiple) multiple @endif
            @disabled($disabled)
            @change="validate($event)"
            {{ $attributes->merge(['class' => 'sr-only']) }}
        />

        <span class="flex shrink-0 items-center border-r border-gray-300 bg-gray-50 px-3.5 text-sm font-medium text-gray-700 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-200">
            Choose file
        </span>
        <span class="flex min-w-0 flex-1 items-center truncate px-3.5 text-sm text-gray-400 dark:text-gray-500" x-text="fileName || 'No file chosen'"></span>
    </label>

    <p x-show="error" x-cloak x-text="error" class="mt-1.5 text-xs text-red-600 dark:text-red-400"></p>
</div>
