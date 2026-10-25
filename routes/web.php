<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SocialmediaController;
use App\Http\Controllers\PhotographyController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\LayoutservicesController;
use App\Http\Controllers\ProductionservicesController;
use App\Http\Controllers\PublishingserviceController;
use App\Http\Controllers\UzpostController;
use App\Http\Controllers\UzprController;
use App\Http\Controllers\SoftcopyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrademarkRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.home');
Route::get('/admin/account', [AccountController::class, 'dashboard'])->name('admin.account');
Route::get('/admin/account/edit-profile', [AccountController::class, 'editprofile'])->name('admin.account.edit-profile');
Route::get('/admin/account/edit-profile/password', [AccountController::class, 'editprofilepassword'])->name('admin.account.edit-password');

//socialmedia request
Route::get('/socmed/dashboard', [SocialmediaController::class, 'dashboard'])->name('socmed.home');
Route::POST('/socmed/dashboard/store', [SocialmediaController::class, 'store'])->name('socmed.store');
Route::PUT('/socmed/dashboard/update/{request_id}', [SocialmediaController::class, 'update'])->name('socmed.update');
Route::post('/socmed/mark-read/{id}', [SocialmediaController::class, 'markAsRead'])->name('socmed.markRead');

//photography
Route::get('/photography/dashboard', [PhotographyController::class, 'dashboard'])->name('photo.home');
Route::POST('/photography/dashboard/store', [PhotographyController::class, 'store'])->name('photo.store');
Route::PUT('/photography/dashboard/update/{request_id}', [PhotographyController::class, 'update'])->name('photo.update');
Route::post('/photography/mark-read/{id}', [PhotographyController::class, 'markAsRead'])->name('photography.markRead');
//video
Route::get('/videography/dashboard', [VideoController::class, 'dashboard'])->name('video.home');
//layout
Route::get('/layoutservices/dashboard', [LayoutservicesController::class, 'dashboard'])->name('layout.home');
Route::POST('/layoutservices/dashboard/store', [LayoutservicesController::class, 'store'])->name('layout.store');
Route::PUT('/layoutservices/dashboard/update/{request_id}', [LayoutservicesController::class, 'update'])->name('layout.update');
//production
Route::get('/productionservices/dashboard', [ProductionservicesController::class, 'dashboard'])->name('production.home');
Route::POST('/productionservices/dashboard/store', [ProductionservicesController::class, 'store'])->name('production.store');
Route::PUT('/productionservices/dashboard/update/{request_id}', [ProductionservicesController::class, 'update'])->name('production.update');

Route::get('/publishingservices/dashboard', [PublishingserviceController::class, 'dashboard'])->name('publishing.home');
//uzpost
Route::get('/uzpost/dashboard', [UzpostController::class, 'dashboard'])->name('uzpost.home');
Route::POST('/uzpost/dashboard/store', [UzpostController::class, 'store'])->name('uzpost.store');
Route::PUT('/uzpost/dashboard/update/{request_id}', [UzpostController::class, 'update'])->name('uzpost.update');

Route::get('/uzpr/dashboard', [UzprController::class, 'dashboard'])->name('uzpr.home');
Route::POST('/uzpr/dashboard/store', [UzprController::class, 'store'])->name('uzpr.store');
Route::PUT('/uzpr/dashboard/update/{request_id}', [UzprController::class, 'update'])->name('uzpr.update');

Route::get('/softcopyrequest/dashboard', [SoftcopyController::class, 'dashboard'])->name('softcopy.home');
Route::POST('/softcopyrequest/dashboard/store', [SoftcopyController::class, 'store'])->name('softcopy.store');
Route::post('/admin/softcopy/mark-sent/{id}', [SoftcopyController::class, 'markAsSent']);
Route::post('/admin/softcopy/mark-denied/{id}', [SoftcopyController::class, 'markAsDenied']);

Route::get('/trademarkrequest/dashboard', [TrademarkRequestController::class, 'dashboard'])->name('trademark.home');
Route::post('/trademarkrequest/store', [TrademarkRequestController::class, 'store'])->name('trademark.store');
Route::post('/admin/trademarkrequest/mark-sent/{id}', [TrademarkRequestController::class, 'markAsSent']);
Route::post('/admin/trademarkrequest/mark-denied/{id}', [TrademarkRequestController::class, 'markAsDenied']);

//user
Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.table');
Route::POST('/user/dashboard/store', [UserController::class, 'store'])->name('user.store');
Route::PUT('/user/dashboard/update/{id}', [UserController::class, 'update'])->name('user.update');
Route::DELETE('/user/dashboard/destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy');