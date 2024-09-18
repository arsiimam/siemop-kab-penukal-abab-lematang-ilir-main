<?php

use App\Http\Controllers\ActivityProgramController;
use App\Http\Controllers\ActivityReportController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DescriptionOfActivitiesController;
use App\Http\Controllers\DevelopmentController;
use App\Http\Controllers\DropzoneController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstituteController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('landing_page.index');
});
// Route::get('/', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    /** profile & user */
    Route::get('/profile', [UserController::class, 'profile'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('users', UserController::class);

    /** role */
    Route::resource('roles', RoleController::class);

    /** permission */
    Route::resource('permissions', PermissionController::class);

    /** institute */
    Route::resource('institute', InstituteController::class);

    /** activity program */
    Route::resource('activity-program', ActivityProgramController::class);

    /** activity report */
    Route::resource('activity-report', ActivityReportController::class);

    /** report */
    Route::controller(ReportController::class)->group(function () {
        Route::get('report-realisasi-fisik-keuangan', 'index')->name('report.index');
        Route::get('report-pembangunan-fisik-non_fisik', 'index2')->name('report.pembangunan.index');
    });

    /** development */
    Route::controller((DevelopmentController::class))->group(function () {
        Route::get('development', 'index')->name('development.index');
        Route::get('development/create', 'create')->name('development.create');
        Route::get('development/{development}/edit', 'edit')->name('development.edit');
    });

    /** statistic */
    Route::get('statistics', [StatisticsController::class, 'index'])->name('statistics.index');

    /** announcement */
    Route::resource('announcement', AnnouncementController::class);

    /** description of activity */
    Route::controller(DescriptionOfActivitiesController::class)->group(function () {
        Route::get('description-of-activities/index/{id}', 'index')->name('description-of-activities.index');
        Route::get('description-of-activities/create/{id}', 'create')->name('description-of-activities.create');
        Route::get('description-of-activities/edit/{id}', 'edit')->name('description-of-activities.edit');
        Route::get('description-of-activities/create/details/{id}', 'create_details')->name('description-of-activities.create.details');
        Route::get('description-of-activities/edit/details/{id}', 'edit_details')->name('description-of-activities.edit.details');
    });

    /** dropdown */
    Route::post('postimage', [DropzoneController::class, 'singlePost'])->name('postimage');

    /** settings */
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings/store', [SettingController::class, 'store'])->name('settings.store');
    Route::post('settings/store_banner', [SettingController::class, 'updatePict'])->name('settings.store_banner');
    Route::get('settings/filerecent/{id}', [SettingController::class, 'fileRecent'])->name('filerecent');
    Route::get('settings/removeImg/{id}', [SettingController::class, 'removeImg'])->name('removeImg');
});

require __DIR__ . '/auth.php';
