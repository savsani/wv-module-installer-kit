@props([
    'name' => null,
    'id' => null,
    'rows' => 5,
    'placeholder' => null,
    'value' => null,
    'error' => false,
    'success' => false,
])

@php
    $stateClass = $error ? 'form-input-error' : ($success ? 'form-input-success' : 'form-input-default');
@endphp

<textarea
    @if($name) name="{{ $name }}" @endif
    @if($id) id="{{ $id }}" @endif
    rows="{{ $rows }}"
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    {{ $attributes->merge(['class' => "form-input {$stateClass}"]) }}
>{{ $value }}</textarea>
