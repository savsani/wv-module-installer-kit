{{--
    Toolbar control for inserting/editing/removing a link — click the link
    icon to open a floating popover (same `ui-popover` surface as
    icon-toggle-dropdown) with a URL field and three actions: apply, open in
    a new tab, remove. Replaces the old window.prompt()-based flow.

    Self-contained `url`/`open` state by default (demo mode,
    examples/ui/data-display). Pass these to wire to a real editor — see
    <x-form.rich-text-toolbar :bound="true">:
      - `sync`      current link href, e.g. "linkHref"
      - `active`    whether the cursor/selection is on a link, e.g. "isActive('link')"
      - `selection` whether there's a non-collapsed selection, e.g. "hasSelection"
      - `apply`     run with `url` in scope to apply it, e.g. "setLink(url)"
      - `unlink`    remove the link, e.g. "unsetLink()"

    The trigger is disabled unless `active` or `selection` is true — there's
    nothing to link with a collapsed cursor that isn't already on a link.
    The popover also opens itself automatically the moment the cursor lands
    on an existing link (tracked via `wasActive`, so it only fires on the
    active→true edge, not on every keystroke while already open) so clicking
    into link text surfaces edit/open/remove immediately, the same way the
    toolbar button does.
--}}
@props([
    'sync' => null,
    'active' => null,
    'selection' => null,
    'apply' => null,
    'unlink' => null,
])

@php
    $canExpr = match (true) {
        $active !== null && $selection !== null => "({$active}) || ({$selection})",
        $active !== null => $active,
        $selection !== null => $selection,
        default => 'true',
    };

    $effectParts = [];
    if ($sync) {
        $effectParts[] = "url = ({$sync}) || '';";
    }
    if ($active) {
        $effectParts[] = "let isLink = ({$active}); if (isLink && ! wasActive) { open = true } if (! isLink && wasActive) { open = false } wasActive = isLink;";
    }
    $effect = implode(' ', $effectParts);
@endphp

<div
    class="relative inline-block"
    x-data="{ open: false, url: '', wasActive: false }"
    @if($effect) x-effect="{!! $effect !!}" @endif
    {{--
        Not a plain @click.outside — the editor's own content lives outside
        this control's DOM too, so a bare "outside" check would force-close
        the popover on every click inside the editor, including clicks that
        just move the cursor to another spot within the same link. Clicks
        landing in the editor content are left for the isActive('link')
        effect above to decide; only a click genuinely outside both this
        popover and the editor closes it here.
    --}}
    @click.window="if (open && ! $el.contains($event.target) && ! $event.target.closest('.ProseMirror')) { open = false }"
    @keydown.escape.window="open = false"
>
    <button
        type="button"
        @click="if ({!! $canExpr !!}) { open = ! open; $nextTick(() => $refs.linkUrlInput?.focus()) }"
        :disabled="! ({!! $canExpr !!})"
        aria-label="Link"
        title="Link"
        aria-haspopup="dialog"
        :aria-expanded="open.toString()"
        x-bind:class="! ({!! $canExpr !!})
            ? 'text-gray-500 dark:text-gray-400 cursor-not-allowed pointer-events-none opacity-40'
            : (open{!! $active ? " || ({$active})" : '' !!})
                ? 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400'
                : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200'"
        class="ui-icon-toggle inline-flex h-9 w-9 shrink-0 cursor-pointer items-center justify-center transition focus:outline-none"
    >
        <x-icon.link class="h-4 w-4" />
    </button>

    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        role="dialog"
        aria-label="Edit link"
        class="ui-popover absolute top-full left-0 z-40 mt-1 flex w-80 items-center gap-1.5 p-1.5"
    >
        <input
            x-ref="linkUrlInput"
            x-model="url"
            @keydown.enter.prevent="{!! $apply ?? '' !!}; open = false"
            type="text"
            placeholder="https://example.com"
            class="form-input form-input-default h-9 min-w-0 flex-1 !py-0 text-sm"
        />

        <button
            type="button"
            @click="{!! $apply ?? '' !!}; open = false"
            aria-label="Apply link"
            title="Apply"
            class="ui-icon-button inline-flex h-9 w-9 shrink-0 cursor-pointer items-center justify-center text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 focus:outline-none dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
        >
            <x-icon.check class="h-3.5 w-3.5" />
        </button>

        <button
            type="button"
            @click="url && window.open(url, '_blank', 'noopener,noreferrer')"
            :disabled="! url"
            aria-label="Open link in new tab"
            title="Open in new tab"
            x-bind:class="! url ? 'cursor-not-allowed opacity-40' : ''"
            class="ui-icon-button inline-flex h-9 w-9 shrink-0 cursor-pointer items-center justify-center text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 focus:outline-none dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
        >
            <x-icon.external-link class="h-4 w-4" />
        </button>

        <button
            type="button"
            @click="{!! $unlink ?? "url = ''" !!}; open = false"
            aria-label="Remove link"
            title="Remove link"
            class="ui-icon-button inline-flex h-9 w-9 shrink-0 cursor-pointer items-center justify-center text-gray-500 transition hover:bg-gray-100 hover:text-red-600 focus:outline-none dark:text-gray-400 dark:hover:bg-red-500/10 dark:hover:text-red-400"
        >
            <x-icon.trash class="h-4 w-4" />
        </button>
    </div>
</div>
