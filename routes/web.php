<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StatusSuratController;

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

// Halaman publik
Route::get('/', function () {
    return view('auth.login');
})->name('welcome');

// Auth routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

// Protected routes (harus login)
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Surat Management
    // Route::resource('surat', SuratController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
    Route::get('/surat', [SuratController::class, 'index'])->name('surat.index');
    Route::get('/surat/create', [SuratController::class, 'create'])->name('surat.create');
    Route::post('/surat', [SuratController::class, 'store'])->name('surat.store');
    Route::get('/surat/{surat}/edit', [SuratController::class, 'edit'])->name('surat.edit');
    Route::get('/surat/{surat}', [SuratController::class, 'show'])->name('surat.show');
    Route::put('/surat/{surat}', [SuratController::class, 'update'])->name('surat.update');
    Route::delete('/surat/{surat}', [SuratController::class, 'destroy'])->name('surat.destroy');
    Route::get('/surat/{surat}/download', [SuratController::class, 'downloadFile'])->name('surat.download');
    Route::get('/surat/{surat}/preview', [SuratController::class, 'preview'])->name('surat.preview');

    // Status Surat (Khusus Departemen)
    Route::get('/status-surat', [StatusSuratController::class, 'index'])->name('status-surat.index');
    Route::get('/status-surat/{surat}', [StatusSuratController::class, 'show'])->name('status-surat.show');

    // Arsip Surat (untuk Admin, Rektor, WR)
    Route::get('/arsip', [SuratController::class, 'arsip'])->name('surat.arsip');

    // Approval Surat (Workflow: WR Approval → Rektor TTD → Numbering → Archive → Return)
    Route::prefix('approval')->name('approval.')->group(function () {
        // Untuk Wakil Rektor (WR)
        Route::get('/pending', [ApprovalController::class, 'pending'])->name('pending');
        Route::post('/{surat}/approve', [ApprovalController::class, 'approve'])->name('approve');
        Route::post('/{surat}/reject', [ApprovalController::class, 'reject'])->name('reject');

        // Untuk Rektor
        Route::get('/waiting-signature', [ApprovalController::class, 'waitingSignature'])->name('waitingSignature');
        Route::post('/{surat}/sign', [ApprovalController::class, 'sign'])->name('sign');
        Route::post('/{surat}/archive', [ApprovalController::class, 'archive'])->name('archive');
        Route::post('/{surat}/return', [ApprovalController::class, 'returnToDepartment'])->name('returnToDepartment');
        Route::get('/{surat}/preview', [ApprovalController::class, 'preview'])->name('preview');
        // Double Approval Rektor (Preview, Approve, Reject sebelum TTD)
        Route::get('/{surat}/preview-rektor', [ApprovalController::class, 'previewForRektor'])->name('previewForRektor');
        Route::post('/{surat}/approve-rektor', [ApprovalController::class, 'approveRektor'])->name('approveRektor');
        Route::post('/{surat}/reject-rektor', [ApprovalController::class, 'rejectRektor'])->name('rejectRektor');
        // Download & Verify Digital Signature
        Route::get('/{surat}/download-signed', [ApprovalController::class, 'downloadSigned'])->name('downloadSigned');
        Route::get('/{surat}/verify-signature', [ApprovalController::class, 'verifySignature'])->name('verifySignature');

        // Admin Tasks (Numbering & Archive)
        Route::get('/admin-tasks', [ApprovalController::class, 'adminTasks'])->name('adminTasks');
        Route::post('/{surat}/numbering', [ApprovalController::class, 'numbering'])->name('numbering');
    });

    // Disposisi Surat Masuk (untuk Rektor)
    Route::get('/disposisi/{suratId}/create', [DisposisiController::class, 'create'])->name('disposisi.create');
    Route::post('/disposisi/{suratId}', [DisposisiController::class, 'store'])->name('disposisi.store');
    Route::get('/disposisi/{disposisi}/pdf', [DisposisiController::class, 'generatePDF'])->name('disposisi.pdf');

    // Manajemen Pengguna (hanya Admin)
    Route::resource('user', UserController::class)->except(['show']);
});