# ALUR APPROVAL SURAT WORKFLOW - SISTEM ARSIP PERSURATAN

## 📋 Deskripsi Umum Alur

Alur workflow approval surat mengikuti prosedur standar departemen/universitas:

1. **Departemen/Biro** → Buat dan kirim draft surat ke Wakil Rektor
2. **Wakil Rektor** → Periksa dan paraf surat (approve/reject)
3. **Rektor** → Tanda tangan surat yang sudah diparaf
4. **Rektor** → Beri nomor surat
5. **Rektor** → Arsipkan surat (fisik + digital)
6. **Rektor** → Kembalikan ke departemen

---

## 📊 Status Workflow Lengkap

| No. | Status | Deskripsi | Actor |
|-----|--------|-----------|-------|
| 1 | `draft` | Draft awal di departemen | Departemen |
| 2 | `pending_wr` | Menunggu approval Wakil Rektor | Wakil Rektor |
| 3 | `approved_wr` | Disetujui & diparaf WR | Wakil Rektor |
| 4 | `rejected_wr` | Ditolak WR, kembali untuk revisi | Wakil Rektor |
| 5 | `pending_rektor` | Menunggu TTD Rektor | Rektor |
| 6 | `signed_rektor` | Sudah ditandatangani Rektor | Rektor |
| 7 | `numbered` | Sudah diberi nomor surat | Rektor |
| 8 | `archived` | Sudah diarsipkan | Rektor |
| 9 | `returned` | Dikembalikan ke departemen | Rektor |

---

## 🔄 Tahap-Tahap Workflow Detail

### Tahap 1: DRAFT & SUBMISSION
**Aktor:** Departemen/Biro

```
- Departemen membuat draft surat
- Status: "draft"
- approval_status: "draft"
- Departemen submit surat ke Wakil Rektor
- Status berubah ke: "pending_wr"
- approval_status: "pending_wr"
```

### Tahap 2: APPROVAL WAKIL REKTOR (WR)
**Aktor:** Wakil Rektor  
**URL:** `/approval/pending`

**Option A - SETUJUI (Paraf Surat):**
```
- WR klik tombol "Setujui"
- Status: "pending_wr" → "approved_wr"
- Catat: 
  * paraf_wr_by (user ID)
  * paraf_wr_at (timestamp)
- Surat langsung dikirim ke Rektor
- Buat record di tabel Approval (status: 'disetujui')
```

**Option B - TOLAK (Minta Revisi):**
```
- WR klik tombol "Tolak"
- Modal muncul untuk input alasan penolakan
- Status: "pending_wr" → "rejected_wr"
- Catat:
  * revision_notes (alasan penolakan)
  * revision_count++ (increment counter)
- Buat record di tabel Approval (status: 'ditolak')
- Surat kembali ke Departemen dengan catatan revisi
- Departemen bisa revisi dan submit ulang
```

### Tahap 3: PENANDATANGANAN REKTOR
**Aktor:** Rektor  
**URL:** `/approval/waiting-signature`

**Proses Tanda Tangan:**
```
- Rektor melihat daftar surat yang sudah diparaf WR
- Status: "approved_wr" → "pending_rektor" (optional)
- Rektor klik tombol "Tanda Tangan"
- Status: "pending_rektor" → "signed_rektor"
- Catat:
  * signed_rektor_by (user ID)
  * signed_rektor_at (timestamp)
```

### Tahap 4: PENOMORAN SURAT
**Aktor:** Rektor  
**URL:** `/approval/waiting-signature`

**Proses Penomoran:**
```
- Rektor klik tombol "Beri Nomor"
- Input: nomor_urut (contoh: "001", "002", dst)
- Generate nomor lengkap: nomor_urut/TIPE/BULAN/TAHUN
  Contoh: "001/KL/01/2026" (Keluar, Januari 2026)
- Status: "signed_rektor" → "numbered"
- Catat:
  * nomor_surat (nomor lengkap)
  * nomor_urut (nomor urut)
```

### Tahap 5: ARCHIVING
**Aktor:** Rektor

**Proses Arsipkan:**
```
- Rektor arsipkan surat
- Surat disimpan ke lemari arsip fisik dan file di PC
- Status: "numbered" → "archived"
- Buat record di tabel arsips
- Catat lokasi dan metadata arsip
```

### Tahap 6: RETURN TO DEPARTMENT
**Aktor:** Rektor

**Proses Pengembalian:**
```
- Rektor kembalikan surat ke Departemen/Biro
- Status: "archived" → "selesai" + approval_status: "returned"
- Departemen menerima surat lengkap dengan:
  * Nomor surat resmi
  * Paraf WR
  * Tanda tangan Rektor
  * File terarsiP
```

---

## 📁 Database Schema Tracking Fields

### Tabel: `surats`
```sql
-- Workflow Status
approval_status: enum('draft', 'pending_wr', 'approved_wr', 'rejected_wr', 
                       'pending_rektor', 'signed_rektor', 'numbered', 
                       'archived', 'returned')

-- Paraf Wakil Rektor
paraf_wr_by: foreignId(users)  -- User ID yang paraf
paraf_wr_at: timestamp          -- Kapan diparaf

-- Tanda Tangan Rektor
signed_rektor_by: foreignId(users)  -- User ID yang TTD
signed_rektor_at: timestamp         -- Kapan TTD

-- Penomoran
nomor_urut: string (unique)  -- Nomor urut sebelum generate
nomor_surat: string (unique) -- Nomor surat lengkap

-- Revisi Tracking
revision_count: integer      -- Berapa kali di-revisi
revision_notes: text         -- Catatan penolakan terakhir
```

### Tabel: `approvals` (Dokumentasi)
```sql
surat_id: foreignId
approver_id: foreignId(users)
status: enum('disetujui', 'ditolak')
catatan: text (nullable)
approved_at: timestamp
```

---

## 🛣️ API Routes

### Untuk Wakil Rektor
```
GET    /approval/pending              -- List surat pending untuk WR
POST   /approval/{surat}/approve      -- WR approve & paraf
POST   /approval/{surat}/reject       -- WR reject & request revisi
```

### Untuk Rektor
```
GET    /approval/waiting-signature    -- List surat untuk TTD & penomoran
POST   /approval/{surat}/sign         -- Rektor tanda tangani
POST   /approval/{surat}/numbering    -- Rektor beri nomor
POST   /approval/{surat}/archive      -- Rektor arsipkan
POST   /approval/{surat}/return       -- Rektor kembalikan ke dept
```

---

## 🔒 Access Control

| Role | Permission |
|------|-----------|
| `wr` (Wakil Rektor) | View pending, Approve, Reject |
| `rektor` (Rektor) | View waiting-signature, Sign, Number, Archive, Return |
| `admin` | Full access |
| `staff` | Create/Edit draft |
| `viewer` | View only |

---

## 📝 Validation Rules

### Penolakan (Reject)
- `alasan`: Required, string, min 10 characters
- Catatan akan disimpan di `revision_notes`

### Penomoran (Numbering)
- `nomor_urut`: Required, string, numeric pattern, unique
- Auto-generate format: `{nomor_urut}/KL/{bulan}/{tahun}`

---

## 🎯 Key Features

✅ **Multi-stage Approval** - Paraf WR → TTD Rektor  
✅ **Revision Tracking** - Counter revisi & catatan penolakan  
✅ **Audit Trail** - Tercatat siapa & kapan setiap tahap  
✅ **Automatic Numbering** - Generate nomor surat otomatis  
✅ **Status Visibility** - Setiap tahap memiliki status yang jelas  
✅ **Return to Sender** - Revisi dapat disubmit ulang  

---

## 📌 Implementation Notes

- Surat yang ditolak masuk status `rejected_wr` dan `revision_count` increment
- Departemen bisa lihat catatan revisi dan resubmit
- Ketika resubmit, status kembali ke `pending_wr`
- Penomoran hanya bisa dilakukan setelah TTD Rektor
- Surat tidak bisa di-archive sebelum diberi nomor
- Setiap perubahan status dicatat dengan timestamp dan user ID

