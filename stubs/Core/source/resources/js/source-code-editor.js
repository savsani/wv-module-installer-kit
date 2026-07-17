import { EditorView, basicSetup } from 'codemirror';
import { EditorState, Compartment } from '@codemirror/state';
import { html } from '@codemirror/lang-html';
import { oneDark } from '@codemirror/theme-one-dark';

/**
 * Backs <x-form.source-code-editor>. A plain CodeMirror 6 instance — no
 * Tiptap, no schema, no toolbar. Unlike <x-form.rich-text-editor>'s source
 * view (which round-trips through Tiptap/ProseMirror's schema and so only
 * keeps content the WYSIWYG side understands), this component never touches
 * the text: whatever is typed — <style>, <script>, arbitrary tags/attributes
 * — is exactly what ends up in the hidden textarea. See that component's
 * doc block for the storage/rendering security notes that come with that.
 */
export default function sourceCodeEditor({ value = '', disabled = false } = {}) {
    let view = null;
    const theme = new Compartment();
    const isDark = () => document.documentElement.classList.contains('dark');

    return {
        init() {
            view = new EditorView({
                parent: this.$refs.element,
                state: EditorState.create({
                    doc: value,
                    extensions: [
                        basicSetup,
                        html(),
                        theme.of(isDark() ? oneDark : []),
                        EditorView.editable.of(!disabled),
                        EditorView.updateListener.of((update) => {
                            if (update.docChanged) this.syncTextarea();
                        }),
                    ],
                }),
            });

            this.syncTextarea();

            // Follows <html class="dark"> live, same as the rich-text
            // editor's source view (see resources/js/rich-text-editor.js).
            new MutationObserver(() => {
                view.dispatch({ effects: theme.reconfigure(isDark() ? oneDark : []) });
            }).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
        },

        syncTextarea() {
            if (this.$refs.textarea) {
                this.$refs.textarea.value = view.state.doc.toString();
            }
        },

        // Same wrapper-vs-content click gap as rich-text-editor.js's
        // focusEnd()/focusSource() — see those for the full explanation.
        focusEditor(event) {
            if (event.target === this.$refs.element) {
                view.focus();
            }
        },
    };
}
