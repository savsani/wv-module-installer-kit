export default function languageSwitcher() {
    return {
        current: localStorage.getItem('language') || 'en',

        select(code) {
            this.current = code;
            localStorage.setItem('language', code);
        },
    };
}
