<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Public Homepage
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PaymentController;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/fields', [App\Http\Controllers\PageController::class, 'fields'])->name('fields.index');
Route::get('/fields/{slug}', [App\Http\Controllers\PageController::class, 'fieldDetail'])->name('field.detail');
Route::post('/book', [App\Http\Controllers\BookingController::class, 'store'])->name('book');
Route::get('/api/booking-status/{code}', [App\Http\Controllers\BookingController::class, 'checkPaymentStatus'])->name('api.booking.status');
Route::get('/api/webhook/simulate/{code}', [App\Http\Controllers\BookingController::class, 'simulateWebhook'])->name('api.webhook.simulate');
Route::post('/voucher/check', [App\Http\Controllers\BookingController::class, 'checkVoucher'])->name('voucher.check');
Route::get('/blog', [PostController::class, 'index'])->name('blog.index');

Route::get('/payment/create', [PaymentController::class, 'createPayment'])->name('payment.create');
Route::get('/payment/vnpay-return', [PaymentController::class, 'vnpayReturn'])->name('payment.vnpay.return');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Admin/Staff Area
    Route::prefix('admin')->middleware('role:admin,staff')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            if (auth()->user()->role === 'staff') {
                return view('staff.dashboard');
            }
            return app()->make(\App\Http\Controllers\Admin\DashboardController::class)->index();
        })->name('dashboard');

        Route::resource('fields', \App\Http\Controllers\FieldController::class);

        // Core Management
        Route::get('/bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
        Route::post('/bookings', [\App\Http\Controllers\Admin\BookingController::class, 'store'])->name('bookings.store');
        Route::put('/bookings/{id}/status', [\App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('bookings.update-status');
        Route::get('/bookings/calendar-data', [\App\Http\Controllers\Admin\BookingController::class, 'calendarData'])->name('bookings.calendar');
        Route::get('/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
        Route::put('/customers/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/customers/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'destroy'])->name('customers.destroy');
        
        // HR / Staff Management
        Route::middleware('role:admin')->group(function() {
            Route::get('/payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
            Route::get('/payments/export', [\App\Http\Controllers\Admin\PaymentController::class, 'export'])->name('payments.export');

            Route::get('/staff', [\App\Http\Controllers\StaffController::class, 'index'])->name('staff.index');
            Route::post('/staff', [\App\Http\Controllers\StaffController::class, 'store'])->name('staff.store');
            Route::put('/staff/{id}', [\App\Http\Controllers\StaffController::class, 'update'])->name('staff.update');
            Route::delete('/staff/{id}', [\App\Http\Controllers\StaffController::class, 'destroy'])->name('staff.destroy');
            Route::post('/staff/attendance', [\App\Http\Controllers\StaffController::class, 'markAttendance'])->name('staff.attendance');
            Route::post('/staff/payroll', [\App\Http\Controllers\StaffController::class, 'generatePayroll'])->name('staff.payroll');

            // System Admin
            Route::match(['get', 'post'], '/system/settings', [\App\Http\Controllers\Admin\SystemController::class, 'settings'])->name('system.settings');
            Route::post('/system/backup', [\App\Http\Controllers\Admin\SystemController::class, 'backupDatabase'])->name('system.backup');
        });

        // Marketing / Vouchers / Blog
        Route::get('/vouchers', [\App\Http\Controllers\VoucherController::class, 'index'])->name('vouchers.index');
        Route::post('/vouchers', [\App\Http\Controllers\VoucherController::class, 'store'])->name('vouchers.store');
        Route::put('/vouchers/{id}', [\App\Http\Controllers\VoucherController::class, 'update'])->name('vouchers.update');
        Route::delete('/vouchers/{id}', [\App\Http\Controllers\VoucherController::class, 'destroy'])->name('vouchers.destroy');
        Route::resource('/posts', \App\Http\Controllers\Admin\PostController::class);
    });

    // Customer Area
    Route::prefix('customer')->middleware('role:customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\CustomerController::class, 'dashboard'])->name('dashboard');
    });
});
