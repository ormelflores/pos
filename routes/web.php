<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceVerificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResendVerificationCodeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
})->name('main');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'active', 'device.verified', 'verified', 'password.updated'])->name('dashboard');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::controller(DeviceVerificationController::class)->prefix('device')->group(function () {
        Route::get('/verification', 'show')->name('device.verification');
        Route::post('/verify', 'store')->name('device.verify');
    });

    Route::post('/resend/otp', [ResendVerificationCodeController::class, 'store'])->name('resend.otp');
});

Route::middleware(['auth', 'active', 'device.verified', 'password.updated'])->prefix('configurations')->group(function () {
    Route::middleware('admin')->group(function () {
        Route::controller(RoleController::class)->prefix('roles')->group(function () {
            Route::get('/', 'index')->name('roles.index');
            Route::post('/', 'store')->name('roles.store');
            Route::put('/{role:id}', 'update')->name('roles.update');
            Route::delete('/{role:id}', 'destroy')->name('roles.delete');
        });

        Route::controller(CategoryController::class)->prefix('categories')->group(function () {
            Route::get('/', 'index')->name('categories.index');
            Route::post('/', 'store')->name('categories.store');
            Route::put('/{category:uuid}', 'update')->name('categories.update');
            Route::delete('/{category:uuid}', 'destroy')->name('categories.delete');
        });

        Route::controller(ProductController::class)->prefix('products')->group(function () {
            Route::get('/', 'index')->name('products.index');
            Route::post('/', 'store')->name('products.store');
            Route::post('/{product:uuid}', 'update')->name('products.update');
            Route::delete('/{product:uuid}', 'destroy')->name('products.delete');
        });

        Route::controller(UserController::class)->prefix('users')->group(function () {
            Route::get('/', 'index')->name('users.index');
            Route::post('/', 'store')->name('users.store');
            Route::put('/{user:uuid}', 'update')->name('users.update');
            Route::post('/{user:uuid}/resend', 'resend')->name('users.password.resend');
        });

        Route::get('/reports', function () {
            return Inertia::render('Reports/Index');
        })->name('reports.index');

        Route::get('/activity-logs/{user:uuid}', [ActivityLogController::class, 'index'])->name('logs.activities.index');
    });

    Route::controller(TransactionController::class)->prefix('transactions')->group(function () {
        Route::get('/', 'index')->name('transactions.index');
        Route::get('/{transaction:uuid}', 'show')->name('transactions.show');
        Route::post('/', 'store')->name('transactions.store');
        Route::put('/{transaction:uuid}', 'update')->middleware('admin')->name('transactions.update');
        Route::post('/generate-receipt/{transaction:uuid}', 'generateReceipt')->name('transactions.receipt.generate');
    });

    Route::inertia('/orders', 'Kanban/Index')->name('orders.index');
    Route::inertia('/orders/display', 'Kanban/Display')->name('orders.show');
});

Route::get('webhook/facebook', [\App\Http\Controllers\WebhookController::class, 'verifyWebhook']);
Route::post('webhook/facebook', [\App\Http\Controllers\WebhookController::class, 'handleWebhook']);

require __DIR__.'/auth.php';
