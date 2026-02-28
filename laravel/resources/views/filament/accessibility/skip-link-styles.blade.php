<style>
    .vr-skip-link {
        position: fixed;
        top: 0.75rem;
        left: 0.75rem;
        z-index: 10000;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        border: 2px solid #111827;
        background: #ffffff;
        color: #111827;
        font-size: 0.95rem;
        font-weight: 700;
        line-height: 1.2;
        text-decoration: none;
        transform: translateY(-250%);
        transition: transform 120ms ease;
    }

    [dir="rtl"] .vr-skip-link {
        left: auto;
        right: 0.75rem;
    }

    .vr-skip-link:focus,
    .vr-skip-link:focus-visible {
        transform: translateY(0);
        outline: 3px solid #ff750f;
        outline-offset: 2px;
    }

    :where(a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])):focus-visible {
        outline: 3px solid #ff750f;
        outline-offset: 2px;
    }

    #main-content {
        scroll-margin-top: 5rem;
    }
</style>
