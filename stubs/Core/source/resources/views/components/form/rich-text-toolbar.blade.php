{{--
    Fully assembled rich-text toolbar, composed from the icon-toggle
    primitives in components/ui/richtext/ (icon-toggle, icon-toggle-group,
    icon-toggle-divider, icon-toggle-dropdown, icon-toggle-radio-group).

    `plain` (default true) toggles the toolbar's own background — see
    <x-ui.richtext.icon-toggle-group>.

    `bound` (default false) wires every control's `command`/`sync` props to
    the ambient Alpine scope of <x-form.rich-text-editor> instead of each
    control's self-managed local state — e.g. Bold becomes
    command="toggleBold()" sync="isActive('bold')" instead of a local
    `active` flag that just flips on click. Leave `bound` false (the
    default) to use this toolbar as a plain UI demo with no editor behind
    it, as on examples/ui/data-display.
--}}
@props(['plain' => true, 'bound' => false])

@php
    // Returns the given Alpine expression only when bound to a real editor,
    // otherwise null so every control falls back to its own local state.
    $w = fn (?string $expr) => $bound ? $expr : null;

    $b = [
        'bold' => ['command' => $w('toggleBold()'), 'sync' => $w('isActive(\'bold\')')],
        'italic' => ['command' => $w('toggleItalic()'), 'sync' => $w('isActive(\'italic\')')],
        'underline' => ['command' => $w('toggleUnderline()'), 'sync' => $w('isActive(\'underline\')')],
        'strike' => ['command' => $w('toggleStrike()'), 'sync' => $w('isActive(\'strike\')')],
        'superscript' => ['command' => $w('toggleSuperscript()'), 'sync' => $w('isActive(\'superscript\')')],
        'subscript' => ['command' => $w('toggleSubscript()'), 'sync' => $w('isActive(\'subscript\')')],
        'bulletList' => ['command' => $w('toggleBulletList()'), 'sync' => $w('isActive(\'bulletList\')')],
        'orderedList' => ['command' => $w('toggleOrderedList()'), 'sync' => $w('isActive(\'orderedList\')')],
        'link' => [
            'sync' => $w('linkHref'),
            'active' => $w('isActive(\'link\')'),
            'selection' => $w('hasSelection'),
            'apply' => $w('setLink(url)'),
            'unlink' => $w('unsetLink()'),
        ],
        'source' => ['command' => $w('toggleSource()'), 'sync' => $w('sourceMode')],
        'align' => ['sync' => $w('align')],
        'heading' => ['sync' => $w('headingValue')],
        'fontSize' => ['sync' => $w('fontSizeValue')],
        'color' => ['sync' => $w('colorValue'), 'apply' => $w('setColor($event.detail.value)')],
        'highlight' => ['sync' => $w('highlightValue'), 'apply' => $w('setHighlight($event.detail.value)')],
    ];

    $alignCmds = [
        'left' => $w('setAlign(\'left\')'),
        'center' => $w('setAlign(\'center\')'),
        'right' => $w('setAlign(\'right\')'),
        'justify' => $w('setAlign(\'justify\')'),
    ];

    $headingCmds = [
        'paragraph' => $w('setHeading(\'paragraph\')'),
        'heading-1' => $w('setHeading(\'heading-1\')'),
        'heading-2' => $w('setHeading(\'heading-2\')'),
        'heading-3' => $w('setHeading(\'heading-3\')'),
        'heading-4' => $w('setHeading(\'heading-4\')'),
        'blockquote' => $w('setHeading(\'blockquote\')'),
        'codeblock' => $w('setHeading(\'codeblock\')'),
    ];

    // Component tags can't carry a bare @if/@endif in their attribute list
    // (Blade's tag compiler chokes on it), so undo/redo's click/disabled/class
    // expressions are pre-resolved to plain strings here instead — a no-op
    // click and an always-false disabled check when not bound. `disabled`
    // and `class` use the `x-bind:` alias rather than Blade's own `:` prop
    // shorthand — `disabled` isn't a declared prop on <x-ui.icon-button>, so
    // a bare `:disabled=` would get PHP-evaluated by Blade and flattened
    // into a literal (always-truthy) HTML attribute instead of reaching
    // Alpine as a live binding.
    $undoRedo = fn (string $command, string $can) => [
        'click' => $bound ? $command : '',
        'disabled' => $bound ? "! {$can}" : 'false',
        'class' => $bound ? "! {$can} ? 'pointer-events-none opacity-40' : ''" : "''",
    ];
    $undo = $undoRedo('undo()', 'canUndo');
    $redo = $undoRedo('redo()', 'canRedo');
@endphp

<x-ui.richtext.icon-toggle-group :plain="$plain" {{ $attributes }}>
    {{--
        Every control except the Source code view toggle itself lives inside
        this wrapper so it can go inert while source mode is active — none of
        them apply to raw HTML. `display: contents` (Tailwind's `contents`)
        keeps the extra div transparent to the icon-toggle-group's flex
        layout, but that also means the div paints no box of its own — an
        opacity class here would have nothing to render onto. The dimming is
        a plain CSS rule instead (see rich-text-editor.css:
        .rich-text-toolbar-controls[inert] > *), targeting each child
        directly; `inert` (not just visual dimming) also blocks focus/click,
        matching a real disabled state.
    --}}
    <div class="contents rich-text-toolbar-controls" @if($bound) x-bind:inert="sourceMode" @endif>
    <x-ui.icon-button
        aria-label="Undo"
        title="Undo"
        @click="{{ $undo['click'] }}"
        x-bind:disabled="{{ $undo['disabled'] }}"
        x-bind:class="{!! $undo['class'] !!}"
    >
        <x-icon.undo class="h-4 w-4" />
    </x-ui.icon-button>
    <x-ui.icon-button
        aria-label="Redo"
        title="Redo"
        @click="{{ $redo['click'] }}"
        x-bind:disabled="{{ $redo['disabled'] }}"
        x-bind:class="{!! $redo['class'] !!}"
    >
        <x-icon.redo class="h-4 w-4" />
    </x-ui.icon-button>

    <x-ui.richtext.icon-toggle-divider />

    <x-ui.richtext.icon-toggle label="Bold" pressed :command="$b['bold']['command']" :sync="$b['bold']['sync']">
        <x-icon.bold class="h-4 w-4" />
    </x-ui.richtext.icon-toggle>
    <x-ui.richtext.icon-toggle label="Italic" :command="$b['italic']['command']" :sync="$b['italic']['sync']">
        <x-icon.italic class="h-4 w-4" />
    </x-ui.richtext.icon-toggle>
    <x-ui.richtext.icon-toggle label="Underline" :command="$b['underline']['command']" :sync="$b['underline']['sync']">
        <x-icon.underline class="h-4 w-4" />
    </x-ui.richtext.icon-toggle>
    <x-ui.richtext.icon-toggle label="Strikethrough" :command="$b['strike']['command']" :sync="$b['strike']['sync']">
        <x-icon.strike class="h-4 w-4" />
    </x-ui.richtext.icon-toggle>
    <x-ui.richtext.icon-toggle label="Superscript" :command="$b['superscript']['command']" :sync="$b['superscript']['sync']">
        <x-icon.superscript class="h-4 w-4" />
    </x-ui.richtext.icon-toggle>
    <x-ui.richtext.icon-toggle label="Subscript" :command="$b['subscript']['command']" :sync="$b['subscript']['sync']">
        <x-icon.subscript class="h-4 w-4" />
    </x-ui.richtext.icon-toggle>

    <x-ui.richtext.icon-toggle-divider />

    <x-ui.richtext.icon-toggle-radio-group default="left" :sync="$b['align']['sync']">
        <x-ui.richtext.icon-toggle-radio-group.item value="left" label="Align left" :command="$alignCmds['left']">
            <x-icon.align-left class="h-4 w-4" />
        </x-ui.richtext.icon-toggle-radio-group.item>
        <x-ui.richtext.icon-toggle-radio-group.item value="center" label="Align center" :command="$alignCmds['center']">
            <x-icon.align-center class="h-4 w-4" />
        </x-ui.richtext.icon-toggle-radio-group.item>
        <x-ui.richtext.icon-toggle-radio-group.item value="right" label="Align right" :command="$alignCmds['right']">
            <x-icon.align-right class="h-4 w-4" />
        </x-ui.richtext.icon-toggle-radio-group.item>
        <x-ui.richtext.icon-toggle-radio-group.item value="justify" label="Align justify" :command="$alignCmds['justify']">
            <x-icon.align-justify class="h-4 w-4" />
        </x-ui.richtext.icon-toggle-radio-group.item>
    </x-ui.richtext.icon-toggle-radio-group>

    <x-ui.richtext.icon-toggle-divider />

    <x-ui.richtext.icon-toggle label="Bullet list" :command="$b['bulletList']['command']" :sync="$b['bulletList']['sync']">
        <x-icon.list-bullet class="h-4 w-4" />
    </x-ui.richtext.icon-toggle>
    <x-ui.richtext.icon-toggle label="Numbered list" :command="$b['orderedList']['command']" :sync="$b['orderedList']['sync']">
        <x-icon.list-ordered class="h-4 w-4" />
    </x-ui.richtext.icon-toggle>

    <x-ui.richtext.icon-toggle-divider />

    <x-ui.richtext.icon-toggle-dropdown label="Typography" default="paragraph" width="w-48" :sync="$b['heading']['sync']">
        <x-slot:trigger>
            <x-icon.paragraph class="h-4 w-4" />
        </x-slot:trigger>

        <x-ui.richtext.icon-toggle-dropdown.item value="paragraph" :command="$headingCmds['paragraph']">
            <x-slot:icon><x-icon.paragraph class="h-4 w-4" /></x-slot:icon>
            Paragraph
        </x-ui.richtext.icon-toggle-dropdown.item>
        <x-ui.richtext.icon-toggle-dropdown.item value="heading-1" :command="$headingCmds['heading-1']">
            <x-slot:icon><x-icon.heading-1 class="h-4 w-4" /></x-slot:icon>
            <span class="text-base font-semibold">Heading 1</span>
        </x-ui.richtext.icon-toggle-dropdown.item>
        <x-ui.richtext.icon-toggle-dropdown.item value="heading-2" :command="$headingCmds['heading-2']">
            <x-slot:icon><x-icon.heading-2 class="h-4 w-4" /></x-slot:icon>
            <span class="text-sm font-semibold">Heading 2</span>
        </x-ui.richtext.icon-toggle-dropdown.item>
        <x-ui.richtext.icon-toggle-dropdown.item value="heading-3" :command="$headingCmds['heading-3']">
            <x-slot:icon><x-icon.heading-3 class="h-4 w-4" /></x-slot:icon>
            <span class="text-sm font-medium">Heading 3</span>
        </x-ui.richtext.icon-toggle-dropdown.item>
        <x-ui.richtext.icon-toggle-dropdown.item value="heading-4" :command="$headingCmds['heading-4']">
            <x-slot:icon><x-icon.heading-4 class="h-4 w-4" /></x-slot:icon>
            <span class="text-xs font-semibold">Heading 4</span>
        </x-ui.richtext.icon-toggle-dropdown.item>

        <li class="my-1 border-t border-gray-100 dark:border-gray-800"></li>

        <x-ui.richtext.icon-toggle-dropdown.item value="blockquote" :command="$headingCmds['blockquote']">
            <x-slot:icon><x-icon.blockquote class="h-4 w-4" /></x-slot:icon>
            Blockquote
        </x-ui.richtext.icon-toggle-dropdown.item>
        <x-ui.richtext.icon-toggle-dropdown.item value="codeblock" :command="$headingCmds['codeblock']">
            <x-slot:icon><x-icon.codeblock class="h-4 w-4" /></x-slot:icon>
            Code block
        </x-ui.richtext.icon-toggle-dropdown.item>
    </x-ui.richtext.icon-toggle-dropdown>

    <x-ui.richtext.icon-toggle-dropdown label="Font size" default="16" width="w-32" :sync="$b['fontSize']['sync']">
        <x-slot:trigger>
            <x-icon.font-size class="h-4 w-4" />
            <span class="text-xs font-medium text-gray-500 tabular-nums dark:text-gray-400" x-text="selected"></span>
        </x-slot:trigger>

        @foreach ([8, 9, 10, 11, 12, 14, 16, 18, 20, 22, 24, 26, 28, 30, 32, 34, 36, 48] as $fontSize)
            @php($fontSizeCmd = $w("setFontSize({$fontSize})"))
            <x-ui.richtext.icon-toggle-dropdown.item :value="(string) $fontSize" :command="$fontSizeCmd">
                <span style="font-size: {{ $fontSize }}px">{{ $fontSize }}</span>
            </x-ui.richtext.icon-toggle-dropdown.item>
        @endforeach
    </x-ui.richtext.icon-toggle-dropdown>

    <x-ui.richtext.icon-toggle-dropdown label="Text color" default="#111827" width="w-52" max-height="max-h-[30rem]" :sync="$b['color']['sync']">
        <x-slot:trigger>
            <span class="flex flex-col items-center gap-0.5">
                <x-icon.text-color class="h-4 w-4" />
                <span class="h-[3px] w-4 rounded-full" x-bind:style="'background-color: ' + selected"></span>
            </span>
        </x-slot:trigger>

        <li class="p-2">
            <div class="grid grid-cols-6 gap-1.5">
                @foreach ([
                    ['label' => 'Default', 'value' => '#111827'],
                    ['label' => 'Gray', 'value' => '#6B7280'],
                    ['label' => 'Red', 'value' => '#EF4444'],
                    ['label' => 'Orange', 'value' => '#F97316'],
                    ['label' => 'Amber', 'value' => '#F59E0B'],
                    ['label' => 'Yellow', 'value' => '#EAB308'],
                    ['label' => 'Green', 'value' => '#22C55E'],
                    ['label' => 'Teal', 'value' => '#14B8A6'],
                    ['label' => 'Blue', 'value' => '#3B82F6'],
                    ['label' => 'Indigo', 'value' => '#6366F1'],
                    ['label' => 'Purple', 'value' => '#A855F7'],
                    ['label' => 'Pink', 'value' => '#EC4899'],
                ] as $swatch)
                    <button
                        type="button"
                        @click="{!! $bound ? "setColor('{$swatch['value']}')" : "selected = '{$swatch['value']}'" !!}; open = false"
                        x-bind:class="selected === '{{ $swatch['value'] }}' ? 'ring-2 ring-offset-2 ring-brand-500 dark:ring-offset-gray-800' : 'ring-1 ring-gray-200 dark:ring-gray-700'"
                        class="h-6 w-6 shrink-0 rounded-full transition"
                        style="background-color: {{ $swatch['value'] }}"
                        aria-label="{{ $swatch['label'] }}"
                        title="{{ $swatch['label'] }}"
                    ></button>
                @endforeach
            </div>
        </li>

        <x-ui.richtext.color-picker-more :apply="$b['color']['apply']" />
    </x-ui.richtext.icon-toggle-dropdown>

    <x-ui.richtext.icon-toggle-dropdown label="Highlight color" default="#FEF08A" width="w-52" max-height="max-h-[30rem]" :sync="$b['highlight']['sync']">
        <x-slot:trigger>
            <span class="flex flex-col items-center gap-0.5">
                <x-icon.highlight class="h-4 w-4" />
                <span class="h-[3px] w-4 rounded-full" x-bind:style="'background-color: ' + selected"></span>
            </span>
        </x-slot:trigger>

        <li class="p-2">
            <div class="grid grid-cols-6 gap-1.5">
                <button
                    type="button"
                    @click="{!! $bound ? "setHighlight('transparent')" : "selected = 'transparent'" !!}; open = false"
                    x-bind:class="selected === 'transparent' ? 'ring-2 ring-offset-2 ring-brand-500 dark:ring-offset-gray-800' : 'ring-1 ring-gray-200 dark:ring-gray-700'"
                    class="relative h-6 w-6 shrink-0 overflow-hidden rounded-full bg-white transition dark:bg-gray-900"
                    aria-label="None"
                    title="None"
                >
                    <span class="absolute inset-0 flex items-center justify-center">
                        <span class="h-px w-6 rotate-45 bg-red-500"></span>
                    </span>
                </button>
                @foreach ([
                    ['label' => 'Yellow', 'value' => '#FEF08A'],
                    ['label' => 'Green', 'value' => '#BBF7D0'],
                    ['label' => 'Blue', 'value' => '#BFDBFE'],
                    ['label' => 'Purple', 'value' => '#E9D5FF'],
                    ['label' => 'Pink', 'value' => '#FBCFE8'],
                    ['label' => 'Orange', 'value' => '#FED7AA'],
                    ['label' => 'Red', 'value' => '#FECACA'],
                    ['label' => 'Gray', 'value' => '#E5E7EB'],
                    ['label' => 'Teal', 'value' => '#99F6E4'],
                    ['label' => 'Indigo', 'value' => '#C7D2FE'],
                    ['label' => 'Amber', 'value' => '#FDE68A'],
                ] as $swatch)
                    <button
                        type="button"
                        @click="{!! $bound ? "setHighlight('{$swatch['value']}')" : "selected = '{$swatch['value']}'" !!}; open = false"
                        x-bind:class="selected === '{{ $swatch['value'] }}' ? 'ring-2 ring-offset-2 ring-brand-500 dark:ring-offset-gray-800' : 'ring-1 ring-gray-200 dark:ring-gray-700'"
                        class="h-6 w-6 shrink-0 rounded-full transition"
                        style="background-color: {{ $swatch['value'] }}"
                        aria-label="{{ $swatch['label'] }}"
                        title="{{ $swatch['label'] }}"
                    ></button>
                @endforeach
            </div>
        </li>

        <x-ui.richtext.color-picker-more :apply="$b['highlight']['apply']" />
    </x-ui.richtext.icon-toggle-dropdown>

    <x-ui.richtext.icon-toggle-divider />

    <x-ui.richtext.link-popover
        :sync="$b['link']['sync']"
        :active="$b['link']['active']"
        :selection="$b['link']['selection']"
        :apply="$b['link']['apply']"
        :unlink="$b['link']['unlink']"
    />
    </div>

    <x-ui.richtext.icon-toggle-divider />

    <x-ui.richtext.icon-toggle label="Source code view" :command="$b['source']['command']" :sync="$b['source']['sync']">
        <x-icon.code-view class="h-4 w-4" />
    </x-ui.richtext.icon-toggle>
</x-ui.richtext.icon-toggle-group>
