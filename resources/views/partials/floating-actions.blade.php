@php
    $whatsappPhone = preg_replace('/\D+/', '', (string) (($siteSettings ?? null)?->contact_phone ?: ''));
    if ($whatsappPhone !== '' && str_starts_with($whatsappPhone, '0')) {
        $whatsappPhone = '44'.substr($whatsappPhone, 1);
    }
@endphp

<div class="floating-action-rail" aria-label="Quick actions">
    @if ($whatsappPhone !== '')
        <a
            href="https://wa.me/{{ $whatsappPhone }}"
            target="_blank"
            rel="noopener noreferrer"
            class="floating-action floating-action-whatsapp"
            aria-label="Chat on WhatsApp"
            data-no-spinner
        >
            <i class="bi bi-whatsapp text-2xl leading-none" aria-hidden="true"></i>
        </a>
    @endif

    <button
        type="button"
        class="floating-action floating-action-top"
        x-data="{ visible: false }"
        x-init="
            const sync = () => { visible = window.scrollY > 320 };
            sync();
            window.addEventListener('scroll', sync, { passive: true });
        "
        x-show="visible"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        aria-label="Back to top"
        data-no-spinner
    >
        <i class="bi bi-arrow-up text-xl leading-none" aria-hidden="true"></i>
    </button>
</div>
