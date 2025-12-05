<?php

use App\Http\Controllers\Account\DashboardController;
use App\Http\Controllers\Account\OrderHistoryController;
use App\Http\Controllers\Api\CheckoutController as ApiCheckoutController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::get('/category', [CategoryController::class, 'index'])->name('category');

Route::view('/checkout', 'checkout')->middleware(['auth'])->name('checkout');

Route::middleware('auth')->group(function () {
    Route::redirect('/account', '/account/dashboard')->name('account');
    Route::get('/account/dashboard', DashboardController::class)->name('account.dashboard');
    Route::get('/account/orders', [OrderHistoryController::class, 'index'])->name('account.orders');
    Route::get('/orders/{order}/invoice', [InvoiceController::class, 'show'])->name('orders.invoice');

    Route::post('/api/checkout', [ApiCheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/api/orders/{orderNumber}/status', [ApiCheckoutController::class, 'status'])->name('orders.status');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    return redirect()->route('account.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
