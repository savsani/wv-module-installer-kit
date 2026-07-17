{{--
    Dark-mode FOUC prevention — must run synchronously in <head>, before any
    paint, so the page never flashes light-then-dark. Included identically by
    every root layout (app/guest/error); keep it as the one copy.
--}}
<script>
    (function () {
        var theme = localStorage.getItem('theme');
        var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (theme === 'dark' || (!theme && prefersDark)) {
            document.documentElement.classList.add('dark');
        }
    })();
</script>
