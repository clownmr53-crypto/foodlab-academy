<?php

use App\Http\Controllers\Admin\CoachingRequestController as AdminCoachingController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\ForumCategoryController as AdminForumCategoryController;
use App\Http\Controllers\Admin\LegalPageController as AdminLegalController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Admin\ModuleController as AdminModuleController;
use App\Http\Controllers\Admin\QaSessionController as AdminQaController;
use App\Http\Controllers\Admin\ResourceTemplateController as AdminTemplateController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CoachingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\LmsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QaController;
use App\Http\Controllers\TemplateLibraryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/tastebox', [PageController::class, 'tastebox'])->name('tastebox');
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
    Route::get('/payments/{payment}/fedapay/callback', [PaymentController::class, 'fedaPayCallback'])->name('payments.fedapay.callback');

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

        // Forum — lecture + publication pour abonnés
        Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
        Route::get('/forum/categories/{category}', [ForumController::class, 'category'])->name('forum.category');
        Route::get('/forum/categories/{category}/nouveau', [ForumController::class, 'createThread'])->name('forum.thread.create');
        Route::post('/forum/categories/{category}/threads', [ForumController::class, 'storeThread'])->name('forum.thread.store');
        Route::get('/forum/threads/{thread}', [ForumController::class, 'showThread'])->name('forum.thread');
        Route::post('/forum/threads/{thread}/posts', [ForumController::class, 'storePost'])->name('forum.post.store');
        Route::delete('/forum/threads/{thread}', [ForumController::class, 'destroyThread'])->name('forum.thread.destroy');
        Route::delete('/forum/posts/{post}', [ForumController::class, 'destroyPost'])->name('forum.post.destroy');

        Route::get('/qa', [QaController::class, 'index'])->name('qa.index');

        Route::get('/templates', [TemplateLibraryController::class, 'index'])->name('templates.index');
        Route::get('/templates/{template}/download', [TemplateLibraryController::class, 'download'])->name('templates.download');
    });

    // Coaching — tous les authentifiés (lien Premium mis en avant dans la nav)
    Route::get('/coaching', [CoachingController::class, 'index'])->name('coaching.index');
    Route::post('/coaching', [CoachingController::class, 'store'])->name('coaching.store');

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
    Route::resource('faqs', AdminFaqController::class)->except(['show']);
    Route::get('legal', [AdminLegalController::class, 'index'])->name('legal.index');
    Route::get('legal/{legal}/edit', [AdminLegalController::class, 'edit'])->name('legal.edit');
    Route::put('legal/{legal}', [AdminLegalController::class, 'update'])->name('legal.update');
    Route::get('contact', [AdminContactController::class, 'index'])->name('contact.index');
    Route::get('contact/{contact}', [AdminContactController::class, 'show'])->name('contact.show');
    Route::delete('contact/{contact}', [AdminContactController::class, 'destroy'])->name('contact.destroy');

    Route::get('qa', [AdminQaController::class, 'index'])->name('qa.index');
    Route::get('qa/create', [AdminQaController::class, 'create'])->name('qa.create');
    Route::post('qa', [AdminQaController::class, 'store'])->name('qa.store');
    Route::get('qa/{qa}/edit', [AdminQaController::class, 'edit'])->name('qa.edit');
    Route::put('qa/{qa}', [AdminQaController::class, 'update'])->name('qa.update');
    Route::delete('qa/{qa}', [AdminQaController::class, 'destroy'])->name('qa.destroy');

    Route::resource('templates', AdminTemplateController::class)->except(['show']);

    Route::get('coaching', [AdminCoachingController::class, 'index'])->name('coaching.index');
    Route::patch('coaching/{coaching}', [AdminCoachingController::class, 'updateStatus'])->name('coaching.status');
    Route::delete('coaching/{coaching}', [AdminCoachingController::class, 'destroy'])->name('coaching.destroy');

    Route::get('forum/categories', [AdminForumCategoryController::class, 'index'])->name('forum.categories');
    Route::post('forum/categories', [AdminForumCategoryController::class, 'store'])->name('forum.categories.store');
    Route::delete('forum/categories/{category}', [AdminForumCategoryController::class, 'destroy'])->name('forum.categories.destroy');
});

require __DIR__.'/auth.php';
