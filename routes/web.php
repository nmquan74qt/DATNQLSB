<?php

use Illuminate\Support\Facades\Route;

// Public Controllers
use App\Http\Controllers\HomeController;

// Auth Controllers
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

// Customer Portal Controllers
use App\Http\Controllers\Customer\CustomerPortalController;
use App\Http\Controllers\Customer\CustomerBookingController;

// Admin Portal Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FieldController;
use App\Http\Controllers\Admin\FieldTypeController;
use App\Http\Controllers\Admin\TimeSlotController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Public Pages (Accessible by everyone)
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('contact', [\App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

// 2. Auth Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// Auth-dependent logout
Route::middleware('auth')->post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// 3. Customer Portal (Auth + Customer Role)
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('dashboard', [CustomerPortalController::class, 'dashboard'])->name('dashboard');
    
    // Profile
    Route::get('profile', [CustomerPortalController::class, 'editProfile'])->name('profile.edit');
    Route::post('profile', [CustomerPortalController::class, 'updateProfile'])->name('profile.update');

    // Booking Wizard
    Route::get('bookings/create', [CustomerBookingController::class, 'create'])->name('bookings.create');
    Route::post('bookings/check-availability', [CustomerBookingController::class, 'checkAvailability'])->name('bookings.check-availability');
    Route::post('bookings/store', [CustomerBookingController::class, 'store'])->name('bookings.store');
    Route::get('bookings/{booking}', [CustomerPortalController::class, 'showBooking'])->name('bookings.show');
    Route::post('bookings/{booking}/cancel', [CustomerBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('bookings/{booking}/review', [CustomerPortalController::class, 'storeReview'])->name('bookings.review');
    
    // Field Reviews
    Route::get('fields/{field}/reviews', [CustomerBookingController::class, 'getFieldReviews'])->name('fields.reviews');

    // Payments
    Route::get('payment/vnpay-return', [\App\Http\Controllers\Customer\PaymentController::class, 'vnpayReturn'])->name('payment.vnpay.return');
    Route::get('payment/process/{booking}', [\App\Http\Controllers\Customer\PaymentController::class, 'process'])->name('payment.process');
    Route::get('payment/momo/{booking}', [\App\Http\Controllers\Customer\PaymentController::class, 'momoQR'])->name('payment.momo.qr');
});

// 4. Admin & Staff Portal (Auth + Staff/Manager Role)
Route::middleware(['auth', 'role:manager,staff'])->prefix('admin')->name('admin.')->group(function () {
    
    // Shareable Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Bookings Management
    Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('bookings/{booking}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
    Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('bookings/{booking}/add-services', [BookingController::class, 'addServices'])->name('bookings.add-services');
    Route::delete('bookings/{booking}/remove-service/{order}', [BookingController::class, 'removeService'])->name('bookings.remove-service');
    Route::post('bookings/{booking}/checkout', [BookingController::class, 'checkout'])->name('bookings.checkout');

    // Customers Directory
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('customers/store', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::post('customers/{customer}/update', [CustomerController::class, 'update'])->name('customers.update');
    Route::post('customers/{customer}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('customers.toggle-status');

    // Services Catalog
    Route::resource('services', ServiceController::class)->except(['show']);

    // Payments Records
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');

    // Invoices Management
    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');

    // 5. Manager ONLY Routes (Auth + Manager Role)
    Route::middleware('role:manager')->group(function () {
        // Football Fields Settings
        Route::resource('fields', FieldController::class)->except(['show']);
        Route::resource('field-types', FieldTypeController::class)->except(['show']);
        Route::resource('time-slots', TimeSlotController::class)->except(['show']);

        // Staff Accounts Settings
        Route::get('staffs', [StaffController::class, 'index'])->name('staffs.index');
        Route::get('staffs/create', [StaffController::class, 'create'])->name('staffs.create');
        Route::post('staffs/store', [StaffController::class, 'store'])->name('staffs.store');
        Route::get('staffs/{staff}/edit', [StaffController::class, 'edit'])->name('staffs.edit');
        Route::post('staffs/{staff}/update', [StaffController::class, 'update'])->name('staffs.update');
        Route::post('staffs/{staff}/toggle-status', [StaffController::class, 'toggleStatus'])->name('staffs.toggle-status');
        Route::delete('staffs/{staff}', [StaffController::class, 'destroy'])->name('staffs.destroy');

        // General Financial Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export-csv', [ReportController::class, 'exportCsv'])->name('reports.export-csv');
    });
});

