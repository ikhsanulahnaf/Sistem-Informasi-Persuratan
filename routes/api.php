<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\SuratController;
use App\Http\Controllers\API\ApprovalController;
use App\Http\Controllers\API\DisposisiController;
use App\Http\Controllers\API\ArsipController;
use App\Http\Controllers\API\StatusTrackingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

// Public route: login
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require valid Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // User profile & management
    Route::get('/user', fn(Request $request) => $request->user()); // get current user
    Route::apiResource('users', UserController::class)->except(['create', 'edit']); // CRUD user (admin only)

    // Surat management
    Route::apiResource('surats', SuratController::class)->except(['create', 'edit']);
    Route::get('/surats/{id}/status-history', [StatusTrackingController::class, 'showForSurat']);

    // Approval surat keluar (by Wakil Rektor / Rektor)
    Route::post('/approvals/{suratId}/approve', [ApprovalController::class, 'approve']);
    Route::post('/approvals/{suratId}/reject', [ApprovalController::class, 'reject']);
    Route::get('/approvals/pending', [ApprovalController::class, 'pending']); // untuk WR/Rektor

    // Disposisi surat masuk (by Rektor)
    Route::post('/disposisis/{suratId}', [DisposisiController::class, 'store']);
    Route::put('/disposisis/{id}', [DisposisiController::class, 'update']);

    // Arsip (view & create after approval)
    Route::apiResource('arsips', ArsipController::class)->only(['index', 'store', 'show']);
});