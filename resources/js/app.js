import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

function syncBagHeader(count) {
    const trigger = document.querySelector('[data-bag-open]');
    if (!trigger) {
        return;
    }

    trigger.setAttribute('aria-label', count > 0 ? `Shopping bag (${count} items)` : 'Shopping bag');

    let badge = trigger.querySelector('.cart-count');
    if (count > 0) {
        if (!badge) {
            badge = document.createElement('span');
            badge.className = 'cart-count';
            trigger.appendChild(badge);
        }
        badge.textContent = count > 99 ? '99+' : String(count);
    } else if (badge) {
        badge.remove();
    }
}

document.addEventListener('alpine:init', () => {
    const boot = window.__SHOP_BAG__ || {};
    const cart = boot.cart || { lines: [], totals: { subtotal: 0, count: 0 } };

    Alpine.store('lightbox', {
        open: false,
        src: '',
        alt: '',
        label: '',

        show(src, alt = '', label = '') {
            this.src = src || '';
            this.alt = alt || '';
            this.label = label || '';
            this.open = true;
            document.body.classList.add('overflow-hidden');
        },

        close() {
            this.open = false;
            this.src = '';
            this.alt = '';
            this.label = '';
            document.body.classList.remove('overflow-hidden');
        },
    });

    Alpine.store('bag', {
        open: false,
        saving: false,
        addingId: null,
        removingId: null,
        navigating: false,
        csrf: boot.csrf || '',
        addUrl: boot.addUrl || '/shop/cart/add',
        lines: cart.lines || [],
        totals: cart.totals || { subtotal: 0, count: 0 },

        money(value) {
            return '£' + Number(value || 0).toFixed(2);
        },

        openDrawer() {
            this.open = true;
            document.body.classList.add('overflow-hidden');
        },

        close() {
            this.open = false;
            document.body.classList.remove('overflow-hidden');
        },

        startCheckout() {
            this.navigating = true;
        },

        applyCart(nextCart) {
            this.lines = nextCart.lines || [];
            this.totals = nextCart.totals || { subtotal: 0, count: 0 };
            syncBagHeader(this.totals.count || 0);
        },

        async addProduct(accessoryId, quantity = 1) {
            this.saving = true;
            this.addingId = accessoryId;

            try {
                const response = await fetch(this.addUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        accessory_id: accessoryId,
                        quantity,
                    }),
                });

                if (response.status === 401 || response.status === 419) {
                    window.location.href = '/login?redirect=' + encodeURIComponent(window.location.href);
                    return;
                }

                const data = await response.json();

                if (!response.ok || !data.ok) {
                    throw new Error(data.message || 'Could not add item');
                }

                this.applyCart(data.cart);
                this.openDrawer();
            } catch (error) {
                alert(error.message || 'Could not add item to bag.');
            } finally {
                this.saving = false;
                this.addingId = null;
            }
        },

        async changeQty(line, quantity) {
            quantity = Math.max(0, Math.min(20, Number(quantity) || 0));

            if (quantity <= 0) {
                return this.removeLine(line);
            }

            // Instant UI update — sync to server in the background.
            line.quantity = quantity;
            line.line_total = Math.round(line.price * quantity * 100) / 100;
            this.recalcTotals();

            this._qtyTimers = this._qtyTimers || {};
            clearTimeout(this._qtyTimers[line.id]);
            this._qtyTimers[line.id] = setTimeout(() => {
                this.persistQty(line);
            }, 250);
        },

        recalcTotals() {
            const subtotal = this.lines.reduce((sum, item) => sum + Number(item.line_total || 0), 0);
            const count = this.lines.reduce((sum, item) => sum + Number(item.quantity || 0), 0);
            this.totals = {
                ...this.totals,
                subtotal: Math.round(subtotal * 100) / 100,
                count,
            };
            syncBagHeader(count);
        },

        async persistQty(line) {
            try {
                const response = await fetch(line.update_url, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ quantity: line.quantity }),
                });

                if (!response.ok) {
                    throw new Error('Update failed');
                }

                const data = await response.json();
                if (data.cart) {
                    this.applyCart(data.cart);
                }
            } catch (error) {
                // keep optimistic UI; next interaction can resync
            }
        },

        async removeLine(line) {
            this.removingId = line.id;
            this.lines = this.lines.filter((item) => item.id !== line.id);
            this.recalcTotals();

            try {
                const response = await fetch(line.remove_url, {
                    method: 'DELETE',
                    headers: {
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    throw new Error('Remove failed');
                }

                const data = await response.json();
                if (data.cart) {
                    this.applyCart(data.cart);
                }
            } catch (error) {
                // ignore
            } finally {
                this.removingId = null;
            }
        },
    });
});

window.productReviews = function productReviews(config) {
    const labels = {
        1: 'Poor',
        2: 'Fair',
        3: 'Good',
        4: 'Very good',
        5: 'Excellent',
    };

    return {
        reviews: Array.isArray(config.reviews) ? config.reviews : [],
        average: config.average,
        count: Number(config.count || 0),
        breakdown: config.breakdown || {},
        filter: 'all',
        sort: 'newest',
        showAll: false,
        pageSize: 4,
        formOpen: Boolean(config.formOpen),
        rating: Number(config.oldRating || 5),
        hoverRating: 0,
        body: config.oldBody || '',
        expanded: {},
        photoPreview: null,
        photoName: '',
        photoInput: null,

        get ratingLabel() {
            return labels[this.rating] || '';
        },

        get filtered() {
            let list = [...this.reviews];

            if (this.filter !== 'all') {
                list = list.filter((item) => item.rating === Number(this.filter));
            }

            if (this.sort === 'highest') {
                list.sort((a, b) => b.rating - a.rating || b.timestamp - a.timestamp);
            } else if (this.sort === 'lowest') {
                list.sort((a, b) => a.rating - b.rating || b.timestamp - a.timestamp);
            } else {
                list.sort((a, b) => b.timestamp - a.timestamp);
            }

            return list;
        },

        get visibleReviews() {
            return this.showAll ? this.filtered : this.filtered.slice(0, this.pageSize);
        },

        setFilter(value) {
            this.filter = value;
            this.showAll = false;
        },

        onPhotoChange(event) {
            const input = event.target;
            this.photoInput = input;
            const file = input.files?.[0] || null;
            this.setPhotoFile(file);
        },

        onPhotoDrop(event) {
            const file = event.dataTransfer?.files?.[0] || null;
            if (!file || !file.type.startsWith('image/')) {
                return;
            }

            const input = event.currentTarget.querySelector('input[type="file"]');
            if (input) {
                const transfer = new DataTransfer();
                transfer.items.add(file);
                input.files = transfer.files;
                this.photoInput = input;
            }

            this.setPhotoFile(file);
        },

        setPhotoFile(file) {
            if (this.photoPreview) {
                URL.revokeObjectURL(this.photoPreview);
            }

            if (!file) {
                this.photoPreview = null;
                this.photoName = '';
                return;
            }

            this.photoPreview = URL.createObjectURL(file);
            this.photoName = file.name;
        },

        clearPhoto() {
            if (this.photoPreview) {
                URL.revokeObjectURL(this.photoPreview);
            }

            this.photoPreview = null;
            this.photoName = '';

            if (this.photoInput) {
                this.photoInput.value = '';
            } else {
                const input = document.querySelector('#write-review input[type="file"][name="image"]');
                if (input) {
                    input.value = '';
                }
            }
        },
    };
};

window.productVariants = function productVariants(config) {
    const variants = Array.isArray(config.variants) ? config.variants : [];
    const optionKeys = [
        { key: 'condition', label: 'Select the condition', style: 'cards' },
        { key: 'storage', label: 'Select the storage', style: 'chips' },
        { key: 'color', label: 'Select the colour', style: 'chips' },
        { key: 'unlock_status', label: 'Network', style: 'chips' },
        { key: 'ram', label: 'Select the RAM', style: 'chips' },
        { key: 'cpu', label: 'Select the processor', style: 'chips' },
        { key: 'screen_size', label: 'Select the screen', style: 'chips' },
    ];

    const money = (value) => {
        const n = Number(value || 0);
        return '£' + n.toFixed(n % 1 ? 2 : 0);
    };

    const optionGroups = optionKeys
        .map((group) => {
            const values = [...new Set(variants.map((item) => item[group.key]).filter(Boolean))];
            return values.length ? { ...group, values } : null;
        })
        .filter(Boolean);

    const selected = {};
    optionGroups.forEach((group) => {
        const firstAvailable = group.values.find((value) =>
            variants.some((item) => item[group.key] === value && item.in_stock)
        );
        selected[group.key] = firstAvailable || group.values[0] || '';
    });

    return {
        variants,
        optionGroups,
        selected,
        quantity: 1,
        submitting: false,
        hasVariants: Boolean(config.hasVariants) && variants.length > 0,
        inStock: Boolean(config.inStock),
        baseImage: config.baseImage || null,
        basePrice: Number(config.basePrice || 0),
        baseCompare: config.baseCompare == null ? null : Number(config.baseCompare),
        productName: config.productName || '',

        get selectedVariant() {
            if (!this.hasVariants) {
                return null;
            }

            return (
                this.variants.find((item) =>
                    this.optionGroups.every((group) => item[group.key] === this.selected[group.key])
                ) || null
            );
        },

        get summaryBits() {
            return this.optionGroups
                .map((group) => this.selected[group.key])
                .filter(Boolean);
        },

        get allOptionsChosen() {
            return this.optionGroups.every((group) => Boolean(this.selected[group.key]));
        },

        get currentImage() {
            return this.selectedVariant?.image || this.baseImage;
        },

        get priceValue() {
            if (this.selectedVariant) {
                return Number(this.selectedVariant.price);
            }

            if (this.hasVariants && this.variants.length) {
                return Math.min(...this.variants.map((item) => Number(item.price)));
            }

            return this.basePrice;
        },

        get priceLabel() {
            if (this.selectedVariant) {
                return this.selectedVariant.price_formatted;
            }

            if (this.hasVariants && this.variants.length) {
                return 'From ' + money(this.priceValue);
            }

            return money(this.basePrice);
        },

        get compareLabel() {
            const compare = this.selectedVariant
                ? this.selectedVariant.compare_at_price
                : this.baseCompare;

            if (!compare) {
                return '';
            }

            if (Number(compare) <= this.priceValue) {
                return '';
            }

            return money(compare);
        },

        get savingsLabel() {
            const compare = this.selectedVariant
                ? this.selectedVariant.compare_at_price
                : this.baseCompare;

            if (!compare || Number(compare) <= this.priceValue) {
                return '';
            }

            const pct = Math.round(((Number(compare) - this.priceValue) / Number(compare)) * 100);
            return pct > 0 ? pct + '% off' : '';
        },

        get canAdd() {
            if (!this.inStock) {
                return false;
            }

            if (!this.hasVariants) {
                return true;
            }

            return Boolean(this.selectedVariant?.in_stock);
        },

        matchesExcept(item, exceptKey) {
            return this.optionGroups.every((group) => {
                if (group.key === exceptKey) {
                    return true;
                }

                const chosen = this.selected[group.key];
                return !chosen || item[group.key] === chosen;
            });
        },

        isOptionAvailable(key, value) {
            return this.variants.some(
                (item) => item[key] === value && item.in_stock && this.matchesExcept(item, key)
            );
        },

        optionPrice(key, value) {
            const matches = this.variants.filter(
                (item) => item[key] === value && this.matchesExcept(item, key)
            );

            if (!matches.length) {
                return null;
            }

            const inStock = matches.filter((item) => item.in_stock);
            const pool = inStock.length ? inStock : matches;
            return Math.min(...pool.map((item) => Number(item.price)));
        },

        optionPriceLabel(key, value) {
            const price = this.optionPrice(key, value);
            return price == null ? '' : money(price);
        },

        selectOption(key, value) {
            this.selected[key] = value;

            this.optionGroups.forEach((group) => {
                if (group.key === key) {
                    return;
                }

                if (!this.isOptionAvailable(group.key, this.selected[group.key])) {
                    const next = group.values.find((candidate) => this.isOptionAvailable(group.key, candidate));
                    this.selected[group.key] = next || group.values[0] || '';
                }
            });
        },
    };
};

window.shopCart = function shopCart(config) {
    return {
        csrf: config.csrf,
        lines: config.lines,
        totals: config.totals,
        removingId: null,
        navigating: false,
        ready: false,
        _qtyTimers: {},

        init() {
            setTimeout(() => {
                this.ready = true;
            }, 280);
        },

        money(value) {
            return '£' + Number(value || 0).toFixed(2);
        },

        updateHeaderCount(count) {
            syncBagHeader(count);
            if (window.Alpine?.store('bag')) {
                Alpine.store('bag').totals = { ...Alpine.store('bag').totals, count };
            }
        },

        recalcTotals() {
            const subtotal = this.lines.reduce((sum, item) => sum + Number(item.line_total || 0), 0);
            const count = this.lines.reduce((sum, item) => sum + Number(item.quantity || 0), 0);
            this.totals = {
                ...this.totals,
                subtotal: Math.round(subtotal * 100) / 100,
                count,
            };
            this.updateHeaderCount(count);
        },

        changeQty(line, quantity) {
            quantity = Math.max(0, Math.min(20, Number(quantity) || 0));

            if (quantity <= 0) {
                return this.removeLine(line);
            }

            line.quantity = quantity;
            line.line_total = Math.round(line.price * quantity * 100) / 100;
            this.recalcTotals();

            clearTimeout(this._qtyTimers[line.id]);
            this._qtyTimers[line.id] = setTimeout(() => {
                this.persistQty(line);
            }, 250);
        },

        async persistQty(line) {
            try {
                const response = await fetch(line.update_url, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ quantity: line.quantity }),
                });

                if (!response.ok) {
                    throw new Error('Update failed');
                }

                const data = await response.json();

                if (data.cart) {
                    this.lines = data.cart.lines;
                    this.totals = data.cart.totals;
                    if (window.Alpine?.store('bag')) {
                        Alpine.store('bag').applyCart(data.cart);
                    } else {
                        this.updateHeaderCount(data.totals.count);
                    }
                }

                if (data.empty) {
                    window.location.reload();
                }
            } catch (error) {
                // keep optimistic UI
            }
        },

        async removeLine(line) {
            this.removingId = line.id;
            this.lines = this.lines.filter((item) => item.id !== line.id);
            this.recalcTotals();

            try {
                const response = await fetch(line.remove_url, {
                    method: 'DELETE',
                    headers: {
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    throw new Error('Remove failed');
                }

                const data = await response.json();

                if (data.cart) {
                    this.lines = data.cart.lines;
                    this.totals = data.cart.totals;
                    if (window.Alpine?.store('bag')) {
                        Alpine.store('bag').applyCart(data.cart);
                    } else {
                        this.updateHeaderCount(data.totals.count);
                    }
                }

                if (data.empty) {
                    window.location.reload();
                }
            } catch (error) {
                // keep optimistic removal
            } finally {
                this.removingId = null;
            }
        },
    };
};

window.shopCheckout = function shopCheckout(config = {}) {
    const initialFulfilment = config.fulfilment || 'collection';

    return {
        ready: false,
        submitting: false,
        couponBusy: false,
        couponInput: '',
        couponError: '',
        couponMessage: '',
        coupon: config.coupon || null,
        fulfilment: initialFulfilment,
        subtotal: Number(config.subtotal || 0),
        shipping: Number(config.shipping ?? (initialFulfilment === 'delivery' ? 3.99 : 0)),
        discount: Number(config.discount || 0),
        applyUrl: config.applyUrl || '/shop/checkout/coupon',
        removeUrl: config.removeUrl || '/shop/checkout/coupon',
        csrf: config.csrf || '',

        get total() {
            return Math.max(0, this.subtotal + this.shipping - this.discount);
        },

        init() {
            setTimeout(() => {
                this.ready = true;
                this.syncTotals();
            }, 280);
        },

        money(value) {
            return '£' + Number(value || 0).toFixed(2);
        },

        syncTotals() {
            const checked = document.querySelector('input[name="fulfilment"]:checked')?.value || this.fulfilment;
            this.fulfilment = checked;
            this.shipping = checked === 'delivery' ? 3.99 : 0;
        },

        applyTotals(totals) {
            this.subtotal = Number(totals.subtotal || 0);
            this.shipping = Number(totals.shipping_amount || 0);
            this.discount = Number(totals.discount_amount || 0);
            this.coupon = totals.coupon || null;
        },

        async applyCoupon() {
            this.couponError = '';
            this.couponMessage = '';
            const code = (this.couponInput || '').trim();

            if (!code) {
                this.couponError = 'Enter a coupon code.';
                return;
            }

            this.couponBusy = true;
            this.syncTotals();

            try {
                const response = await fetch(this.applyUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        coupon_code: code,
                        fulfilment: this.fulfilment,
                    }),
                });

                const data = await response.json();

                if (!response.ok || !data.ok) {
                    throw new Error(data.message || 'Could not apply coupon.');
                }

                this.applyTotals(data.totals);
                this.couponInput = '';
                this.couponMessage = data.message || 'Coupon applied.';
            } catch (error) {
                this.couponError = error.message || 'Could not apply coupon.';
            } finally {
                this.couponBusy = false;
            }
        },

        async removeCoupon() {
            this.couponBusy = true;
            this.couponError = '';
            this.couponMessage = '';
            this.syncTotals();

            try {
                const response = await fetch(this.removeUrl, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ fulfilment: this.fulfilment }),
                });

                const data = await response.json();

                if (!response.ok || !data.ok) {
                    throw new Error(data.message || 'Could not remove coupon.');
                }

                this.applyTotals(data.totals);
                this.couponMessage = data.message || 'Coupon removed.';
            } catch (error) {
                this.couponError = error.message || 'Could not remove coupon.';
            } finally {
                this.couponBusy = false;
            }
        },
    };
};

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    initMobileNav();
    initDesktopDropdowns();
    initMobileAccordions();
    initPriceChecker();
    initQuoteModal();
    initButtonLoading();
});

/**
 * Universal processing spinner for submit / navigation buttons.
 * Skip UI-only controls with data-no-spinner (or known toggle selectors).
 */
function initButtonLoading() {
    const skipSelector = [
        '[data-no-spinner]',
        '[data-nav-trigger]',
        '[data-mobile-trigger]',
        '[data-bag-open]',
        '[data-quote-open]',
        '#nav-toggle',
    ].join(', ');

    const shouldSkip = (el) => {
        if (!el || el.dataset.loading === '1') {
            return true;
        }

        if (el.disabled || el.getAttribute('aria-disabled') === 'true') {
            return true;
        }

        return el.matches(skipSelector) || Boolean(el.closest(skipSelector));
    };

    const needsDarkSpinner = (button) => {
        if (button.classList.contains('btn-primary') || button.classList.contains('btn-secondary')) {
            return false;
        }

        return true;
    };

    const setLoading = (button) => {
        if (shouldSkip(button)) {
            return;
        }

        button.dataset.loading = '1';
        button.classList.add('btn-loading', 'is-processing');
        button.setAttribute('aria-busy', 'true');

        if ('disabled' in button) {
            button.disabled = true;
        }

        let spinner = button.querySelector(':scope > .btn-spinner');

        if (!spinner) {
            spinner = document.createElement('span');
            spinner.className = 'btn-spinner' + (needsDarkSpinner(button) ? ' is-dark' : '');
            spinner.setAttribute('aria-hidden', 'true');
            button.insertBefore(spinner, button.firstChild);
        } else {
            spinner.classList.remove('hidden');
            spinner.style.display = 'inline-block';
            if (needsDarkSpinner(button)) {
                spinner.classList.add('is-dark');
            }
        }
    };

    document.addEventListener(
        'submit',
        (event) => {
            const form = event.target;
            if (!(form instanceof HTMLFormElement) || form.hasAttribute('data-no-spinner')) {
                return;
            }

            const button =
                event.submitter instanceof HTMLElement
                    ? event.submitter
                    : form.querySelector('button[type="submit"], input[type="submit"], button:not([type])');

            setLoading(button);
        },
        true
    );

    document.addEventListener(
        'click',
        (event) => {
            const el = event.target.closest(
                'a.btn-primary, a.btn-secondary, a.btn-ghost, button.btn-primary, button.btn-secondary, button.btn-ghost, [data-loading-btn]'
            );

            if (!el || shouldSkip(el)) {
                return;
            }

            if (el.tagName === 'A') {
                const href = el.getAttribute('href') || '';
                if (
                    !href ||
                    href === '#' ||
                    href.startsWith('#') ||
                    href.startsWith('javascript:') ||
                    href.startsWith('mailto:') ||
                    href.startsWith('tel:') ||
                    el.hasAttribute('download') ||
                    el.getAttribute('target') === '_blank'
                ) {
                    return;
                }

                setLoading(el);
                return;
            }

            if (el.type === 'submit') {
                return;
            }

            if (el.hasAttribute('data-loading-btn')) {
                setLoading(el);
            }
        },
        true
    );
}

function initMobileNav() {
    const toggle = document.getElementById('nav-toggle');
    const panel = document.getElementById('nav-panel');

    if (!toggle || !panel) {
        return;
    }

    const openLabel = 'Open menu';
    const closeLabel = 'Close menu';

    toggle.addEventListener('click', (event) => {
        event.stopPropagation();
        const isOpen = panel.classList.toggle('hidden') === false;
        toggle.setAttribute('aria-expanded', String(isOpen));
        toggle.setAttribute('aria-label', isOpen ? closeLabel : openLabel);

        if (!isOpen) {
            closeAllMobileAccordions();
        }
    });

    panel.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            panel.classList.add('hidden');
            toggle.setAttribute('aria-expanded', 'false');
            toggle.setAttribute('aria-label', openLabel);
            closeAllMobileAccordions();
        });
    });
}

function initDesktopDropdowns() {
    const dropdowns = document.querySelectorAll('[data-nav-dropdown]');

    if (!dropdowns.length) {
        return;
    }

    const closeAll = (except = null) => {
        dropdowns.forEach((dropdown) => {
            if (dropdown === except) {
                return;
            }

            const trigger = dropdown.querySelector('[data-nav-trigger]');
            const panel = dropdown.querySelector('[data-nav-panel]');
            const chevron = dropdown.querySelector('[data-nav-chevron]');

            panel?.classList.add('hidden');
            trigger?.setAttribute('aria-expanded', 'false');
            chevron?.classList.remove('rotate-180');
        });
    };

    dropdowns.forEach((dropdown) => {
        const trigger = dropdown.querySelector('[data-nav-trigger]');
        const panel = dropdown.querySelector('[data-nav-panel]');
        const chevron = dropdown.querySelector('[data-nav-chevron]');

        if (!trigger || !panel) {
            return;
        }

        trigger.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();

            const willOpen = panel.classList.contains('hidden');
            closeAll(dropdown);

            panel.classList.toggle('hidden', !willOpen);
            trigger.setAttribute('aria-expanded', String(willOpen));
            chevron?.classList.toggle('rotate-180', willOpen);
        });
    });

    document.addEventListener('click', () => closeAll());

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeAll();
        }
    });
}

function initMobileAccordions() {
    document.querySelectorAll('[data-mobile-accordion]').forEach((item) => {
        const trigger = item.querySelector('[data-mobile-trigger]');
        const panel = item.querySelector('[data-mobile-panel]');
        const chevron = item.querySelector('[data-mobile-chevron]');

        if (!trigger || !panel) {
            return;
        }

        trigger.addEventListener('click', (event) => {
            event.stopPropagation();
            const willOpen = panel.classList.contains('hidden');

            closeAllMobileAccordions(item);

            panel.classList.toggle('hidden', !willOpen);
            trigger.setAttribute('aria-expanded', String(willOpen));
            chevron?.classList.toggle('rotate-180', willOpen);
        });
    });
}

function closeAllMobileAccordions(except = null) {
    document.querySelectorAll('[data-mobile-accordion]').forEach((item) => {
        if (item === except) {
            return;
        }

        item.querySelector('[data-mobile-panel]')?.classList.add('hidden');
        item.querySelector('[data-mobile-trigger]')?.setAttribute('aria-expanded', 'false');
        item.querySelector('[data-mobile-chevron]')?.classList.remove('rotate-180');
    });
}

function initPriceChecker() {
    const brand = document.getElementById('pc-brand');
    const model = document.getElementById('pc-model');
    const repair = document.getElementById('pc-repair');
    const out = document.getElementById('pc-out');

    if (!brand || !model || !repair || !out) {
        return;
    }

    const cache = {};
    let current = [];

    function resetSelect(select, text, disabled = true) {
        select.innerHTML = '';
        select.add(new Option(text, ''));
        select.disabled = disabled;
        select.classList.toggle('is-disabled', disabled);
    }

    function blank() {
        out.className = 'pc-out mt-4';
        out.textContent = 'Your price and turnaround will appear here.';
    }

    async function loadBrand(slug) {
        resetSelect(model, 'Loading models…');
        resetSelect(repair, 'Select a model first');
        blank();

        if (!slug) {
            resetSelect(model, 'Select a brand first');
            blank();
            return;
        }

        try {
            if (!cache[slug]) {
                const response = await fetch(`/api/models/${encodeURIComponent(slug)}`);
                if (!response.ok) {
                    throw new Error('Failed to load models');
                }
                cache[slug] = await response.json();
            }

            current = cache[slug];
            resetSelect(model, 'Select your model…', false);
            current.forEach((item, index) => {
                model.add(new Option(item.m, String(index)));
            });
            blank();
        } catch (error) {
            resetSelect(model, 'Could not load models');
            blank();
        }
    }

    brand.addEventListener('change', () => {
        loadBrand(brand.value);
    });

    model.addEventListener('change', () => {
        resetSelect(repair, 'Select your repair…');
        blank();

        if (model.value === '') {
            resetSelect(repair, 'Select a model first');
            return;
        }

        const selected = current[Number(model.value)];
        if (!selected) {
            return;
        }

        resetSelect(repair, 'Select your repair…', false);
        selected.r.forEach((item, index) => {
            const label = item.e ? `${item.e} ${item.n}` : item.n;
            repair.add(new Option(label, String(index)));
        });
    });

    repair.addEventListener('change', () => {
        if (repair.value === '') {
            blank();
            return;
        }

        const selectedModel = current[Number(model.value)];
        const selectedRepair = selectedModel?.r[Number(repair.value)];

        if (!selectedModel || !selectedRepair) {
            blank();
            return;
        }

        const turnaround = selectedRepair.t || 'Same day';
        const warranty = selectedRepair.w || 12;
        const repairLabel = selectedRepair.e
            ? `${selectedRepair.e} ${selectedRepair.n}`
            : selectedRepair.n;

        out.className = 'pc-out is-live mt-4';
        out.innerHTML = `
            <div class="flex flex-wrap items-end justify-between gap-3">
                <span>
                    <span class="mb-1 block text-[11px] font-semibold uppercase tracking-wider text-steel">Guide price from</span>
                    <span class="font-display text-3xl font-bold text-accent">${selectedRepair.p}</span>
                </span>
                <span class="text-right text-sm font-medium text-steel">
                    ${repairLabel}<br>
                    ${turnaround} · ${warranty}-month warranty
                </span>
            </div>
            <a href="${selectedModel.u}?repair=${encodeURIComponent(selectedRepair.i)}" class="btn-primary mt-4 w-full rounded-full">
                See ${selectedModel.m} repair details
            </a>
        `;
    });
}

function initQuoteModal() {
    const modal = document.getElementById('quote-modal');

    if (!modal) {
        return;
    }

    const dialog = modal.querySelector('[data-quote-dialog]');
    const openers = document.querySelectorAll('[data-quote-open]');
    const closers = modal.querySelectorAll('[data-quote-close]');
    const shouldAutoOpen = modal.dataset.openOnLoad === '1';

    const open = (event) => {
        if (event) {
            event.preventDefault();
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('overflow-hidden');
        modal.querySelector('input[name="name"]')?.focus();
    };

    const close = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('overflow-hidden');
    };

    openers.forEach((button) => {
        button.addEventListener('click', open);
    });

    closers.forEach((button) => {
        button.addEventListener('click', close);
    });

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            close();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            close();
        }
    });

    dialog?.addEventListener('click', (event) => {
        event.stopPropagation();
    });

    if (shouldAutoOpen) {
        open();
    }
}
