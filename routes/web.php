<?php

use App\Http\Controllers\Api\PriceCheckerController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ShopCartController;
use App\Http\Controllers\ShopCheckoutController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', RobotsController::class)->name('robots');

Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
Route::get('/privacy', [PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('pages.terms');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/cart', [ShopCartController::class, 'show'])->name('shop.cart');
Route::post('/shop/cart/add', [ShopCartController::class, 'add'])->name('shop.cart.add');
Route::patch('/shop/cart/{accessory}', [ShopCartController::class, 'update'])->name('shop.cart.update');
Route::delete('/shop/cart/{accessory}', [ShopCartController::class, 'remove'])->name('shop.cart.remove');
Route::get('/shop/checkout', [ShopCheckoutController::class, 'show'])->name('shop.checkout');
Route::post('/shop/checkout', [ShopCheckoutController::class, 'store'])->name('shop.checkout.store');
Route::post('/shop/checkout/coupon', [ShopCheckoutController::class, 'applyCoupon'])->name('shop.checkout.coupon.apply');
Route::delete('/shop/checkout/coupon', [ShopCheckoutController::class, 'removeCoupon'])->name('shop.checkout.coupon.remove');
Route::get('/shop/checkout/stripe/success/{shopOrder}', [ShopCheckoutController::class, 'stripeSuccess'])->name('shop.checkout.stripe.success');
Route::get('/shop/checkout/stripe/cancel/{shopOrder}', [ShopCheckoutController::class, 'stripeCancel'])->name('shop.checkout.stripe.cancel');
Route::get('/shop/checkout/success/{shopOrder}', [ShopCheckoutController::class, 'success'])->name('shop.checkout.success');
Route::get('/shop/{accessory:slug}', [ShopController::class, 'show'])->name('shop.show');

Route::redirect('/accessories', '/shop');
Route::get('/accessories/{slug}', function (string $slug) {
    return redirect()->route('shop.show', $slug);
});

Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/repairs/{slug}', [DeviceController::class, 'show'])->name('devices.show');
Route::post('/repairs/{slug}/book', [DeviceController::class, 'book'])->name('devices.book');

Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

Route::get('/api/models/{categorySlug}', [PriceCheckerController::class, 'models'])->name('api.models');

Route::post('/quotes', [QuoteController::class, 'store'])->name('quotes.store');
