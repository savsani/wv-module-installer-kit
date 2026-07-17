@props([
    'name' => null,
    'id' => null,
    'value' => null,
    'format' => 'DD/MM/YYYY',
    'error' => false,
    'success' => false,
])

@php
    $stateClass = $error ? 'form-input-error' : ($success ? 'form-input-success' : 'form-input-default');
    $separator = str_contains($format, '-') ? '-' : '/';
    $tokens = explode($separator, $format);
    $segmentLength = fn ($token) => $token === 'YYYY' ? 4 : 2;

    $displayValue = '';
    if ($value) {
        [$y, $m, $d] = array_map('intval', explode('-', $value));
        $parts = ['DD' => sprintf('%02d', $d), 'MM' => sprintf('%02d', $m), 'YYYY' => sprintf('%04d', $y)];
        $displayValue = collect($tokens)->map(fn ($token) => $parts[$token])->implode($separator);
    }
@endphp

<div
    class="relative"
    x-data="{
        separator: @js($separator),
        tokens: @js($tokens),
        raw: @js($displayValue),
        iso: @js($value ?? ''),
        segmentLength(token) { return token === 'YYYY' ? 4 : 2; },
        digitsOnly(str) { return str.replace(/\D/g, '').slice(0, 8); },
        formatDigits(digits) {
            let result = '';
            let pos = 0;
            for (const token of this.tokens) {
                const len = this.segmentLength(token);
                let segment = digits.slice(pos, pos + len);
                if (!segment) break;
                if (segment.length === len) {
                    let num = parseInt(segment, 10);
                    if (token === 'DD') num = Math.min(Math.max(num, 1), 31);
                    if (token === 'MM') num = Math.min(Math.max(num, 1), 12);
                    segment = String(num).padStart(len, '0');
                }
                result += (result ? this.separator : '') + segment;
                pos += len;
            }
            return result;
        },
        syncIso() {
            const digits = this.digitsOnly(this.raw);
            if (digits.length < 8) { this.iso = ''; return; }
            const parts = {};
            let pos = 0;
            for (const token of this.tokens) {
                const len = this.segmentLength(token);
                parts[token] = digits.slice(pos, pos + len);
                pos += len;
            }
            this.iso = `${parts.YYYY}-${parts.MM}-${parts.DD}`;
        },
        onInput(e) {
            this.raw = this.formatDigits(this.digitsOnly(e.target.value));
            e.target.value = this.raw;
            this.syncIso();
        },
    }"
>
    <input type="hidden" @if($name) name="{{ $name }}" @endif :value="iso" />

    <input
        type="text"
        inputmode="numeric"
        autocomplete="off"
        @if($id) id="{{ $id }}" @endif
        :value="raw"
        @input="onInput($event)"
        placeholder="{{ $format }}"
        {{ $attributes->merge(['class' => "form-input {$stateClass} h-11 !pr-4"]) }}
    />
</div>
