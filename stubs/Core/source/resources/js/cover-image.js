/**
 * Generic Alpine.data factory backing the reusable cover image uploader at
 * components/form/cover-image.blade.php. Two file inputs are involved:
 *
 *   - the <x-form.file-input> picker  a `name`-less input, the control the
 *     user actually sees and interacts with. Its own validate() (type/size)
 *     runs first and clears the input on rejection, so by the time its
 *     `change` event bubbles up to onPick() here, `event.target.files` is
 *     already empty for anything invalid — no duplicate validation needed.
 *   - $refs.output  a hidden <input type="file"> carrying the real `name`,
 *                   submitted with the surrounding <form>. Its FileList is
 *                   set programmatically (via DataTransfer) to either the
 *                   cropped result or, when cropping is disabled, the
 *                   original picked file untouched.
 *
 * Cropping is done with Cropper.js v2 (custom elements: <cropper-canvas>,
 * <cropper-image>, <cropper-selection>, ...), built by handing `new
 * Cropper(imgEl, { template })` a copy of its own default template with an
 * `aspect-ratio` attribute injected into <cropper-selection> when one was
 * configured — omitting it leaves the selection freeform. The template's
 * markup is duplicated here (rather than string-patched from the package's
 * exported DEFAULT_TEMPLATE) so an upstream template change can't silently
 * turn into a missing aspect-ratio lock.
 *
 *   x-data="coverImage({
 *       value: '/storage/covers/existing.jpg',
 *       aspectRatio: 16 / 9,   // null = freeform crop
 *       cropWidth: 1280,       // output canvas size in px, null = natural
 *       cropHeight: 720,
 *       cropEnabled: true,
 *   })"
 */
import Cropper from 'cropperjs';

function cropperTemplate(aspectRatio) {
    const selectionAttrs = `initial-coverage="0.5" movable resizable${aspectRatio ? ` aspect-ratio="${aspectRatio}"` : ''}`;

    return '<cropper-canvas background style="width: 100%; height: 100%;">'
        + '<cropper-image rotatable scalable skewable translatable></cropper-image>'
        + '<cropper-shade hidden></cropper-shade>'
        + '<cropper-handle action="select" plain></cropper-handle>'
        + `<cropper-selection ${selectionAttrs}>`
        + '<cropper-grid role="grid" bordered covered></cropper-grid>'
        + '<cropper-crosshair centered></cropper-crosshair>'
        + '<cropper-handle action="move" theme-color="rgba(255, 255, 255, 0.35)"></cropper-handle>'
        + '<cropper-handle action="n-resize"></cropper-handle>'
        + '<cropper-handle action="e-resize"></cropper-handle>'
        + '<cropper-handle action="s-resize"></cropper-handle>'
        + '<cropper-handle action="w-resize"></cropper-handle>'
        + '<cropper-handle action="ne-resize"></cropper-handle>'
        + '<cropper-handle action="nw-resize"></cropper-handle>'
        + '<cropper-handle action="se-resize"></cropper-handle>'
        + '<cropper-handle action="sw-resize"></cropper-handle>'
        + '</cropper-selection>'
        + '</cropper-canvas>';
}

export default function coverImage(options = {}) {
    const {
        value = null,
        aspectRatio = null,
        cropWidth = null,
        cropHeight = null,
        cropEnabled = true,
    } = options;

    return {
        aspectRatio,
        previewUrl: value,
        error: null,
        cropModalOpen: false,

        // Non-reactive: wrapping the Cropper instance/File in Alpine's proxy
        // breaks its internal DOM bookkeeping (same reasoning as the
        // `editor` closure variable in rich-text-editor.js).
        cropper: null,
        pendingFile: null,
        rawObjectUrl: null,

        onPick(event) {
            this.error = null;
            const file = event.target.files[0];
            event.target.value = '';
            if (!file) return;

            if (cropEnabled) {
                this.startCrop(file);
            } else {
                this.finalize(file);
            }
        },

        startCrop(file) {
            this.pendingFile = file;
            this.rawObjectUrl = URL.createObjectURL(file);
            this.cropModalOpen = true;

            this.$nextTick(() => {
                const img = this.$refs.cropperImage;
                img.onload = () => this.initCropper();
                img.src = this.rawObjectUrl;
            });
        },

        initCropper() {
            this.destroyCropper();
            this.cropper = new Cropper(this.$refs.cropperImage, {
                template: cropperTemplate(this.aspectRatio),
            });
        },

        destroyCropper() {
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
        },

        async applyCrop() {
            const selection = this.cropper?.getCropperSelection();
            if (!selection) return;

            try {
                const canvas = await selection.$toCanvas({
                    width: cropWidth || undefined,
                    height: cropHeight || undefined,
                });
                const mimeType = this.pendingFile.type.startsWith('image/') ? this.pendingFile.type : 'image/png';
                const fileName = this.pendingFile.name;

                canvas.toBlob((blob) => {
                    if (!blob) {
                        this.error = 'Could not crop the image. Please try another file.';
                        return;
                    }
                    this.finalize(new File([blob], fileName, { type: mimeType }));
                    this.closeCropModal();
                }, mimeType, 0.92);
            } catch (e) {
                this.error = 'Could not crop the image. Please try another file.';
                this.closeCropModal();
            }
        },

        cancelCrop() {
            this.closeCropModal();
        },

        closeCropModal() {
            this.cropModalOpen = false;
            this.destroyCropper();
            if (this.rawObjectUrl) {
                URL.revokeObjectURL(this.rawObjectUrl);
                this.rawObjectUrl = null;
            }
            this.pendingFile = null;
            this.$refs.cropperImage.removeAttribute('src');
        },

        finalize(file) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            this.$refs.output.files = dataTransfer.files;

            if (this.previewUrl?.startsWith('blob:')) {
                URL.revokeObjectURL(this.previewUrl);
            }
            this.previewUrl = URL.createObjectURL(file);
            this.$dispatch('change', { file });
        },

        remove() {
            this.error = null;
            if (this.previewUrl?.startsWith('blob:')) {
                URL.revokeObjectURL(this.previewUrl);
            }
            this.previewUrl = null;
            this.$refs.output.value = '';
            this.$dispatch('change', { file: null });
        },
    };
}
