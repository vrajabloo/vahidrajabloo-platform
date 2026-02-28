<script>
    (() => {
        const ensureMainTarget = () => {
            const main = document.querySelector('main');

            if (! main) {
                return;
            }

            if (! main.id) {
                main.id = 'main-content';
            }
        };

        const init = () => {
            ensureMainTarget();

            const skipLink = document.querySelector('.vr-skip-link[href="#main-content"]');
            if (! skipLink) {
                return;
            }

            skipLink.addEventListener('click', () => {
                ensureMainTarget();

                const target = document.getElementById('main-content');
                if (! target) {
                    return;
                }

                target.setAttribute('tabindex', '-1');
                requestAnimationFrame(() => target.focus({ preventScroll: true }));
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init, { once: true });
        } else {
            init();
        }
    })();
</script>
