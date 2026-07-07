# IMPLEMENTASI CHECKLIST - WORKFLOW APPROVAL SURAT

## ✅ Completed Implementation

### Database Changes
- [x] Migration: `2026_01_16_000001_update_surats_table_for_workflow.php`
  - Added `approval_status` field dengan 9 enum values
  - Added `paraf_wr_by`, `paraf_wr_at` columns
  - Added `signed_rektor_by`, `signed_rektor_at` columns
  - Added `nomor_urut`, `revision_count`, `revision_notes` columns

### Model Updates
- [x] Updated `Surat.php` model
  - Added new fillable fields
  - Added datetime casts
  - Added relationships: `parafBy()`, `signedBy()`

- [x] `Approval.php` model (already exists, ready to use)

### Controller Implementation
- [x] Complete `ApprovalController.php` with methods:
  - `pending()` - View pending surat untuk WR
  - `approve()` - WR approve & paraf surat
  - `reject()` - WR reject & request revisi
  - `waitingSignature()` - View untuk Rektor signature
  - `sign()` - Rektor tanda tangan surat
  - `numbering()` - Rektor beri nomor surat
  - `archive()` - Rektor arsipkan surat
  - `returnToDepartment()` - Rektor kembalikan ke departemen

### Views Implementation
- [x] `approval/pending.blade.php` - List untuk Wakil Rektor
  - Tampilkan daftar surat pending
  - Tombol: Lihat Dokumen, Setujui, Tolak
  - Modal tolak dengan textarea untuk catatan revisi
  - Display revision count jika ada

- [x] `approval/waiting-signature.blade.php` - List untuk Rektor
  - Tampilkan surat yang sudah diparaf WR
  - Tombol: Lihat Dokumen, Tanda Tangani, Beri Nomor
  - Modal numbering dengan input nomor urut
  - Display info paraf WR (nama & timestamp)

### Documentation
- [x] `WORKFLOW_DOCUMENTATION.md` - Dokumentasi lengkap workflow

---

## 🔧 Next Steps (Belum Implemented)

### 1. Routes Registration
**File:** `routes/web.php`

Add these routes:
```php
Route::middleware('auth')->group(function () {
    // Wakil Rektor routes
    Route::prefix('approval')->name('approval.')->group(function () {
        Route::get('/pending', [ApprovalController::class, 'pending'])->name('pending');
        Route::post('/{surat}/approve', [ApprovalController::class, 'approve'])->name('approve');
        Route::post('/{surat}/reject', [ApprovalController::class, 'reject'])->name('reject');
        
        // Rektor routes
        Route::get('/waiting-signature', [ApprovalController::class, 'waitingSignature'])->name('waitingSignature');
        Route::post('/{surat}/sign', [ApprovalController::class, 'sign'])->name('sign');
        Route::post('/{surat}/numbering', [ApprovalController::class, 'numbering'])->name('numbering');
        Route::post('/{surat}/archive', [ApprovalController::class, 'archive'])->name('archive');
        Route::post('/{surat}/return', [ApprovalController::class, 'returnToDepartment'])->name('returnToDepartment');
    });
});
```

### 2. Run Migration
```bash
php artisan migrate
```

### 3. Navigation/Sidebar Menu Updates
Add links in layout untuk:
- WR: "Approval Pending" → `/approval/pending`
- Rektor: "Waiting Signature" → `/approval/waiting-signature`

### 4. Update User Factory/Seeder
Pastikan seed users memiliki role:
- `wr` - Wakil Rektor
- `rektor` - Rektor
- `staff` - Staff departemen

### 5. Create Department Dashboard
View untuk departemen melihat:
- Surat yang dikirim (status tracking)
- Surat yang ditolak (dengan catatan revisi)
- Surat yang sudah selesai

### 6. Add Status Badge Component
Component untuk display status approval dengan warna:
- `draft` - Gray
- `pending_wr` - Yellow
- `approved_wr` - Blue
- `rejected_wr` - Red
- `signed_rektor` - Purple
- `numbered` - Green
- `archived` - Dark Green
- `returned` - Success

### 7. Email Notifications (Optional)
Kirim email notification ke:
- Wakil Rektor: "Surat baru menunggu approval"
- Departemen: "Surat Anda ditolak, silakan revisi"
- Departemen: "Surat Anda selesai dan siap diambil"

### 8. Audit Log (Optional)
Track setiap perubahan status dengan:
- User ID
- Timestamp
- Old status → New status
- Catatan (jika ada)

---

## 📊 Role-Based Access Control

### Wakil Rektor (wr)
- Can view: `/approval/pending`
- Can: approve surat, reject surat (minta revisi)
- Cannot: sign, number, archive

### Rektor (rektor)
- Can view: `/approval/waiting-signature`
- Can: sign surat, number surat, archive, return
- Cannot: approve/reject (itu WR punya)

### Staff/Departemen (staff)
- Can: create surat (draft)
- Can: submit surat ke WR
- Can: view surat yang ditolak (dengan catatan)
- Can: revise & resubmit
- Can: view surat yang sudah selesai

### Admin (admin)
- Full access semua fungsi

---

## 🚀 Testing Checklist

- [ ] Run migration tanpa error
- [ ] Test WR approve surat: 
  - [ ] Status berubah ke `approved_wr`
  - [ ] `paraf_wr_by` dan `paraf_wr_at` terisi
  - [ ] Approval record created
- [ ] Test WR reject surat:
  - [ ] Status berubah ke `rejected_wr`
  - [ ] `revision_count` increment
  - [ ] `revision_notes` saved
  - [ ] Departemen bisa lihat catatan
- [ ] Test Rektor sign surat:
  - [ ] Status berubah ke `signed_rektor`
  - [ ] `signed_rektor_by` dan `signed_rektor_at` terisi
- [ ] Test Rektor numbering:
  - [ ] Nomor surat auto-generate: `001/KL/01/2026`
  - [ ] Status berubah ke `numbered`
  - [ ] `nomor_urut` dan `nomor_surat` saved
- [ ] Test Rektor archive:
  - [ ] Status berubah ke `archived`
  - [ ] Arsips record created
- [ ] Test Rektor return:
  - [ ] Status berubah ke `returned`
  - [ ] Surat status menjadi `selesai`

---

## 📝 Notes

- Default password untuk user adalah `"password"`
- Gunakan `php artisan db:seed` untuk populate user dengan role yang benar
- Ensure `public/storage` symlink sudah dibuat: `php artisan storage:link`
- Storage path di `.env` harus pointing ke `/storage/app/public`

---

## 🔗 Related Files

- Database Migration: `database/migrations/2026_01_16_000001_update_surats_table_for_workflow.php`
- Models: `app/Models/Surat.php`, `app/Models/Approval.php`
- Controller: `app/Http/Controllers/ApprovalController.php`
- Views: 
  - `resources/views/approval/pending.blade.php`
  - `resources/views/approval/waiting-signature.blade.php`
- Documentation: `WORKFLOW_DOCUMENTATION.md`
