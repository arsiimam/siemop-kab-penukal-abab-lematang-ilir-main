<?php

use App\Http\Controllers\Api\ActivityProgramController;
use App\Http\Controllers\Api\ActivityReportController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\ChildActivityReportController;
use App\Http\Controllers\Api\DevelopmentController;
use App\Http\Controllers\Api\InstituteController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ReportPembangunanController;
use App\Http\Controllers\Api\StatisticsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {

    /** institute */
    Route::apiResource('institute', InstituteController::class);
    Route::post('intitute/bulk-delete', [InstituteController::class, 'bulk_delete']);

    /** activity-program */
    Route::apiResource('activity-program', ActivityProgramController::class);
    Route::post('activity-program/bulk-delete', [ActivityProgramController::class, 'bulk_delete']);

    /** activity-report */
    Route::apiResource('activity-report', ActivityReportController::class);
    Route::put('activity-report/update-status/{activity_report}', [ActivityReportController::class, 'update_status']);
    Route::post('activity-report/bulk-delete', [ActivityReportController::class, 'bulk_delete']);

    /** child-activity-report */
    Route::apiResource('child-activity-report', ChildActivityReportController::class);
    Route::post('child-activity-report/list_json', [ChildActivityReportController::class, 'list_json']);
    Route::post('child-activity-report/bulk-delete', [ChildActivityReportController::class, 'bulk_delete']);

    /** development */
    Route::apiResource('development', DevelopmentController::class);
    Route::post('development/programs_json', [DevelopmentController::class, 'programs_json']);
    Route::post('development/activity_json', [DevelopmentController::class, 'activity_json']);
    Route::post('development/list_json', [DevelopmentController::class, 'list_json']);
    Route::post('development/bulk-delete', [DevelopmentController::class, 'bulk_delete']);

    /** report non fisik */
    Route::post('report/realisasi/index_json', [ReportController::class, 'index_json']);
    Route::post('report/realisasi/download', [ReportController::class, 'generate_pdf']);

    /** report fisik */
    Route::post('report/pembangunan/index_json', [ReportPembangunanController::class, 'index_json']);
    Route::post('report/pembangunan/download', [ReportPembangunanController::class, 'generate_pdf']);

    /** Statitstic */
    Route::post('statistics/index_json', [StatisticsController::class, 'index_json']);

    /** announcement */
    Route::apiResource('announcement', AnnouncementController::class);
    Route::post('announcement/bulk-delete', [AnnouncementController::class, 'bulk_delete']);
});
