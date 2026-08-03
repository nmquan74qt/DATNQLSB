<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;

// Public Homepage
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PaymentController;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/fields', [App\Http\Controllers\PageController::class, 'fields'])->name('fields.index');
Route::get('/fields/{slug}', [App\Http\Controllers\PageController::class, 'fieldDetail'])->name('field.detail');
Route::get('/api/booking-status/{code}', [App\Http\Controllers\BookingController::class, 'checkPaymentStatus'])->name('api.booking.status');
Route::get('/api/webhook/simulate/{code}', [App\Http\Controllers\BookingController::class, 'simulateWebhook'])->name('api.webhook.simulate');
Route::get('/blog', [PostController::class, 'index'])->name('blog.index');

Route::get('/payment/create', [PaymentController::class, 'createPayment'])->name('payment.create');
Route::get('/payment/vnpay-return', [PaymentController::class, 'vnpayReturn'])->name('payment.vnpay.return');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    
    // Google OAuth Routes
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    // Protected Booking Actions
    Route::post('/book', [App\Http\Controllers\BookingController::class, 'store'])->name('book');
    Route::post('/voucher/check', [App\Http\Controllers\BookingController::class, 'checkVoucher'])->name('voucher.check');

    // Admin & Staff Area (Shared POS Console)
    Route::prefix('admin')->middleware('role:admin,staff')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // POS Core: Bookings, Calendar, Check-in/out
        Route::get('/bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
        Route::post('/bookings', [\App\Http\Controllers\Admin\BookingController::class, 'store'])->name('bookings.store');
        Route::post('/bookings/batch', [\App\Http\Controllers\Admin\BookingController::class, 'batchStore'])->name('bookings.batch-store');
        Route::put('/bookings/{id}/status', [\App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('bookings.update-status');
        Route::get('/bookings/calendar-data', [\App\Http\Controllers\Admin\BookingController::class, 'calendarData'])->name('bookings.calendar');
        
        // Customers & Blacklist checking
        Route::get('/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
        Route::put('/customers/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/customers/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'destroy'])->name('customers.destroy');
        
        // Payments & Bills
        Route::get('/payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
        
        // Admin Only Area (Configuration & Sensitive Data)
        Route::middleware('role:admin')->group(function () {
            Route::resource('fields', \App\Http\Controllers\FieldController::class);
            Route::resource('time-slots', \App\Http\Controllers\Admin\TimeSlotController::class)->except(['create', 'edit', 'show']);
            Route::resource('staff', \App\Http\Controllers\Admin\StaffController::class)->except(['create', 'edit', 'show']);
            Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
            Route::get('/payments/export', [\App\Http\Controllers\Admin\PaymentController::class, 'export'])->name('payments.export');
            Route::match(['get', 'post'], '/system/settings', [\App\Http\Controllers\Admin\SystemController::class, 'settings'])->name('system.settings');
            Route::post('/system/backup', [\App\Http\Controllers\Admin\SystemController::class, 'backupDatabase'])->name('system.backup');
            Route::get('/vouchers', [\App\Http\Controllers\VoucherController::class, 'index'])->name('vouchers.index');
            Route::post('/vouchers', [\App\Http\Controllers\VoucherController::class, 'store'])->name('vouchers.store');
            Route::put('/vouchers/{id}', [\App\Http\Controllers\VoucherController::class, 'update'])->name('vouchers.update');
            Route::delete('/vouchers/{id}', [\App\Http\Controllers\VoucherController::class, 'destroy'])->name('vouchers.destroy');
            Route::resource('/posts', \App\Http\Controllers\Admin\PostController::class);
        });
    });

    // Customer Area
    Route::prefix('customer')->middleware('role:customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\CustomerController::class, 'dashboard'])->name('dashboard');
        Route::put('/profile', [\App\Http\Controllers\CustomerController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [\App\Http\Controllers\CustomerController::class, 'updatePassword'])->name('profile.password');
        Route::put('/bookings/{id}/cancel', [\App\Http\Controllers\CustomerController::class, 'cancelBooking'])->name('bookings.cancel');

        // Notifications
        Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.readAll');
        Route::get('/notifications/demo', [\App\Http\Controllers\NotificationController::class, 'demo'])->name('notifications.demo');
    });
});
