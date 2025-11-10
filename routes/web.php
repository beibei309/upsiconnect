<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatRequestController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceApplicationController;
use App\Http\Controllers\StudentServiceController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Admin\VerificationController as AdminVerificationController;
use App\Http\Controllers\Admin\ReportAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Pages\StudentPageController;
use App\Http\Controllers\Pages\SearchPageController;
use App\Http\Controllers\Pages\AdminPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Legal pages
Route::get('/terms', function () {
    return view('legal.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('legal.privacy');
})->name('privacy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// UI pages
Route::get('/students/{user}/profile', [StudentPageController::class, 'profile'])->middleware(['auth'])->name('students.profile');
Route::get('/search', [SearchPageController::class, 'index'])->middleware(['auth'])->name('search.index');
Route::get('/services/manage', [StudentServiceController::class, 'manage'])->middleware(['auth'])->name('services.manage');
Route::get('/services/create', [StudentServiceController::class, 'create'])->middleware(['auth'])->name('services.create');
Route::get('/services/apply', function () {
    return view('services.apply');
})->middleware(['auth'])->name('services.apply');
Route::post('/services/apply', [App\Http\Controllers\ServiceApplicationController::class, 'store'])->middleware(['auth'])->name('services.apply.store');
Route::get('/services/applications', [App\Http\Controllers\ServiceApplicationController::class, 'index'])->middleware(['auth'])->name('services.applications.index');
Route::get('/services/applications/{application}', [App\Http\Controllers\ServiceApplicationController::class, 'show'])->middleware(['auth'])->name('services.applications.show');
Route::get('/services/applications/{application}/edit', [App\Http\Controllers\ServiceApplicationController::class, 'edit'])->middleware(['auth'])->name('services.applications.edit');
Route::patch('/services/applications/{application}', [App\Http\Controllers\ServiceApplicationController::class, 'update'])->middleware(['auth'])->name('services.applications.update');
Route::post('/services/applications/{application}/accept', [App\Http\Controllers\ServiceApplicationController::class, 'accept'])->middleware(['auth'])->name('services.applications.accept');
Route::post('/services/applications/{application}/reject', [App\Http\Controllers\ServiceApplicationController::class, 'reject'])->middleware(['auth'])->name('services.applications.reject');
Route::post('/services/applications/{application}/mark-completed', [App\Http\Controllers\ServiceApplicationController::class, 'markCompleted'])->middleware(['auth'])->name('services.applications.mark-completed');
Route::get('/service-requests', [ServiceRequestController::class, 'index'])->middleware(['auth'])->name('service-requests.index');

// Service Request routes
Route::middleware(['auth'])->group(function () {
    Route::get('/service-requests', [ServiceRequestController::class, 'index'])->name('service-requests.index');
    Route::post('/service-requests', [ServiceRequestController::class, 'store'])->name('service-requests.store');
    Route::get('/service-requests/{serviceRequest}', [ServiceRequestController::class, 'show'])->name('service-requests.show');
    Route::post('/service-requests/{serviceRequest}/accept', [ServiceRequestController::class, 'accept'])->name('service-requests.accept');
    Route::post('/service-requests/{serviceRequest}/reject', [ServiceRequestController::class, 'reject'])->name('service-requests.reject');
    Route::post('/service-requests/{serviceRequest}/mark-in-progress', [ServiceRequestController::class, 'markInProgress'])->name('service-requests.mark-in-progress');
    Route::post('/service-requests/{serviceRequest}/mark-completed', [ServiceRequestController::class, 'markCompleted'])->name('service-requests.mark-completed');
    Route::post('/service-requests/{serviceRequest}/cancel', [ServiceRequestController::class, 'cancel'])->name('service-requests.cancel');
});

// Mockup flows (public for preview)
Route::get('/onboarding', fn() => view('onboarding.register'))->name('onboarding.register');
Route::get('/onboarding/community', fn() => view('onboarding.community_verification'))->name('onboarding.community.verify');
Route::get('/community/home', fn() => view('home.community'))->name('community.home');
Route::get('/requests/demo', fn() => view('community.request_view'))->name('community.request.demo');
Route::get('/chat/demo/request', fn() => view('chat.request'))->name('chat.request.demo');
Route::get('/chat/request', [ChatRequestController::class, 'create'])->middleware(['auth'])->name('chat.request');
Route::get('/chat/demo', fn() => view('chat.index'))->name('chat.index.demo');
Route::get('/chat', [ChatController::class, 'index'])->middleware(['auth'])->name('chat.index');
Route::get('/chat/{conversation}', [ChatController::class, 'show'])->middleware(['auth'])->name('chat.show');
Route::get('/admin/verifications', [AdminPageController::class, 'verifications'])->middleware(['auth'])->name('admin.verifications.page');
Route::get('/admin/reports', [AdminPageController::class, 'reports'])->middleware(['auth'])->name('admin.reports.page');

// Authenticated JSON endpoints
Route::middleware(['auth'])->group(function () {
    Route::post('/availability/toggle', [AvailabilityController::class, 'toggle']);

    Route::post('/chat-requests', [ChatRequestController::class, 'store'])->name('chat-requests.store');
    Route::post('/chat-requests/{chatRequest}/accept', [ChatRequestController::class, 'accept']);
    Route::post('/chat-requests/{chatRequest}/decline', [ChatRequestController::class, 'decline']);
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/typing', [MessageController::class, 'typing'])->name('messages.typing');
    Route::post('/service-applications/apply', [ServiceApplicationController::class, 'applyFromChat'])->name('service-applications.apply');
    Route::post('/service-applications/{application}/accept', [ServiceApplicationController::class, 'acceptFromChat'])->name('service-applications.accept');
    Route::post('/service-applications/{application}/decline', [ServiceApplicationController::class, 'declineFromChat'])->name('service-applications.decline');
    Route::post('/service-applications/{application}/complete', [ServiceApplicationController::class, 'markCompleted'])->name('service-applications.complete');

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    Route::post('/student-services', [StudentServiceController::class, 'store']);
    Route::put('/student-services/{service}', [StudentServiceController::class, 'update']);
    Route::delete('/student-services/{service}', [StudentServiceController::class, 'destroy']);
    Route::get('/student-services/{service}', [StudentServiceController::class, 'show'])->name('student-services.show');

    Route::post('/reports', [ReportController::class, 'store']);

    // Favorites routes
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{user}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorites/{user}/check', [FavoriteController::class, 'check'])->name('favorites.check');

    // Admin moderation endpoints
    Route::post('/admin/verifications/{user}/approve', [AdminVerificationController::class, 'approve']);
    Route::post('/admin/verifications/{user}/reject', [AdminVerificationController::class, 'reject']);
    Route::get('/admin/reports/index', [ReportAdminController::class, 'index']);
    Route::post('/admin/reports/{report}/resolve', [ReportAdminController::class, 'resolve']);

    Route::post('/admin/users/{user}/ban', [UserAdminController::class, 'ban']);
    Route::post('/admin/users/{user}/unban', [UserAdminController::class, 'unban']);
    Route::post('/admin/users/{user}/suspend', [UserAdminController::class, 'suspend']);
    Route::post('/admin/users/{user}/unsuspend', [UserAdminController::class, 'unsuspend']);
});

// Public JSON endpoints
Route::get('/students/{user}', [StudentServiceController::class, 'storefront']);
Route::get('/search/services', [SearchController::class, 'services']);
require __DIR__.'/auth.php';
