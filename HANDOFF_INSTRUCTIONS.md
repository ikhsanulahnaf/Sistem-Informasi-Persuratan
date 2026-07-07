# 📦 Checklist Persiapan Handoff ke Tim IT

Setelah Anda membuat `DEPLOYMENT_GUIDE.md`, lakukan langkah-langkah berikut di komputer Anda untuk menyiapkan file yang akan dikirim (di-deploy):

## 1️⃣ Build Frontend Assets (Wajib)
Karena folder `public/build` belum ada, aplikasi akan tampil berantakan jika langsung dicopy.
Jalankan perintah ini di terminal VS Code Anda:

## 1️⃣ Build Frontend Assets (Wajib)

### A. Cek Node.js (Penting!)
Perintah `npm` hanya bisa jalan jika komputer Anda sudah terinstall **Node.js**.
Coba ketik `node -v` di terminal. Jika error, download dan install dulu dari [nodejs.org](https://nodejs.org/).

### B. Install & Build
Setelah Node.js terinstall, jalankan perintah ini berurutan di terminal VS Code:

```bash
# 1. Install dependencies (library javascript)
npm install

# 2. Build assets (membuat file CSS/JS)
npm run build
```

Pastikan setelah selesai, muncul folder baru: `public/build`.

## 2️⃣ Bersihkan Cache
Agar konfigurasi lokal tidak terbawa ke server, bersihkan cache aplikasi:

```bash
php artisan optimize:clear
```

## 3️⃣ Siapkan File untuk Dikirim
Anda perlu mengemas source code. Ada 2 cara umum, pilih salah satu:

### Opsi A: Menggunakan GitHub/GitLab (Rekomendasi)
1.  Push semua perubahan terakhir ke repository Git Anda.
2.  Berikan link repository ke tim IT.

### Opsi B: Menggunakan File ZIP
Jika tidak pakai Git, buat file ZIP dari folder project `arsip_laravel`.
**PENTING:** Saat menge-zip, **JANGAN** sertakan folder:
-   `node_modules` (Sangat berat, ribuan file)
-   `vendor` (Library PHP, akan diinstall di server)
-   `.env` (Konfigurasi lokal, server akan buat sendiri)
-   `.git` (Folder git, jika ada)

## 4️⃣ Apa yang Harus Dikirim ke IT?
Serahkan item berikut kepada tim IT:

1.  **Source Code** (Link Git atau File ZIP dari langkah 3).
2.  **File Panduan**: `DEPLOYMENT_GUIDE.md` (yang baru kita buat).
3.  **Catatan Tambahan**:
    *   Infokan bahwa ini aplikasi Laravel 11 + Vite.
    *   Infokan credentials user default (Rektor/Staff) untuk testing.

## 5️⃣ Testing Mandiri (Opsional)
Jika Anda ingin memastikan *build* berhasil sebelum dikirim:
1.  Jalankan `php artisan serve`.
2.  Buka aplikasi di browser (biasanya `http://127.0.0.1:8000`).
3.  Cek apakah tampilan (CSS/Layout) terlihat benar dan icons muncul.
4.  Coba login dan navigasi menu.

---
**Siap dikirim!** 🚀
