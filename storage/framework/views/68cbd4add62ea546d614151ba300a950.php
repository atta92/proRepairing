<div
    id="quote-modal"
    class="fixed inset-0 z-[80] <?php echo e(($errors->any() && old('_quote_form')) ? 'flex' : 'hidden'); ?> items-center justify-center bg-ink/70 p-4 backdrop-blur-sm"
    aria-hidden="<?php echo e(($errors->any() && old('_quote_form')) ? 'false' : 'true'); ?>"
    data-open-on-load="<?php echo e(($errors->any() && old('_quote_form')) ? '1' : '0'); ?>"
    role="dialog"
    aria-modal="true"
    aria-labelledby="quote-modal-title"
>
    <div class="relative w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl" data-quote-dialog>
        <button
            type="button"
            class="absolute right-3 top-3 z-10 flex h-9 w-9 items-center justify-center rounded-full bg-accent text-white transition hover:bg-accent-dark"
            data-quote-close
            aria-label="Close quote form"
        >
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
            </svg>
        </button>

        <div class="border-b border-mist bg-snow px-6 py-5 pr-14">
            <p class="text-xs font-semibold uppercase tracking-wider text-accent">Free quote</p>
            <h2 id="quote-modal-title" class="mt-1 font-display text-2xl font-bold text-ink">
                Request a free quote
            </h2>
            <p class="mt-2 text-sm text-steel">
                Older device, rare model, or a fault you can’t describe? Send us the details and we’ll come back with a fixed price.
            </p>
        </div>

        <form
            method="POST"
            action="<?php echo e(route('quotes.store')); ?>"
            enctype="multipart/form-data"
            class="space-y-4 px-6 py-5"
            id="quote-form"
            x-data="{ submitting: false }"
            @submit="submitting = true"
        >
            <?php echo csrf_field(); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any() && old('_quote_form')): ?>
                <div class="rounded-xl border border-signal/30 bg-signal/10 px-4 py-3 text-sm text-signal-dark">
                    <ul class="list-disc space-y-1 pl-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <input type="hidden" name="_quote_form" value="1">

            <label class="block">
                <span class="sr-only">Name</span>
                <input
                    type="text"
                    name="name"
                    value="<?php echo e(old('name')); ?>"
                    required
                    placeholder="Name"
                    class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm text-ink placeholder:text-steel/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20"
                >
            </label>

            <label class="block">
                <span class="sr-only">Email Address</span>
                <input
                    type="email"
                    name="email"
                    value="<?php echo e(old('email')); ?>"
                    required
                    placeholder="Email Address"
                    class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm text-ink placeholder:text-steel/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20"
                >
            </label>

            <label class="block">
                <span class="sr-only">Phone Number</span>
                <input
                    type="text"
                    name="phone"
                    value="<?php echo e(old('phone')); ?>"
                    required
                    placeholder="Phone Number"
                    class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm text-ink placeholder:text-steel/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20"
                >
            </label>

            <label class="block">
                <span class="sr-only">Phone/Tab/Laptop Model</span>
                <input
                    type="text"
                    name="device_name"
                    value="<?php echo e(old('device_name')); ?>"
                    required
                    placeholder="Phone/Tab/Laptop Model"
                    class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm text-ink placeholder:text-steel/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20"
                >
            </label>

            <div>
                <label class="block cursor-pointer">
                    <span class="mb-1.5 block text-xs font-medium text-steel">
                        Please upload an image showing the back of your device or any visible damage for assessment (optional)
                    </span>
                    <input
                        type="file"
                        name="image"
                        accept="image/*"
                        class="block w-full text-sm text-steel file:mr-3 file:rounded-lg file:border-0 file:bg-accent file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-accent-dark"
                    >
                </label>
            </div>

            <label class="block">
                <span class="sr-only">Message</span>
                <textarea
                    name="message"
                    rows="4"
                    required
                    placeholder="Message here"
                    class="w-full rounded-lg border border-mist px-3 py-2.5 text-sm text-ink placeholder:text-steel/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20"
                ><?php echo e(old('message')); ?></textarea>
            </label>

            <button type="submit" class="btn-primary w-full" :disabled="submitting" :class="{ 'btn-loading': submitting }">
                <span class="btn-spinner" x-show="submitting" x-cloak></span>
                <span x-text="submitting ? 'Sending…' : 'Request a Quote'"></span>
            </button>
        </form>
    </div>
</div>
<?php /**PATH D:\laravel\repairing\resources\views/partials/quote-modal.blade.php ENDPATH**/ ?>