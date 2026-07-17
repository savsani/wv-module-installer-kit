import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import TextAlign from '@tiptap/extension-text-align';
import Subscript from '@tiptap/extension-subscript';
import Superscript from '@tiptap/extension-superscript';
import Highlight from '@tiptap/extension-highlight';
import { TextStyleKit } from '@tiptap/extension-text-style';
import { Placeholder } from '@tiptap/extensions';
import { EditorView, basicSetup } from 'codemirror';
import { EditorState, Compartment } from '@codemirror/state';
import { html } from '@codemirror/lang-html';
import { oneDark } from '@codemirror/theme-one-dark';

// Only heading-1..4 are exposed in the toolbar's Typography dropdown — h5/h6
// render identically to h4 (see .ProseMirror h4,h5,h6 in rich-text-editor.css),
// so there's no usable visual distinction to pick between them for.
const HEADING_VALUES = ['heading-1', 'heading-2', 'heading-3', 'heading-4'];

/**
 * Backs <x-form.rich-text-editor>. Boots a headless TipTap editor onto
 * $refs.element and drives <x-form.rich-text-toolbar :bound="true">, whose
 * controls call back into the methods/getters below (toggleBold(),
 * isActive('bold'), setAlign(), align, ...) instead of managing their own
 * local state. Keeps a hidden textarea ($refs.textarea, the one carrying
 * `name`) in sync with editor.getHTML() so the surrounding <form> submits
 * the content with no extra JS on the caller's side.
 *
 * `editor` is a closure variable, not a property on the returned object —
 * Alpine wraps every component property in a reactive proxy, and dispatching
 * a transaction through a proxied editor instance throws "RangeError:
 * Applying a mismatched transaction" (see
 * https://tiptap.dev/docs/editor/getting-started/install/alpine). `updatedAt`
 * stands in as the reactive signal instead: every getter/isActive() call
 * reads it first purely so Alpine's dependency tracker sees a reactive read
 * and re-evaluates the toolbar's `sync` bindings whenever it changes.
 */
export default function richTextEditor({ value = '', placeholder = '', disabled = false } = {}) {
    let editor = null;
    let sourceView = null;
    const sourceTheme = new Compartment();
    const isDark = () => document.documentElement.classList.contains('dark');

    return {
        sourceMode: false,
        updatedAt: Date.now(),

        init() {
            const bump = () => (this.updatedAt = Date.now());

            editor = new Editor({
                element: this.$refs.element,
                editable: !disabled,
                content: value,
                extensions: [
                    StarterKit.configure({ link: { openOnClick: false } }),
                    TextAlign.configure({ types: ['heading', 'paragraph'] }),
                    Subscript,
                    Superscript,
                    Highlight.configure({ multicolor: true }),
                    TextStyleKit.configure({ fontFamily: false, lineHeight: false, backgroundColor: false }),
                    Placeholder.configure({ placeholder }),
                ],
                onCreate: bump,
                onSelectionUpdate: bump,
                onUpdate: () => {
                    bump();
                    this.syncTextarea();
                },
            });

            this.syncTextarea();

            // Follows <html class="dark"> live (see resources/js/theme.js)
            // so the source view's syntax theme switches instantly if the
            // user flips light/dark while it's open, same idea as the old
            // Jodit component's dark-mode MutationObserver.
            new MutationObserver(() => {
                if (sourceView) {
                    sourceView.dispatch({ effects: sourceTheme.reconfigure(isDark() ? oneDark : []) });
                }
            }).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
        },

        syncTextarea() {
            if (this.$refs.textarea) {
                this.$refs.textarea.value = editor.isEmpty ? '' : editor.getHTML();
            }
        },

        // TipTap's .ProseMirror node only grows to fit its content, so a
        // short document leaves empty space in this wrapper (sized by
        // min-height) that isn't part of the contenteditable element —
        // clicks there land on the wrapper itself and go nowhere. A native
        // <textarea> doesn't have this gap, so mimic it: a click that lands
        // directly on the wrapper (not on/inside the editor content) focuses
        // the nearest valid cursor position instead of doing nothing.
        focusEnd(event) {
            if (event.target === this.$refs.element) {
                editor.chain().focus('end').run();
            }
        },

        // Same wrapper-vs-content gap as focusEnd() above, for the
        // CodeMirror source view.
        focusSource(event) {
            if (event.target === this.$refs.source && sourceView) {
                sourceView.focus();
            }
        },

        // `attrs` must stay undefined (not null) when the caller omits it —
        // Tiptap's node-attribute check does `Object.keys(attrs)` internally
        // and only supplies its own {} default for a genuinely-undefined
        // argument, so forwarding a literal null throws "Cannot convert
        // undefined or null to object" for node checks like isActive('codeBlock').
        isActive(name, attrs) {
            this.updatedAt;

            return editor ? editor.isActive(name, attrs) : false;
        },

        // Whether there's a non-collapsed text selection — used to disable
        // the link control when there's nothing to wrap in a link (unless
        // the cursor is already sitting inside one, see linkHref/isActive).
        get hasSelection() {
            this.updatedAt;

            return editor ? !editor.state.selection.empty : false;
        },

        get canUndo() {
            this.updatedAt;

            return editor ? editor.can().undo() : false;
        },

        get canRedo() {
            this.updatedAt;

            return editor ? editor.can().redo() : false;
        },

        undo() {
            editor.chain().focus().undo().run();
        },

        redo() {
            editor.chain().focus().redo().run();
        },

        toggleBold() {
            editor.chain().focus().toggleBold().run();
        },

        toggleItalic() {
            editor.chain().focus().toggleItalic().run();
        },

        toggleUnderline() {
            editor.chain().focus().toggleUnderline().run();
        },

        toggleStrike() {
            editor.chain().focus().toggleStrike().run();
        },

        toggleSuperscript() {
            editor.chain().focus().toggleSuperscript().run();
        },

        toggleSubscript() {
            editor.chain().focus().toggleSubscript().run();
        },

        toggleBulletList() {
            editor.chain().focus().toggleBulletList().run();
        },

        toggleOrderedList() {
            editor.chain().focus().toggleOrderedList().run();
        },

        toggleBlockquote() {
            editor.chain().focus().toggleBlockquote().run();
        },

        toggleCodeBlock() {
            editor.chain().focus().toggleCodeBlock().run();
        },

        get align() {
            this.updatedAt;

            if (!editor) return 'left';

            return ['left', 'center', 'right', 'justify'].find((value) => editor.isActive({ textAlign: value })) ?? 'left';
        },

        setAlign(value) {
            editor.chain().focus().setTextAlign(value).run();
        },

        // Blockquote and code block live in the same Typography dropdown as
        // the paragraph/heading options (see rich-text-toolbar.blade.php) —
        // checked ahead of headings since a blockquote's content is itself a
        // paragraph/heading, and this getter only reports one selected value.
        get headingValue() {
            this.updatedAt;

            if (!editor) return 'paragraph';

            if (editor.isActive('codeBlock')) return 'codeblock';
            if (editor.isActive('blockquote')) return 'blockquote';

            const index = HEADING_VALUES.findIndex((_, level) => editor.isActive('heading', { level: level + 1 }));

            return index === -1 ? 'paragraph' : HEADING_VALUES[index];
        },

        setHeading(value) {
            if (value === 'paragraph') {
                editor.chain().focus().setParagraph().run();
                return;
            }

            if (value === 'blockquote') {
                this.toggleBlockquote();
                return;
            }

            if (value === 'codeblock') {
                this.toggleCodeBlock();
                return;
            }

            editor.chain().focus().toggleHeading({ level: Number(value.replace('heading-', '')) }).run();
        },

        get fontSizeValue() {
            this.updatedAt;

            const size = editor?.getAttributes('textStyle').fontSize;

            return size ? size.replace('px', '') : '16';
        },

        setFontSize(value) {
            editor.chain().focus().setFontSize(`${value}px`).run();
        },

        get colorValue() {
            this.updatedAt;

            return editor?.getAttributes('textStyle').color || '#111827';
        },

        setColor(value) {
            editor.chain().focus().setColor(value).run();
        },

        get highlightValue() {
            this.updatedAt;

            return editor?.getAttributes('highlight').color || 'transparent';
        },

        setHighlight(value) {
            if (value === 'transparent') {
                editor.chain().focus().unsetHighlight().run();
                return;
            }

            editor.chain().focus().setHighlight({ color: value }).run();
        },

        get linkHref() {
            this.updatedAt;

            return editor?.getAttributes('link').href ?? '';
        },

        // Called with the link-popover's own `url` field (see
        // <x-ui.richtext.link-popover>), not read from the editor.
        setLink(url) {
            if (!url) {
                editor.chain().focus().extendMarkRange('link').unsetLink().run();
                return;
            }

            editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
        },

        unsetLink() {
            editor.chain().focus().unsetLink().run();
        },

        // Source view is a real CodeMirror 6 instance (line numbers, HTML/
        // embedded CSS/JS highlighting via @codemirror/lang-html, dark
        // theme via @codemirror/theme-one-dark) mounted into $refs.source —
        // same idea as CKEditor's CodeMirror-backed source-editing mode.
        // It's created once and its content replaced on every re-entry
        // rather than recreated, so it keeps its own undo history between
        // toggles.
        toggleSource() {
            if (this.sourceMode) {
                editor.commands.setContent(sourceView.state.doc.toString());
                this.syncTextarea();
                this.sourceMode = false;
                return;
            }

            this.sourceMode = true;
            this.$nextTick(() => this.mountSource(editor.getHTML()));
        },

        mountSource(content) {
            if (sourceView) {
                sourceView.dispatch({ changes: { from: 0, to: sourceView.state.doc.length, insert: content } });
                return;
            }

            sourceView = new EditorView({
                parent: this.$refs.source,
                state: EditorState.create({
                    doc: content,
                    extensions: [basicSetup, html(), sourceTheme.of(isDark() ? oneDark : []), EditorView.theme({ '&': { fontSize: '0.8125rem' } })],
                }),
            });
        },
    };
}
