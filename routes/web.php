<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatRequestController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\ServiceApplicationController;
use App\Http\Controllers\StudentServiceController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Admin\VerificationController as AdminVerificationController;
use App\Http\Controllers\Admin\ReportAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Pages\SearchPageController;
use App\Http\Controllers\Pages\AdminPageController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\Admin\AdminServicesController;
use App\Http\Controllers\Admin\AdminCommunityController;
use App\Http\Controllers\Admin\SuperAdminController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/help', function () {return view('help');})->name('help');


// Display the form to join as a part-timer
Route::get('/students/create', [ProfileController::class, 'create'])->name('students.create');
// Handle the profile form submission
Route::get('/students', [StudentsController::class, 'index'])->name('students.index');
Route::post('/students/create', [StudentsController::class, 'store'])->name('students.store');
Route::get('/students/edit-profile', [StudentsController::class, 'edit'])->name('students.edit');
Route::patch('/students/edit-profile', [StudentsController::class, 'update'])->name('students.update');


// ... kod route lain ...

Route::middleware(['auth'])->group(function () {
    
    // Route untuk paparkan page verification
    Route::get('/onboarding/students', [VerificationController::class, 'index'])
        ->name('onboarding.students');

    // Route untuk Upload Profile Photo (INI YANG MISSING DALAM ERROR ANDA)
    Route::post('/verification/upload-photo', [VerificationController::class, 'uploadPhoto'])
        ->name('students_verification.upload');

    // Route untuk Upload Live Selfie (Untuk fungsi kamera nanti)
    Route::post('/verification/upload-selfie', [VerificationController::class, 'uploadSelfie'])
        ->name('students_verification.upload_selfie');

});
// services
Route::get('/services', [StudentServiceController::class, 'index'])->name('services.index');
Route::get('/services/manage', [StudentServiceController::class, 'manage'])->middleware(['auth'])->name('services.manage');
Route::get('/services/create', [StudentServiceController::class, 'create'])->middleware(['auth'])->name('services.create');
Route::post('/services/create', [StudentServiceController::class, 'store'])->middleware(['auth'])->name('services.store');
Route::post('/student-services', [StudentServiceController::class, 'store']);
// Route::put('/student-services/{service}', [StudentServiceController::class, 'update']);
Route::delete('/services/manage/{service}', [StudentServiceController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('services.destroy');
Route::put('/services/manage/{service}', [StudentServiceController::class, 'update'])
    ->middleware(['auth'])
    ->name('services.update');
Route::get('/services/{service}/edit', [StudentServiceController::class, 'edit'])
    ->middleware(['auth'])
    ->name('services.edit');



Route::get('/student-services/{service}', [StudentServiceController::class, 'show'])->name('student-services.show');

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
Route::post('/service-request', [ServiceRequestController::class, 'store'])->name('service-request.store');

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
Route::get('/services/{id}', [StudentServiceController::class, 'details'])->name('services.details');



Route::post('/service-requests', [ServiceRequestController::class, 'store'])
    ->name('service-requests.store')
    ->middleware('auth'); // pastikan user logged in




// When a guest clicks “Request Service”
Route::get('/guest/request/{id}', function () {
    return redirect()->route('login');
})->name('guest.request');

// after login
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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
Route::get('/students/{user}/profile', [StudentsController::class, 'profile'])->name('students.profile');
Route::get('/search', [SearchPageController::class, 'index'])->middleware(['auth'])->name('search.index');



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
    Route::post('/availability/toggle', [AvailabilityController::class, 'toggle'])->name('availability.toggle');
Route::post('/availability/update-settings', [AvailabilityController::class, 'updateSettings'])->name('availability.updateSettings');

    Route::post('/chat-requests', [ChatRequestController::class, 'store'])->name('chat-requests.store');
    Route::post('/chat-requests/{chatRequest}/accept', [ChatRequestController::class, 'accept']);
    Route::post('/chat-requests/{chatRequest}/decline', [ChatRequestController::class, 'decline']);
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/typing', [MessageController::class, 'typing'])->name('messages.typing');
    Route::post('/service-applications/apply', [ServiceApplicationController::class, 'applyFromChat'])->name('service-applications.apply');
    Route::post('/service-applications/{application}/accept', [ServiceApplicationController::class, 'acceptFromChat'])->name('service-applications.accept');
    Route::post('/service-applications/{application}/decline', [ServiceApplicationController::class, 'declineFromChat'])->name('service-applications.decline');
    Route::post('/service-applications/{application}/complete', [ServiceApplicationController::class, 'markCompleted'])->name('service-applications.complete');

    // Interest system endpoints for open applications
    Route::post('/services/applications/{application}/interest', [ServiceApplicationController::class, 'expressInterest'])->name('services.applications.interest');
// Withdraw interest removed by product decision
// Route::delete('/services/applications/{application}/interest', [ServiceApplicationController::class, 'withdrawInterest'])->name('services.applications.interest.withdraw');
Route::post('/services/applications/{application}/interests/confirm', [ServiceApplicationController::class, 'confirmSelected'])->name('services.applications.interests.confirm');
    Route::post('/services/applications/{application}/interests/{interest}/select', [ServiceApplicationController::class, 'selectInterest'])->name('services.applications.interests.select');
    Route::post('/services/applications/{application}/interests/{interest}/decline', [ServiceApplicationController::class, 'declineInterest'])->name('services.applications.interests.decline');

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

 

    Route::post('/reports', [ReportController::class, 'store']);

    // Favorites routes
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{user}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorites/{user}/check', [FavoriteController::class, 'check'])->name('favorites.check');
    // Service
    Route::post('/favourites/service/toggle', [FavoriteController::class, 'toggleService'])
    ->name('favorites.service.toggle')
    ->middleware('auth');
    Route::get('/favourites/service/check/{id}', [FavoriteController::class, 'checkService'])
    ->name('favorites.service.check')
    ->middleware('auth');


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


// Admin service management routes
    Route::get('admin/services', [AdminServicesController::class, 'index'])->name('admin.services.index');
    Route::patch('admin/services/{service}/approve', [AdminServicesController::class, 'approve'])->name('admin.services.approve');
    Route::patch('admin/services/{service}/reject', [AdminServicesController::class, 'reject'])->name('admin.services.reject');



// Public JSON endpoints
Route::get('/students/{user}', [StudentServiceController::class, 'storefront']);
Route::get('/search/services', [SearchController::class, 'services']);
require __DIR__.'/auth.php';

Route::get('/help', function () {
    return view('help');
})->name('help');


/// Admin Login (public)
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->name('admin.login.submit');
    
    // Protected Admin Routes
    Route::middleware(['auth:admin', 'prevent-back-history'])->prefix('admin')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    // Student Management
     // Student List
    Route::get('/students', [AdminStudentController::class, 'index'])->name('admin.students.index');

    //view student(admin)
    Route::get('/students/view/{id}', [AdminStudentController::class, 'view'])->name('admin.students.view');

    // Edit Student
    Route::get('/students/{id}/edit', [AdminStudentController::class, 'edit'])->name('admin.students.edit');
    Route::put('/students/{id}/update', [AdminStudentController::class, 'update'])->name('admin.students.update');

    // NEW: Delete Student
    Route::delete('/students/{id}', [AdminStudentController::class, 'destroy'])->name('admin.students.delete');

    // NEW: Ban Student
    Route::post('/students/{id}/ban', [AdminStudentController::class, 'ban'])->name('admin.students.ban');

    // NEW: Unban Student
    Route::post('/students/{id}/unban', [AdminStudentController::class, 'unban'])->name('admin.students.unban');

    // Manage Admin Accounts (superadmin)
    Route::get('/superadmin/admins/create', [SuperAdminController::class, 'create'])
        ->name('admin.super.admins.create');

    Route::get('/admins', [SuperAdminController::class, 'adminsIndex'])
    ->name('admin.super.admins.index');
    
    Route::get('/admins/create', [SuperAdminController::class, 'create'])
    ->name('admin.super.admins.create');

Route::post('/admins/store', [SuperAdminController::class, 'store'])
    ->name('admin.super.admins.store');

Route::get('/admins/{id}/edit', [SuperAdminController::class, 'edit'])
    ->name('admin.super.admins.edit');

Route::post('/admins/{id}/update', [SuperAdminController::class, 'update'])
    ->name('admin.super.admins.update');

Route::delete('/admins/{id}', [SuperAdminController::class, 'destroy'])
    ->name('admin.super.admins.delete');

}); //end admin manage admin part

//admin-community part
Route::get('/community', [AdminCommunityController::class, 'index'])->name('admin.community.index');

Route::prefix('community')->group(function () {

    Route::get('/', [AdminCommunityController::class, 'index'])->name('admin.community.index');
    Route::get('/view/{id}', [AdminCommunityController::class, 'view'])->name('admin.community.view');
    Route::get('/edit/{id}', [AdminCommunityController::class, 'edit'])->name('admin.community.edit');
    Route::put('/update/{id}', [AdminCommunityController::class, 'update'])->name('admin.community.update');

    // Blacklist routes
    Route::post('/admin/community/blacklist/{id}', [AdminCommunityController::class, 'blacklist'])->name('admin.community.blacklist');
    Route::post('/admin/community/unblacklist/{id}', [AdminCommunityController::class, 'unblacklist'])->name('admin.community.unblacklist');

    // Delete
    Route::delete('/delete/{id}', [AdminCommunityController::class, 'delete'])->name('admin.community.delete');
});
//end admin community aprt

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->name('admin.logout');   
