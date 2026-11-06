<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LayoutservicesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PhotographyController;
use App\Http\Controllers\ProductionservicesController;
use App\Http\Controllers\PublishingserviceController;
use App\Http\Controllers\SocialmediaController;
use App\Http\Controllers\SoftcopyController;
use App\Http\Controllers\TrademarkRequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UzpostController;
use App\Http\Controllers\UzprController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. Public routes (no login needed)
|--------------------------------------------------------------------------
*/

// Send visitors to the right place
Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'admin.home' : 'login');
});

// Login page: only for people who are NOT logged in yet
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    // throttle = max 10 tries per minute, to slow down password guessing
    Route::post('/login', [LoginController::class, 'authenticate'])
        ->middleware('throttle:10,1')
        ->name('login.store');
});

// Keep the old URL working
Route::redirect('/admin-login', '/login');

/*
|--------------------------------------------------------------------------
| 2. Logged-in routes
|--------------------------------------------------------------------------
| Everything below needs a login. "role:..." limits a group to certain user
| types (admin and superadmin can always open everything).
*/

Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // ----- Any logged-in user -----
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.home');
    Route::get('/admin/account', [AccountController::class, 'dashboard'])->name('admin.account');
    Route::get('/admin/account/edit-profile', [AccountController::class, 'editprofile'])->name('admin.account.edit-profile');
    Route::get('/admin/account/edit-profile/password', [AccountController::class, 'editprofilepassword'])->name('admin.account.edit-password');

    // ----- Notifications (any logged-in user, scoped to their own account) -----
    // Read state is per account: these never touch a shared flag.
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/feed', [NotificationController::class, 'feed'])->name('notifications.feed');
    Route::get('/notifications/count', [NotificationController::class, 'count'])->name('notifications.count');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');
    Route::post('/notifications/{key}/read', [NotificationController::class, 'read'])
        ->where('key', '[A-Za-z0-9_\-]+:[A-Za-z0-9_\-]+')
        ->name('notifications.read');
    Route::get('/notifications/{key}/open', [NotificationController::class, 'open'])
        ->where('key', '[A-Za-z0-9_\-]+:[A-Za-z0-9_\-]+')
        ->name('notifications.open');
    Route::get('/notifications/{key}', [NotificationController::class, 'show'])
        ->where('key', '[A-Za-z0-9_\-]+:[A-Za-z0-9_\-]+')
        ->name('notifications.show');

    // ----- Content team: social media, photography, video -----
    Route::middleware('role:content_team,content_admin')->group(function () {
        // social media request
        Route::get('/socmed/dashboard', [SocialmediaController::class, 'dashboard'])->name('socmed.home');
        Route::post('/socmed/dashboard/store', [SocialmediaController::class, 'store'])->name('socmed.store');
        Route::delete('/socmed/dashboard/delete/{request_id}', [SocialmediaController::class, 'destroy'])->name('socmed.destroy');
        Route::put('/socmed/dashboard/update/{request_id}', [SocialmediaController::class, 'update'])->name('socmed.update');

        // photography
        Route::get('/photography/dashboard', [PhotographyController::class, 'dashboard'])->name('photo.home');
        Route::post('/photography/dashboard/store', [PhotographyController::class, 'store'])->name('photo.store');
        Route::put('/photography/dashboard/update/{request_id}', [PhotographyController::class, 'update'])->name('photo.update');
        Route::delete('/photography/dashboard/delete/{request_id}', [PhotographyController::class, 'destroy'])->name('photo.destroy');

        // video
        Route::get('/videography/dashboard', [VideoController::class, 'dashboard'])->name('video.home');
    });

    // ----- Creative team: layout, production, publishing, uzpost, uzpr -----
    Route::middleware('role:creative_team,creative_admin')->group(function () {
        // layout
        Route::get('/layoutservices/dashboard', [LayoutservicesController::class, 'dashboard'])->name('layout.home');
        Route::post('/layoutservices/dashboard/store', [LayoutservicesController::class, 'store'])->name('layout.store');
        Route::put('/layoutservices/dashboard/update/{request_id}', [LayoutservicesController::class, 'update'])->name('layout.update');
        Route::post('/layoutservices/dashboard/mark-sent/{request_id}', [LayoutservicesController::class, 'markAsSent']);
        Route::post('/admin/layoutservices/mark-denied/{request_id}', [LayoutservicesController::class, 'markAsDenied']);
        Route::delete('/layoutservices/dashboard/delete/{request_id}', [LayoutservicesController::class, 'destroy'])->name('layout.destroy');

        // production
        Route::get('/productionservices/dashboard', [ProductionservicesController::class, 'dashboard'])->name('production.home');
        Route::post('/productionservices/dashboard/store', [ProductionservicesController::class, 'store'])->name('production.store');
        Route::put('/productionservices/dashboard/update/{request_id}', [ProductionservicesController::class, 'update'])->name('production.update');
        Route::delete('/productionservices/dashboard/delete/{request_id}', [ProductionservicesController::class, 'destroy'])->name('production.destroy');

        // publishing
        Route::get('/publishingservices/dashboard', [PublishingserviceController::class, 'dashboard'])->name('publishing.home');

        // uzpost
        Route::get('/uzpost/dashboard', [UzpostController::class, 'dashboard'])->name('uzpost.home');
        Route::post('/uzpost/dashboard/store', [UzpostController::class, 'store'])->name('uzpost.store');
        Route::put('/uzpost/dashboard/update/{request_id}', [UzpostController::class, 'update'])->name('uzpost.update');

        // uzpr
        Route::get('/uzpr/dashboard', [UzprController::class, 'dashboard'])->name('uzpr.home');
        Route::post('/uzpr/dashboard/store', [UzprController::class, 'store'])->name('uzpr.store');
        Route::put('/uzpr/dashboard/update/{request_id}', [UzprController::class, 'update'])->name('uzpr.update');
    });

    // ----- Yearbook team: soft copy requests -----
    Route::middleware('role:yearbook_team')->group(function () {
        Route::get('/softcopyrequest/dashboard', [SoftcopyController::class, 'dashboard'])->name('softcopy.home');
        Route::post('/softcopyrequest/dashboard/store', [SoftcopyController::class, 'store'])->name('softcopy.store');
        Route::post('/admin/softcopy/mark-sent/{id}', [SoftcopyController::class, 'markAsSent']);
        Route::post('/admin/softcopy/mark-denied/{id}', [SoftcopyController::class, 'markAsDenied']);
    });

    // ----- Admin only: trademark requests and user management -----
    Route::middleware('role:admin')->group(function () {
        // trademark
        Route::get('/trademarkrequest/dashboard', [TrademarkRequestController::class, 'dashboard'])->name('trademark.home');
        Route::post('/trademarkrequest/store', [TrademarkRequestController::class, 'store'])->name('trademark.store');
        Route::post('/admin/trademarkrequest/mark-sent/{id}', [TrademarkRequestController::class, 'markAsSent']);
        Route::post('/admin/trademarkrequest/mark-denied/{id}', [TrademarkRequestController::class, 'markAsDenied']);

        // users
        Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.table');
        Route::post('/user/dashboard/store', [UserController::class, 'store'])->name('user.store');
        Route::put('/user/dashboard/update/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/dashboard/destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::put('/users/dashboard/user-type/{user}', [UserController::class, 'updateUserType'])->name('users.updateType');
    });
});
