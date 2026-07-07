# Panduan Deployment Aplikasi Arsip Surat (Laravel)

Dokumen ini berisi langkah-langkah detail untuk men-deploy aplikasi Arsip Surat ke server produksi yang disediakan oleh tim IT.

---

## 📋 1. Persiapan Server (Request ke Tim IT)

Pastikan server yang disediakan memiliki spesifikasi minimum berikut agar aplikasi berjalan lancar:

**Server Requirements:**
- **OS**: Linux (Ubuntu 22.04 LTS recommended) atau Windows Server.
- **Web Server**: Nginx (Recommended) atau Apache/IIS.
- **PHP Version**: 8.2 atau lebih baru.
- **Database**: MySQL 8.0+ atau MariaDB 10.6+ (Hindari SQLite untuk production multi-user).
- **Composer**: Dependency manager untuk PHP.
- **Node.js & NPM**: Versi 18+ (Dibutuhkan jika build assets dilakukan di server).

**PHP Extensions Wajib:**
Pastikan ekstensi PHP berikut aktif di `php.ini`:
- `bcmath`
- `ctype`
- `curl`
- `dom`
- `fileinfo`
- `json`
- `mbstring`
- `openssl`
- `pcre`
- `pdo` (dan `pdo_mysql`)
- `tokenizer`
- `xml`

---

## 🚀 2. Langkah-langkah Deployment

### Langkah 1: Upload Source Code
Upload seluruh folder proyek ke server, misal ke direktori `/var/www/html/arsip-surat`.
**PENTING:** Jangan upload folder `vendor` dan `node_modules`. Biarkan folder ini di-generate di server.

Jika menggunakan Git:
```bash
cd /var/www/html
git clone https://github.com/username/arsip_laravel.git arsip-surat
cd arsip-surat
```

### Langkah 2: Install Dependencies (PHP)
Jalankan perintah ini di dalam folder proyek untuk menginstall library PHP:

```bash
composer install --optimize-autoloader --no-dev
```

### Langkah 3: Konfigurasi Environment (.env)
1. Salin file contoh konfigurasi:
   ```bash
   cp .env.example .env
   ```
2. Edit file `.env` dan sesuaikan dengan setting server:
   ```ini
   APP_NAME="Sistem Arsip Surat"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://surat.kampus.ac.id  # Ganti dengan domain asli
   
   # Konfigurasi Database (Minta kredensial ke IT)
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_kampus
   DB_USERNAME=user_database
   DB_PASSWORD=password_database
   
   # Optimalisasi Session & Cache
   SESSION_DRIVER=database
   CACHE_STORE=database
   QUEUE_CONNECTION=database
   ```
3. Generate Application Key:
   ```bash
   php artisan key:generate
   ```

### Langkah 4: Setup Database
Pastikan database kosong sudah dibuat, lalu jalankan migrasi tabel dan seeding data awal:

```bash
php artisan migrate --seed --force
```
> *Note: Flag `--force` dibutuhkan saat environment set ke production.*

### Langkah 5: Build Frontend Assets
Build file CSS dan JS menggunakan Vite. 

Jika server memiliki Node.js:
```bash
npm install
npm run build
```

*Opsi Alternatif (Jika server tidak ada Node.js):*
Lakukan `npm run build` di komputer lokal Anda, lalu upload folder `public/build` dan file `public/manifest.json` ke server secara manual.

### Langkah 6: Folder Permissions & Storage Link
Pastikan web server (misal user `www-data` di Linux) bisa menulis ke folder storage:

```bash
# Set owner ke user web server
chown -R www-data:www-data storage bootstrap/cache

# Set permission write
chmod -R 775 storage bootstrap/cache

# Buat symlink storage public agar file upload bisa diakses
php artisan storage:link
```

### Langkah 7: Optimalisasi Cache (Production Only)
Jalankan perintah ini agar aplikasi meload konfigurasi lebih cepat:

```bash
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache
```

---

## 🌐 3. Konfigurasi Web Server

Berikut adalah contoh konfigurasi untuk Nginx. Minta tim IT untuk menyesuaikan `root` path-nya.

**Nginx Config Example:**
```nginx
server {
    listen 80;
    server_name surat.kampus.ac.id;
    root /var/www/html/arsip-surat/public; # Point ke folder public!
 
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
 
    index index.php;
 
    charset utf-8;
 
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
 
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
 
    error_page 404 /index.php;
 
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
 
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## ⚠️ Troubleshooting

**1. Halaman Putih / Error 500**
- Cek permission folder `storage`.
- Cek log error di `storage/logs/laravel.log`.

**2. Tampilan CSS Rusak / File Tidak Ditemukan**
- Pastikan folder `public/build` ada isinya.
- Jalankan `php Artisan storage:link`.
- Pastikan Document Root web server mengarah ke folder `/public`, BUKAN ke root folder proyek.

**3. Database Error**
- Pastikan user database memiliki hak akses penuh (CREATE, INSERT, UPDATE, DELETE) ke database yang digunakan.

---

Semoga deployment berjalan lancar! 🚀
