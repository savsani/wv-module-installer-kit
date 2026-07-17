/**
 * Generic Alpine.data factory backing the reusable color picker at
 * components/form/color-picker.blade.php. Replaces the Coloris dependency
 * with a plain HSV picker: a saturation/brightness gradient area, a hue
 * slider, and (for the `rgba` format) an alpha slider — driving the same
 * text input that the user can also type a hex/rgba string into directly.
 *
 *   x-data="colorPicker(@js($value), { format: 'hex' | 'rgba' })"
 */
const DEFAULTS = {
    hex: '#4f46e5',
    rgba: 'rgba(79, 70, 229, 1)',
};

const clamp = (n, min, max) => Math.min(max, Math.max(min, n));
const round2 = (n) => Math.round(n * 100) / 100;
const toHex2 = (n) => clamp(Math.round(n), 0, 255).toString(16).padStart(2, '0');

function hsvToRgb(h, s, v) {
    s /= 100;
    v /= 100;
    const c = v * s;
    const hh = (((h % 360) + 360) % 360) / 60;
    const x = c * (1 - Math.abs((hh % 2) - 1));
    const m = v - c;
    const [r, g, b] = hh < 1 ? [c, x, 0]
        : hh < 2 ? [x, c, 0]
        : hh < 3 ? [0, c, x]
        : hh < 4 ? [0, x, c]
        : hh < 5 ? [x, 0, c]
        : [c, 0, x];

    return {
        r: Math.round((r + m) * 255),
        g: Math.round((g + m) * 255),
        b: Math.round((b + m) * 255),
    };
}

function rgbToHsv(r, g, b) {
    r /= 255;
    g /= 255;
    b /= 255;
    const max = Math.max(r, g, b);
    const min = Math.min(r, g, b);
    const d = max - min;
    let h = 0;

    if (d !== 0) {
        if (max === r) h = ((g - b) / d) % 6;
        else if (max === g) h = (b - r) / d + 2;
        else h = (r - g) / d + 4;
        h *= 60;
        if (h < 0) h += 360;
    }

    return {
        h: Math.round(h),
        s: Math.round(max === 0 ? 0 : (d / max) * 100),
        v: Math.round(max * 100),
    };
}

function rgbToHex(r, g, b) {
    return `#${toHex2(r)}${toHex2(g)}${toHex2(b)}`;
}

/** Accepts `#rgb`, `#rrggbb`, `#rrggbbaa`, `rgb(...)` or `rgba(...)` regardless of the field's own format. */
function parseColor(input) {
    if (!input) return null;
    const str = input.trim();

    const hexMatch = str.match(/^#?([0-9a-f]{3}|[0-9a-f]{6}|[0-9a-f]{8})$/i);
    if (hexMatch) {
        let hex = hexMatch[1];
        if (hex.length === 3) hex = hex.split('').map((c) => c + c).join('');
        const r = parseInt(hex.slice(0, 2), 16);
        const g = parseInt(hex.slice(2, 4), 16);
        const b = parseInt(hex.slice(4, 6), 16);
        const a = hex.length === 8 ? parseInt(hex.slice(6, 8), 16) / 255 : 1;
        return { ...rgbToHsv(r, g, b), a: round2(a) };
    }

    const rgbMatch = str.match(/^rgba?\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*(?:,\s*([\d.]+)\s*)?\)$/i);
    if (rgbMatch) {
        const [, r, g, b, a] = rgbMatch;
        return {
            ...rgbToHsv(clamp(+r, 0, 255), clamp(+g, 0, 255), clamp(+b, 0, 255)),
            a: a !== undefined ? clamp(parseFloat(a), 0, 1) : 1,
        };
    }

    return null;
}

const checkerLayers = 'repeating-linear-gradient(45deg, #ccc 25%, transparent 25%, transparent 75%, #ccc 75%, #ccc), '
    + 'repeating-linear-gradient(45deg, #ccc 25%, #fff 25%, #fff 75%, #ccc 75%, #ccc)';
const checkerLayout = 'background-position: 0 0, 0 0, 4px 4px; background-size: 100% 100%, 8px 8px, 8px 8px;';

export default function colorPicker(initialValue = null, options = {}) {
    const format = options.format === 'rgba' ? 'rgba' : 'hex';
    const seed = parseColor(initialValue) ?? parseColor(DEFAULTS[format]);

    return {
        format,
        h: seed.h,
        s: seed.s,
        v: seed.v,
        a: format === 'rgba' ? seed.a : 1,
        text: '',
        isOpen: false,
        draggingArea: false,

        init() {
            this.text = this.displayValue;
        },

        get rgb() {
            return hsvToRgb(this.h, this.s, this.v);
        },

        get displayValue() {
            const { r, g, b } = this.rgb;
            return this.format === 'rgba' ? `rgba(${r}, ${g}, ${b}, ${round2(this.a)})` : rgbToHex(r, g, b);
        },

        get previewStyle() {
            const { r, g, b } = this.rgb;
            return `background-image: linear-gradient(rgba(${r}, ${g}, ${b}, ${this.a}), rgba(${r}, ${g}, ${b}, ${this.a})), ${checkerLayers}; ${checkerLayout}`;
        },

        get hueColor() {
            const { r, g, b } = hsvToRgb(this.h, 100, 100);
            return `rgb(${r}, ${g}, ${b})`;
        },

        get areaStyle() {
            return `background-image: linear-gradient(to top, #000, transparent), linear-gradient(to right, #fff, ${this.hueColor});`;
        },

        get markerStyle() {
            return `left: ${this.s}%; top: ${100 - this.v}%; color: ${this.hueColor};`;
        },

        get alphaTrackStyle() {
            const { r, g, b } = this.rgb;
            return `background-image: linear-gradient(to right, rgba(${r}, ${g}, ${b}, 0), rgb(${r}, ${g}, ${b})), ${checkerLayers}; ${checkerLayout}`;
        },

        open() {
            this.isOpen = true;
        },

        close() {
            this.isOpen = false;
        },

        toggle() {
            this.isOpen = !this.isOpen;
        },

        syncText() {
            this.text = this.displayValue;
            this.$dispatch('change', { value: this.text });
        },

        setFromArea(event, el) {
            const rect = el.getBoundingClientRect();
            const x = clamp(event.clientX - rect.left, 0, rect.width);
            const y = clamp(event.clientY - rect.top, 0, rect.height);
            this.s = Math.round((x / rect.width) * 100);
            this.v = Math.round(100 - (y / rect.height) * 100);
            this.syncText();
        },

        startAreaDrag(event, el) {
            el.setPointerCapture(event.pointerId);
            this.draggingArea = true;
            this.setFromArea(event, el);
        },

        onAreaDrag(event, el) {
            if (!this.draggingArea) return;
            this.setFromArea(event, el);
        },

        endAreaDrag() {
            this.draggingArea = false;
        },

        onAreaKeydown(event) {
            const step = event.shiftKey ? 10 : 1;
            const moves = {
                ArrowUp: [0, step],
                ArrowDown: [0, -step],
                ArrowLeft: [-step, 0],
                ArrowRight: [step, 0],
            };
            const move = moves[event.key];
            if (!move) return;

            event.preventDefault();
            this.s = clamp(this.s + move[0], 0, 100);
            this.v = clamp(this.v + move[1], 0, 100);
            this.syncText();
        },

        onTextInput(event) {
            this.text = event.target.value;
            const parsed = parseColor(this.text);
            if (!parsed) return;

            this.h = parsed.h;
            this.s = parsed.s;
            this.v = parsed.v;
            this.a = this.format === 'rgba' ? parsed.a : 1;
            this.$dispatch('change', { value: this.text });
        },

        applyText() {
            this.text = this.displayValue;
        },
    };
}
