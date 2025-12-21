<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChatRequestController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\StudentServiceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Admin\AdminFeedbackController;
use App\Http\Controllers\Pages\SearchPageController;
use App\Http\Controllers\Admin\ReportAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Pages\AdminPageController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Pages\StudentPageController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\ServiceApplicationController;
use App\Http\Controllers\Admin\AdminRequestController;
use App\Http\Controllers\Admin\AdminServicesController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminFaqsController;
use App\Http\Controllers\Admin\AdminCommunityController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminStudentStatusController;
use App\Http\Controllers\NotificationController;

use App\Http\Controllers\Admin\VerificationController as AdminVerificationController;

// -- PUBLIC ROUTES --
Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/help', [HelpController::class, 'index'])->name('help');

// Display the form to join as a part-timer
Route::get('/students/create', [ProfileController::class, 'create'])->name('students.create');
// Handle the profile form submission
Route::get('/students', [StudentsController::class, 'index'])->name('students.index');
Route::post('/students/create', [StudentsController::class, 'store'])->name('students.store');
Route::get('/students/edit-profile', [StudentsController::class, 'edit'])->name('students.edit');
Route::patch('/students/edit-profile', [StudentsController::class, 'update'])->name('students.update');


// -- AUTHENTICATED ROUTES --
Route::middleware(['auth'])->group(function () {
    
    // Route untuk paparkan page verification
    Route::get('/onboarding/students', [VerificationController::class, 'index'])
        ->name('onboarding.students');

        

    // Route untuk Upload Profile Photo
    Route::post('/verification/upload-photo', [VerificationController::class, 'uploadPhoto'])
        ->name('students_verification.upload');

    // Route untuk Upload Live Selfie
    Route::post('/verification/upload-selfie', [VerificationController::class, 'uploadSelfie'])
        ->name('students_verification.upload_selfie');

    // Route untuk Save Location Data
    Route::post('/verification/save-location', [VerificationController::class, 'saveLocation'])
        ->name('verification.save_location');
        
    Route::post('/onboarding/community/verify-location', [VerificationController::class, 'verifyLocation'])
        ->name('onboarding.community.verify_location');

});

// -- SERVICES ROUTES --
Route::get('/services', [StudentServiceController::class, 'index'])->name('services.index');
Route::get('/services/manage', [StudentServiceController::class, 'manage'])->middleware(['auth'])->name('services.manage');
Route::get('/services/create', [StudentServiceController::class, 'create'])->middleware(['auth'])->name('services.create');
Route::post('/services/create', [StudentServiceController::class, 'store'])->middleware(['auth'])->name('services.store');
Route::post('/student-services', [StudentServiceController::class, 'store']);

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

    // routes/web.php

Route::post('/switch-mode', [App\Http\Controllers\DashboardController::class, 'switchMode'])
    ->name('switch.mode')
    ->middleware('auth');
    

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
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// UI pages
Route::get('/students/{user}/profile', [StudentsController::class, 'profile'])->name('students.profile');
Route::get('/search', [SearchPageController::class, 'index'])->middleware(['auth'])->name('search.index');

// Mockup flows (public for preview)
Route::get('/onboarding', fn() => view('onboarding.register'))->name('onboarding.register');
Route::get('/onboarding/community', function() {
    // Redirect approved users to dashboard
    if (auth()->check() && auth()->user()->verification_status === 'approved') {
        return redirect()->route('dashboard')->with('info', 'Your account is already verified!');
    }
    return view('onboarding.community_verification');
})->middleware('auth')->name('onboarding.community.verify');
Route::post('/onboarding/community/upload-photo', [VerificationController::class, 'uploadPhoto'])->name('onboarding.community.upload_photo');
Route::post('/onboarding/community/upload-selfie', [VerificationController::class, 'uploadCommunitySelfie'])->name('onboarding.community.upload_selfie');
Route::post('/onboarding/community/submit-doc', [VerificationController::class, 'submitDoc'])->name('onboarding.community.submit_doc');
Route::get('/community/home', fn() => view('home.community'))->name('community.home');
Route::get('/requests/demo', fn() => view('community.request_view'))->name('community.request.demo');
Route::get('/chat/demo/request', fn() => view('chat.request'))->name('chat.request.demo');
Route::get('/chat/request', [ChatRequestController::class, 'create'])->middleware(['auth'])->name('chat.request');
Route::get('/chat/demo', fn() => view('chat.index'))->name('chat.index.demo');
Route::get('/chat', [ChatController::class, 'index'])->middleware(['auth'])->name('chat.index');
Route::get('/chat/{conversation}', [ChatController::class, 'show'])->middleware(['auth'])->name('chat.show');
// Routes moved to auth:admin group

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

    // Interest system endpoints
    Route::post('/services/applications/{application}/interest', [ServiceApplicationController::class, 'expressInterest'])->name('services.applications.interest');
    Route::post('/services/applications/{application}/interests/confirm', [ServiceApplicationController::class, 'confirmSelected'])->name('services.applications.interests.confirm');
    Route::post('/services/applications/{application}/interests/{interest}/select', [ServiceApplicationController::class, 'selectInterest'])->name('services.applications.interests.select');
    Route::post('/services/applications/{application}/interests/{interest}/decline', [ServiceApplicationController::class, 'declineInterest'])->name('services.applications.interests.decline');

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{review}/reply', [App\Http\Controllers\ReviewController::class, 'reply'])->name('reviews.reply');
    Route::post('/reports', [ReportController::class, 'store']);

    // Favorites routes
    // Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    // Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    // Route::delete('/favorites/{user}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    // Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    // Route::get('/favorites/{user}/check', [FavoriteController::class, 'check'])->name('favorites.check');
    // // Service
    // Route::post('/favourites/service/toggle', [FavoriteController::class, 'toggleService'])
    // ->name('favorites.service.toggle')
    // ->middleware('auth');
    // Route::get('/favourites/service/check/{id}', [FavoriteController::class, 'checkService'])
    // ->name('favorites.service.check')
    // ->middleware('auth');

    Route::middleware('auth')->group(function () {
Route::post('/favorites/services/toggle', [FavoriteController::class, 'toggleService'])
    ->name('favorites.services.toggle');


    Route::get('/favorites', [FavoriteController::class, 'index'])
        ->name('favorites.index');
});
});


  // Manage Service Requests (Skrin Monitor User Request)
    Route::get('/requests', [AdminRequestController::class, 'index'])->name('admin.requests.index');
    Route::delete('/requests/{serviceRequest}', [AdminRequestController::class, 'destroy'])->    
         name('admin.requests.destroy');

    // Route for Reports (Feedback & Complaints)
    Route::get('/feedback', [AdminFeedbackController::class, 'index'])->name('admin.feedback.index');
    Route::post('/feedback/{user}/warning', [AdminFeedbackController::class, 'sendWarning'])->name('admin.feedback.warning');
    Route::post('/feedback/{user}/block', [AdminFeedbackController::class, 'blockUser'])->name('admin.feedback.block'); 
      
Route::post('admin/services/{id}/warning', [AdminServicesController::class, 'storeWarning'])->name('admin.services.warn');

// Admin service management routes
    Route::get('admin/services', [AdminServicesController::class, 'index'])->name('admin.services.index');
    Route::patch('admin/services/{service}/approve', [AdminServicesController::class, 'approve'])->name('admin.services.approve');
    Route::patch('admin/services/{service}/reject', [AdminServicesController::class, 'reject'])->name('admin.services.reject');
    Route::patch('/services/{service}/suspend',
    [AdminServicesController::class, 'suspend']
)->name('admin.services.suspend');

Route::patch('/services/{service}/unblock', 
    [AdminServicesController::class, 'unblock']
)->name('admin.services.unblock');

// Admin reviews
Route::get('/admin/services/{service}/reviews', [
    App\Http\Controllers\Admin\AdminServicesController::class,
    'reviews'
])->name('admin.services.reviews');

Route::get('/admin/services/{service}', [
    App\Http\Controllers\Admin\AdminServicesController::class,
    'show'
])->name('admin.services.show');


// Help
Route::get('/faqs', [AdminFaqsController::class, 'index'])->name('admin.faqs.index');
Route::get('/faqs/create', [AdminFaqsController::class, 'create'])->name('admin.faqs.create');
Route::post('/faqs', [AdminFaqsController::class, 'store'])->name('admin.faqs.store');
Route::patch('/faqs/{faq}/toggle', [AdminFaqsController::class, 'toggle'])->name('admin.faqs.toggle');
Route::get('/faqs/{faq}/edit', [AdminFaqsController::class, 'edit'])->name('admin.faqs.edit');
Route::put('/faqs/{faq}', [AdminFaqsController::class, 'update'])->name('admin.faqs.update');
Route::delete('/faqs/{faq}', [AdminFaqsController::class, 'destroy'])->name('admin.faqs.destroy');

// Admin Category
 Route::get('/categories', [AdminCategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('admin.categories.destroy');

// Public JSON endpoints
Route::get('/students/{user}', [StudentServiceController::class, 'storefront']);
Route::get('/search/services', [SearchController::class, 'services']);

require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| ADMIN AUTH (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->name('admin.login.submit');


/*
|--------------------------------------------------------------------------
| PROTECTED ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:admin', 'prevent-back-history'])
    ->prefix('admin')
    ->group(function () {

    /*
    |--------------------------------------------------------------------------
    | ADMIN DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');


    /*
    |--------------------------------------------------------------------------
    | STUDENT MANAGEMENT
    |--------------------------------------------------------------------------
    */
    Route::prefix('students')->name('admin.students.')->group(function () {

        Route::get('/', [AdminStudentController::class, 'index'])->name('index');
        Route::get('/view/{id}', [AdminStudentController::class, 'view'])->name('view');
        Route::get('/{id}/edit', [AdminStudentController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [AdminStudentController::class, 'update'])->name('update');

        Route::delete('/{id}', [AdminStudentController::class, 'destroy'])->name('delete');

        Route::post('/{id}/ban', [AdminStudentController::class, 'ban'])->name('ban');
        Route::post('/{id}/unban', [AdminStudentController::class, 'unban'])->name('unban');

        Route::get('/export', [AdminStudentController::class, 'export'])->name('export');

        Route::get('/{id}/selfie', [AdminStudentController::class, 'showSelfie'])->name('selfie');
        Route::post('/{id}/revoke-helper', [AdminStudentController::class, 'revokeHelper'])
            ->name('revoke_helper');
    });


    /*
    |--------------------------------------------------------------------------
    | COMMUNITY USER MANAGEMENT
    |--------------------------------------------------------------------------
    */
    Route::prefix('community')->name('admin.community.')->group(function () {

        Route::get('/', [AdminCommunityController::class, 'index'])->name('index');
        Route::get('/view/{id}', [AdminCommunityController::class, 'view'])->name('view');
        Route::get('/edit/{id}', [AdminCommunityController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [AdminCommunityController::class, 'update'])->name('update');

        Route::post('/blacklist/{id}', [AdminCommunityController::class, 'blacklist'])->name('blacklist');
        Route::post('/unblacklist/{id}', [AdminCommunityController::class, 'unblacklist'])->name('unblacklist');

        Route::delete('/delete/{id}', [AdminCommunityController::class, 'delete'])->name('delete');
        Route::get('/export', [AdminCommunityController::class, 'export'])->name('export');
    });


    /*
    |--------------------------------------------------------------------------
    | USER MODERATION (GLOBAL)
    |--------------------------------------------------------------------------
    */
    Route::prefix('users')->name('admin.users.')->group(function () {

        Route::post('/{user}/ban', [UserAdminController::class, 'ban'])->name('ban');
        Route::post('/{user}/unban', [UserAdminController::class, 'unban'])->name('unban');
        Route::post('/{user}/suspend', [UserAdminController::class, 'suspend'])->name('suspend');
        Route::post('/{user}/unsuspend', [UserAdminController::class, 'unsuspend'])->name('unsuspend');
    });


    /*
    |--------------------------------------------------------------------------
    | VERIFICATION MANAGEMENT
    |--------------------------------------------------------------------------
    */
    Route::prefix('verifications')->name('admin.verifications.')->group(function () {

        Route::get('/', [AdminVerificationController::class, 'index'])->name('index');
        Route::post('/{user}/approve', [AdminVerificationController::class, 'approve'])->name('approve');
        Route::post('/{user}/reject', [AdminVerificationController::class, 'reject'])->name('reject');

        Route::get('/{user}/document', [AdminVerificationController::class, 'showDocument'])
            ->name('document');

        Route::get('/{user}/selfie', [AdminVerificationController::class, 'showSelfie'])
            ->name('selfie');
    });


    /*
    |--------------------------------------------------------------------------
    | REPORTS & FEEDBACK
    |--------------------------------------------------------------------------
    */
    Route::get('/reports', [ReportAdminController::class, 'index'])
        ->name('admin.reports.index');

    Route::post('/reports/{report}/resolve', [ReportAdminController::class, 'resolve'])
        ->name('admin.reports.resolve');

    Route::get('/feedback', [AdminFeedbackController::class, 'index'])
        ->name('admin.feedback.index');

    Route::post('/feedback/{user}/warning', [AdminFeedbackController::class, 'sendWarning'])
        ->name('admin.feedback.warning');

    Route::post('/feedback/{user}/block', [AdminFeedbackController::class, 'blockUser'])
        ->name('admin.feedback.block');


    /*
    |--------------------------------------------------------------------------
    | STUDENT STATUS MANAGEMENT
    |--------------------------------------------------------------------------
    */
    Route::prefix('student-status')->name('admin.student_status.')->group(function () {

        Route::get('/', [AdminStudentStatusController::class, 'index'])->name('index');
        Route::get('/create', [AdminStudentStatusController::class, 'create'])->name('create');
        Route::post('/store', [AdminStudentStatusController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [AdminStudentStatusController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [AdminStudentStatusController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [AdminStudentStatusController::class, 'destroy'])->name('delete');
    });


    /*
    |--------------------------------------------------------------------------
    | SUPERADMIN – ADMIN ACCOUNT MANAGEMENT
    |--------------------------------------------------------------------------
    */
    Route::prefix('admins')->name('admin.super.admins.')->group(function () {

        Route::get('/', [SuperAdminController::class, 'adminsIndex'])->name('index');
        Route::get('/create', [SuperAdminController::class, 'create'])->name('create');
        Route::post('/store', [SuperAdminController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SuperAdminController::class, 'edit'])->name('edit');
        Route::post('/{id}/update', [SuperAdminController::class, 'update'])->name('update');
        Route::delete('/{id}', [SuperAdminController::class, 'destroy'])->name('delete');
    });


    /*
    |--------------------------------------------------------------------------
    | ADMIN LOGOUT
    |--------------------------------------------------------------------------
    */
    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->name('admin.logout');
});