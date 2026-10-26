

<?php $__env->startSection('title', 'Booking confirmed - ' . $siteSettings->displayName()); ?>

<?php $__env->startSection('meta_description', 'Your repair booking is confirmed. We will be in touch from ' . $siteSettings->displayName() . '.'); ?>

<?php $__env->startSection('meta_robots', 'noindex, nofollow'); ?>

<?php
    $isPaid = filled($order->paid_at);
    $mailInAddress = trim((string) ($siteSettings->mail_in_address ?? ''));
    $repairItems = $order->items->whereNull('repair_addon_id');
    $addonItems = $order->items->whereNotNull('repair_addon_id');
    $placedAt = $order->paid_at ?? $order->created_at;
?>

<?php $__env->startSection('content'); ?>
    <section class="site-grid-bg-soft bg-snow py-10 sm:py-16">
        <div class="mx-auto max-w-lg px-4 sm:px-6">
            <div class="text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-accent text-white shadow-sm" aria-hidden="true">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.2em] text-accent">
                    <?php echo e($isPaid ? 'Payment received' : 'Booking confirmed'); ?>

                </p>
                <h1 class="mt-2 font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">
                    Thanks, <?php echo e($order->customer->name); ?>

                </h1>
                <p class="mt-2 text-sm text-steel">
                    Keep this receipt for your records. A copy
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isPaid): ?>
                        has been emailed to
                    <?php else: ?>
                        will be emailed to
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <span class="font-medium text-ink"><?php echo e($order->customer->email); ?></span>.
                </p>
            </div>

            
            <article class="receipt mt-8 overflow-hidden bg-white shadow-[0_12px_40px_-18px_rgba(15,23,32,0.35)]" aria-label="Booking receipt">
                <div class="receipt-edge receipt-edge-top" aria-hidden="true"></div>

                <div class="px-5 pb-2 pt-6 sm:px-7">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-display text-lg font-bold text-ink"><?php echo e($siteSettings->displayName()); ?></p>
                            <p class="mt-0.5 text-xs uppercase tracking-wider text-steel">Repair booking receipt</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-steel">Reference</p>
                            <p class="mt-0.5 font-display text-base font-bold tracking-wide text-accent"><?php echo e($order->reference); ?></p>
                        </div>
                    </div>

                    <div class="receipt-rule mt-5"></div>

                    <dl class="mt-4 grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                        <div>
                            <dt class="text-[10px] font-semibold uppercase tracking-wider text-steel">Date</dt>
                            <dd class="mt-0.5 font-medium text-ink"><?php echo e($placedAt?->timezone(config('app.timezone'))->format('d M Y')); ?></dd>
                        </div>
                        <div class="text-right">
                            <dt class="text-[10px] font-semibold uppercase tracking-wider text-steel">Time</dt>
                            <dd class="mt-0.5 font-medium text-ink"><?php echo e($placedAt?->timezone(config('app.timezone'))->format('H:i')); ?></dd>
                        </div>
                        <div>
                            <dt class="text-[10px] font-semibold uppercase tracking-wider text-steel">Device</dt>
                            <dd class="mt-0.5 font-medium text-ink"><?php echo e($order->device->name); ?></dd>
                        </div>
                        <div class="text-right">
                            <dt class="text-[10px] font-semibold uppercase tracking-wider text-steel">Fulfilment</dt>
                            <dd class="mt-0.5 font-medium text-ink">Mail-in</dd>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->scheduled_date): ?>
                            <div class="col-span-2">
                                <dt class="text-[10px] font-semibold uppercase tracking-wider text-steel">Scheduled</dt>
                                <dd class="mt-0.5 font-medium text-ink">
                                    <?php echo e($order->scheduled_date->format('d M Y')); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->scheduled_time): ?>
                                        · <?php echo e($order->scheduled_time); ?>

                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </dd>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </dl>

                    <div class="receipt-rule mt-5"></div>

                    <div class="mt-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-steel">Customer</p>
                        <p class="mt-1 font-medium text-ink"><?php echo e($order->customer->name); ?></p>
                        <p class="mt-0.5 text-sm text-steel"><?php echo e($order->customer->email); ?></p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($order->customer->phone)): ?>
                            <p class="text-sm text-steel"><?php echo e($order->customer->phone); ?></p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="receipt-rule mt-5"></div>

                    <table class="mt-4 w-full text-sm">
                        <thead>
                            <tr class="text-[10px] font-semibold uppercase tracking-wider text-steel">
                                <th class="pb-2 text-left font-semibold">Item</th>
                                <th class="pb-2 text-center font-semibold">Qty</th>
                                <th class="pb-2 text-right font-semibold">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-dashed divide-mist">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $repairItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="py-2.5 pr-2 text-ink">
                                        <?php echo e($item->name); ?>

                                        <span class="mt-0.5 block text-xs text-steel">Repair</span>
                                    </td>
                                    <td class="py-2.5 text-center text-steel"><?php echo e($item->quantity); ?></td>
                                    <td class="py-2.5 text-right font-medium text-ink">£<?php echo e(number_format((float) $item->price * $item->quantity, 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $addonItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="py-2.5 pr-2 text-ink">
                                        <?php echo e($item->name); ?>

                                        <span class="mt-0.5 block text-xs text-steel">Protect add-on</span>
                                    </td>
                                    <td class="py-2.5 text-center text-steel"><?php echo e($item->quantity); ?></td>
                                    <td class="py-2.5 text-right font-medium text-ink">£<?php echo e(number_format((float) $item->price * $item->quantity, 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>

                    <div class="receipt-rule mt-4"></div>

                    <dl class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between gap-4 text-steel">
                            <dt>Subtotal</dt>
                            <dd>£<?php echo e(number_format((float) $order->subtotal, 2)); ?></dd>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if((float) $order->discount_amount > 0): ?>
                            <div class="flex justify-between gap-4 text-accent">
                                <dt>Discount</dt>
                                <dd>-£<?php echo e(number_format((float) $order->discount_amount, 2)); ?></dd>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="flex justify-between gap-4 border-t border-dashed border-mist pt-3">
                            <dt class="font-display text-base font-bold text-ink">Total</dt>
                            <dd class="font-display text-xl font-bold text-accent">£<?php echo e(number_format((float) $order->total, 2)); ?></dd>
                        </div>
                        <div class="flex justify-between gap-4 pt-1">
                            <dt class="text-steel">Payment</dt>
                            <dd class="font-semibold <?php echo e($isPaid ? 'text-accent' : 'text-ink'); ?>">
                                <?php echo e($isPaid ? 'Paid online' : 'Pay on completion'); ?>

                            </dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-steel">Status</dt>
                            <dd class="font-medium capitalize text-ink"><?php echo e(str_replace('_', ' ', $order->status)); ?></dd>
                        </div>
                    </dl>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->fulfilment === 'mail_in' && $mailInAddress !== ''): ?>
                        <div class="receipt-rule mt-5"></div>
                        <div class="mt-4 rounded-lg bg-snow px-4 py-3">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-steel">Post your device to</p>
                            <p class="mt-2 whitespace-pre-line text-sm font-medium leading-relaxed text-ink"><?php echo e($mailInAddress); ?></p>
                            <p class="mt-2 text-xs text-steel">Include reference <span class="font-semibold text-ink"><?php echo e($order->reference); ?></span> inside the parcel.</p>
                        </div>
                    <?php elseif($order->fulfilment === 'mail_in'): ?>
                        <div class="receipt-rule mt-5"></div>
                        <p class="mt-4 text-sm text-steel">
                            Mail-in packing instructions and the return address are in your confirmation email.
                        </p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <p class="mt-6 text-center text-[11px] leading-relaxed text-steel">
                        12-month warranty on repairs · <?php echo e($siteSettings->displayName()); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(filled($siteSettings->contact_email)): ?>
                            · <?php echo e($siteSettings->contact_email); ?>

                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                </div>

                <div class="receipt-edge receipt-edge-bottom mt-4" aria-hidden="true"></div>
            </article>

            <div class="mt-8 flex flex-wrap justify-center gap-3 print:hidden">
                <button type="button" onclick="window.print()" class="btn-ghost" data-no-spinner>
                    Print receipt
                </button>
                <a href="<?php echo e(route('home')); ?>" class="btn-primary">Back to home</a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->device?->category): ?>
                    <a href="<?php echo e(route('categories.show', $order->device->category->slug)); ?>" class="btn-ghost">Browse more repairs</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laravel\repairing\resources\views/checkout/success.blade.php ENDPATH**/ ?>