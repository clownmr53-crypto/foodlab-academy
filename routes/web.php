<?php

use App\Http\Controllers\Admin\ContactMessageController as AdminContactController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\LegalPageController as AdminLegalController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Admin\ModuleController as AdminModuleController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\LmsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/legal/{slug}', [LegalController::class, 'show'])->name('legal.show');
Route::get('/verify-certificate', [CertificateController::class, 'verify'])->name('certificates.verify');

Route::post('/webhooks/stripe', [PaymentController::class, 'stripeWebhook'])->name('webhooks.stripe');
Route::post('/webhooks/mobile-money', [PaymentController::class, 'mobileMoneyWebhook'])->name('webhooks.mm');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/plans', [PaymentController::class, 'plans'])->name('payments.plans');
    Route::post('/checkout', [PaymentController::class, 'checkout'])->name('payments.checkout');
    Route::post('/payments/{payment}/simulate', [PaymentController::class, 'simulateSuccess'])->name('payments.simulate');
    Route::get('/payments/{payment}/success', [PaymentController::class, 'success'])->name('payments.success');

    Route::middleware('plan:starter')->group(function () {
        Route::get('/lms', [LmsController::class, 'index'])->name('lms.index');
        Route::get('/lms/modules/{module}', [LmsController::class, 'showModule'])->name('lms.module');
        Route::get('/lms/modules/{module}/lessons/{lesson}', [LmsController::class, 'showLesson'])->name('lms.lesson');
        Route::post('/lms/modules/{module}/lessons/{lesson}/complete', [LmsController::class, 'completeLesson'])->name('lms.lesson.complete');

        Route::get('/calculator', [CalculatorController::class, 'index'])->name('calculator.index');
        Route::post('/calculator', [CalculatorController::class, 'store'])->name('calculator.store');
        Route::get('/calculator/{calculator}', [CalculatorController::class, 'show'])->name('calculator.show');
        Route::get('/calculator/{calculator}/pdf', [CalculatorController::class, 'pdf'])->name('calculator.pdf');
        Route::get('/calculator/{calculator}/excel', [CalculatorController::class, 'excel'])->name('calculator.excel');
    });

    Route::get('/certificates/mine', [CertificateController::class, 'mine'])->name('certificates.mine');
    Route::get('/certificates/download', [CertificateController::class, 'download'])->name('certificates.download');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::resource('modules', AdminModuleController::class)->except(['show']);
    Route::resource('modules.lessons', AdminLessonController::class)->except(['show']);
    Route::resource('testimonials', AdminTestimonialController::class)->except(['show']);
    Route::resource('faqs', AdminFaqController::class)->except(['show']);
    Route::get('legal', [AdminLegalController::class, 'index'])->name('legal.index');
    Route::get('legal/{legal}/edit', [AdminLegalController::class, 'edit'])->name('legal.edit');
    Route::put('legal/{legal}', [AdminLegalController::class, 'update'])->name('legal.update');
    Route::get('contact', [AdminContactController::class, 'index'])->name('contact.index');
    Route::get('contact/{contact}', [AdminContactController::class, 'show'])->name('contact.show');
    Route::delete('contact/{contact}', [AdminContactController::class, 'destroy'])->name('contact.destroy');
});

require __DIR__.'/auth.php';
