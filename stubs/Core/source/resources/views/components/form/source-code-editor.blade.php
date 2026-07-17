{{--
    Pure CodeMirror 6 HTML/CSS/JS source editor — no toolbar, no Tiptap, no
    schema parsing. Use this instead of <x-form.rich-text-editor> when the
    field needs to accept and preserve raw markup byte-for-byte (custom
    <style>/<script> blocks, arbitrary tags/attributes) rather than
    normalizing to a rich-text schema.

    SECURITY — read before wiring this to a real feature:
    - Storing the value is fine: a plain TEXT/LONGTEXT column, no special
      encoding. Storage isn't where the risk lives.
    - The risk is entirely on render: this field is expected to contain
      <script> tags and arbitrary HTML. Never output it with Blade's raw
      {!! !!} into a normal page — that's stored XSS against whoever views
      that page (and if it ever renders on an authenticated/admin page,
      that's a hijackable admin session, not just a defaced page).
    - Render it inside a sandboxed iframe instead:
      <iframe sandbox="allow-scripts" srcdoc="{{ $html }}"></iframe>
      Omit `allow-same-origin` from the sandbox list — combined with
      allow-scripts, allow-same-origin lets the sandboxed content unwrap
      the sandbox entirely and read the parent page's cookies/DOM, which
      defeats the isolation.
    - If script execution should never be allowed at all (some rendering
      contexts, e.g. transactional email), sanitize on the way out with a
      library like HTMLPurifier rather than relying on this editor's input
      to be trustworthy.
    - Whoever can reach this field is, by construction, able to run
      JavaScript in the context wherever it gets rendered — restrict access
      to that field/feature accordingly (authorization, not just auth).
--}}
@props([
    'name' => null,
    'id' => null,
    'value' => null,
    'minHeight' => 300,
    'maxHeight' => 600,
    'disabled' => false,
])

@php($id = $id ?? 'sce_'.uniqid())

<div
    x-data="sourceCodeEditor(@js(['value' => $value ?? '', 'disabled' => $disabled]))"
    {{ $attributes->class(['pointer-events-none opacity-60' => $disabled]) }}
>
    <div
        x-ref="element"
        @click="focusEditor($event)"
        id="{{ $id }}"
        style="min-height: {{ $minHeight }}px; max-height: {{ $maxHeight }}px"
        class="form-input form-input-default cursor-text overflow-y-auto !p-0"
    ></div>

    <textarea x-ref="textarea" class="hidden" @if($name) name="{{ $name }}" @endif>{{ $value }}</textarea>
</div>
