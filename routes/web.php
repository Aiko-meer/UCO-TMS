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
use App\Http\Controllers\ContentuserController;
use App\Http\Controllers\CreativesuserController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.home');
Route::get('/admin/account', [AccountController::class, 'dashboard'])->name('admin.account');
Route::get('/admin/account/edit-profile', [AccountController::class, 'editprofile'])->name('admin.account.edit-profile');
Route::get('/admin/account/edit-profile/password', [AccountController::class, 'editprofilepassword'])->name('admin.account.edit-password');

Route::get('/socmed/dashboard', [SocialmediaController::class, 'dashboard'])->name('socmed.home');

Route::get('/photography/dashboard', [PhotographyController::class, 'dashboard'])->name('photo.home');

Route::get('/videography/dashboard', [VideoController::class, 'dashboard'])->name('video.home');

Route::get('/layoutservices/dashboard', [LayoutservicesController::class, 'dashboard'])->name('layout.home');

Route::get('/productionservices/dashboard', [ProductionservicesController::class, 'dashboard'])->name('production.home');

Route::get('/publishingservices/dashboard', [PublishingserviceController::class, 'dashboard'])->name('publishing.home');

Route::get('/uzpost/dashboard', [UzpostController::class, 'dashboard'])->name('uzpost.home');

Route::get('/uzpr/dashboard', [UzprController::class, 'dashboard'])->name('uzpr.home');

Route::get('/contentuser/table', [ContentuserController::class, 'usertable'])->name('contentuser.table');
Route::get('/contentuser/socmed', [ContentuserController::class, 'socmed'])->name('contentuser.socmed');
Route::get('/contentuser/socmed/table', [ContentuserController::class, 'socmedtable'])->name('contentuser.socmedtable');

Route::get('/creativesuser/dashboard', [CreativesuserController::class, 'dashboard'])->name('creativeuser.table');

Route::get('/users/dashboard', [UserController::class, 'dashboard'])->name('user.table');