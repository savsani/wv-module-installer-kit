import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import themeToggle from './theme';
import languageSwitcher from './language-switcher';
import colorPicker from './color-picker';
import dataTable from './data-table';
import tableActionsMenu from './table-actions-menu';
import richTextEditor from './rich-text-editor';
import sourceCodeEditor from './source-code-editor';
import datePicker from './date-picker';
import coverImage from './cover-image';
import toastStore from './toast-store';
import confirmDialogStore from './confirm-dialog-store';

window.Alpine = Alpine;

Alpine.plugin(focus);

Alpine.store('toast', toastStore);
Alpine.store('confirmDialog', confirmDialogStore);

Alpine.data('themeToggle', themeToggle);
Alpine.data('languageSwitcher', languageSwitcher);
Alpine.data('dataTable', dataTable);
Alpine.data('tableActionsMenu', tableActionsMenu);
Alpine.data('richTextEditor', richTextEditor);
Alpine.data('sourceCodeEditor', sourceCodeEditor);
Alpine.data('datePicker', datePicker);
Alpine.data('colorPicker', colorPicker);
Alpine.data('coverImage', coverImage);

Alpine.start();
