<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\ChatRequestController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StudentServiceController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReportController;
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// UI pages
Route::get('/students/{user}/profile', [StudentPageController::class, 'profile'])->middleware(['auth'])->name('students.profile');
Route::get('/search', [SearchPageController::class, 'index'])->middleware(['auth'])->name('search.index');

// Mockup flows (public for preview)
Route::get('/onboarding', fn() => view('onboarding.register'))->name('onboarding.register');
Route::get('/onboarding/community', fn() => view('onboarding.community_verification'))->name('onboarding.community.verify');
Route::get('/community/home', fn() => view('home.community'))->name('community.home');
Route::get('/requests/demo', fn() => view('community.request_view'))->name('community.request.demo');
Route::get('/chat/demo/request', fn() => view('chat.request'))->name('chat.request.demo');
Route::get('/chat/demo', fn() => view('chat.index'))->name('chat.index.demo');
Route::get('/admin/verifications', [AdminPageController::class, 'verifications'])->middleware(['auth'])->name('admin.verifications.page');
Route::get('/admin/reports', [AdminPageController::class, 'reports'])->middleware(['auth'])->name('admin.reports.page');

// Authenticated JSON endpoints
Route::middleware(['auth'])->group(function () {
    Route::post('/availability/toggle', [AvailabilityController::class, 'toggle']);

    Route::post('/chat-requests', [ChatRequestController::class, 'store']);
    Route::post('/chat-requests/{chatRequest}/accept', [ChatRequestController::class, 'accept']);
    Route::post('/chat-requests/{chatRequest}/decline', [ChatRequestController::class, 'decline']);

    Route::post('/reviews', [ReviewController::class, 'store']);

    Route::post('/student-services', [StudentServiceController::class, 'store']);
    Route::put('/student-services/{service}', [StudentServiceController::class, 'update']);
    Route::delete('/student-services/{service}', [StudentServiceController::class, 'destroy']);

    Route::post('/reports', [ReportController::class, 'store']);

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
