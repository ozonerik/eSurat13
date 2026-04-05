<p align="center">
	<img src="public/images/logo.webp" alt="Logo eSurat 13" width="160">
</p>

<h1 align="center">eSurat 13</h1>

<p align="center">
	Sistem administrasi surat sekolah berbasis Laravel dan Filament untuk pengelolaan surat, approval, audit trail, dan notifikasi Telegram.
</p>

<p align="center">
	<img src="https://img.shields.io/badge/Laravel-13-red" alt="Laravel 13">
	<img src="https://img.shields.io/badge/PHP-8.3%2B-777bb4" alt="PHP 8.3+">
	<img src="https://img.shields.io/badge/Filament-Admin%20Panel-f59e0b" alt="Filament">
	<img src="https://img.shields.io/badge/Queue-Database-0f766e" alt="Queue Database">
	<img src="https://img.shields.io/badge/Notifications-Telegram-229ED9" alt="Telegram">
</p>

## Tentang Aplikasi

eSurat 13 adalah aplikasi pengelolaan surat resmi yang dirancang untuk membantu sekolah atau institusi pendidikan mengelola proses surat secara lebih cepat, tertib, dan terdokumentasi. Aplikasi ini mendukung penomoran surat, alur persetujuan, pengarsipan, audit log, serta notifikasi Telegram agar proses kerja tidak bergantung pada komunikasi manual.

Panel administrasi aplikasi tersedia di `/admin`.

**Developer:** ozonerik IT Solutions

## Fitur Utama

- Manajemen master data surat seperti kategori, jenis surat, counter nomor surat, dan data sekolah.
- Penomoran surat otomatis dengan mekanisme booking nomor surat.
- Workflow status surat dari draft, dikirim, review, disetujui, ditolak, hingga expired.
- Pembatalan otomatis nomor surat yang tidak dipakai setelah melewati masa berlaku booking.
- Audit trail untuk mencatat perubahan penting pada surat.
- Role dan permission berbasis database menggunakan Spatie Permission.
- Notifikasi Telegram berbasis queue untuk pembuat surat dan approver.
- Widget pemantauan kesehatan queue pada dashboard admin.
- Seed data awal untuk role, permission, user, kategori surat, sekolah, dan contoh surat.

## Stack Teknologi

- Backend: Laravel 13
- Admin panel: Filament
- PHP: 8.3+
- Database default: PostgreSQL
- Queue connection default: database
- Frontend build tool: Vite
- Authorization: spatie/laravel-permission

## Changelog Ringkas

### April 2026

#### Notifikasi Telegram
- Implementasi pengiriman notifikasi Telegram berbasis queue.
- Penambahan log pengiriman Telegram dan penanganan retry saat gagal.
- Perbaikan status pengiriman notifikasi Telegram agar lebih stabil.

#### Scheduler dan Booking Surat
- Menambahkan command `surat:expire-bookings` untuk membatalkan booking nomor surat yang kedaluwarsa.
- Menjadwalkan proses queue dan pengecekan booking expired melalui Laravel scheduler.

#### Workflow dan Hak Akses
- Perbaikan alur draft surat, surat dikirim, surat disetujui, surat ditolak, dan review surat.
- Permission diubah menjadi berbasis database agar lebih fleksibel.
- Perbaikan validasi agar surat bernomor tidak dapat dihapus sembarangan.

#### Penyempurnaan UI dan Data Awal
- Penyesuaian label aksi dari buat/simpan menjadi kirim.
- Penambahan faker user dan seed data awal untuk kebutuhan development.

## Kebutuhan Sistem

Sebelum instalasi, siapkan:

- PHP 8.3 atau lebih baru
- Composer
- Node.js dan npm
- PostgreSQL
- Git

Opsional tetapi direkomendasikan:

- Web server Apache atau Nginx
- Process supervisor bila ingin memisahkan queue worker dari scheduler

### Ekstensi PHP yang Harus Di-enable

Pastikan ekstensi berikut aktif pada environment PHP (lokal maupun server):

- `bcmath`
- `ctype`
- `curl`
- `dom`
- `fileinfo`
- `json`
- `mbstring`
- `openssl`
- `pdo`
- `pdo_pgsql` (karena default database PostgreSQL)
- `tokenizer`
- `xml`

Ekstensi yang sangat disarankan untuk kebutuhan umum Laravel:

- `zip` (sering dipakai package manager dan ekspor/impor file)
- `intl` (dukungan format lokal/internasional)
- `gd` atau `imagick` (jika nanti ada kebutuhan manipulasi gambar)

Cara cek cepat ekstensi yang aktif:

```bash
php -m
```

## Cara Clone Proyek

```bash
git clone https://github.com/ozonerik/eSurat13.git
cd eSurat13
```

Jika Anda melakukan fork atau menggunakan remote internal, sesuaikan URL repository pada perintah di atas.

## Instalasi Lokal

### 1. Install dependency backend dan frontend

```bash
composer install
npm install
```

### 2. Buat file environment

Windows:

```powershell
Copy-Item .env.example .env
```

Linux:

```bash
cp .env.example .env
```

### 3. Atur konfigurasi `.env`

Minimal sesuaikan variabel berikut:

```env
APP_NAME="eSurat 13"
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=esurat13
DB_USERNAME=postgres
DB_PASSWORD=

QUEUE_CONNECTION=database
CACHE_STORE=database

TELEGRAM_BOT_NAME=nama_bot_anda
TELEGRAM_BOT_TOKEN=token_bot_anda
TELEGRAM_BASE_URL=https://api.telegram.org

ESURAT_ADMIN_NAME="Administrator eSurat"
ESURAT_ADMIN_EMAIL=admin@test.id
ESURAT_ADMIN_PASSWORD=admin
ESURAT_ADMIN_CHAT_ID=
```

### 4. Generate key, migrasi, seeding, dan storage link

```bash
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

### 5. Build asset frontend

Untuk development:

```bash
npm run dev
```

Untuk production build:

```bash
npm run build
```

### 6. Jalankan aplikasi

Mode sederhana:

```bash
php artisan serve
```

Mode development terintegrasi sesuai script Composer:

```bash
composer run dev
```

Script di atas akan menjalankan:

- web server Laravel
- queue listener
- Vite dev server

## Shortcut Setup

Repository ini menyediakan script Composer berikut:

```bash
composer run setup
```

Script tersebut akan menjalankan instalasi dependency, membuat `.env` jika belum ada, generate app key, migrasi database, install package frontend, dan build asset. Gunakan setelah konfigurasi environment Anda sudah benar.

## Akun Default Development

Setelah menjalankan seeder, akun berikut tersedia untuk kebutuhan pengujian lokal:

| Role | Email | Password |
| --- | --- | --- |
| Admin | admin@test.id | admin |
| Kepala Sekolah | kepsek@test.id | password |
| Guru | guru@test.id | password |
| TU | tu@test.id | password |
| Kaprog | kaprog@test.id | password |
| Wakil Kepala Sekolah | wakasek@test.id | password |
| Pengelola Surat | pengelola@test.id | password |

## Scheduler dan Queue di Server

Aplikasi ini mengandalkan Laravel scheduler untuk dua proses penting:

- Menjalankan command `surat:expire-bookings` setiap menit.
- Menjalankan `queue:work --stop-when-empty --queue=default --tries=3 --sleep=1` setiap menit.

Artinya, pada deployment standar, Anda wajib memastikan `php artisan schedule:run` berjalan setiap menit.

### Linux (Cron)

Tambahkan cron berikut pada user yang menjalankan aplikasi:

```bash
* * * * * cd /path/to/esurat13 && php artisan schedule:run >> /dev/null 2>&1
```

Jika PHP tidak ada di PATH, gunakan path absolut:

```bash
* * * * * cd /path/to/esurat13 && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

### Windows Server (Task Scheduler)

Anda bisa menambahkan task melalui Task Scheduler UI atau menggunakan `schtasks`.

Contoh command:

```cmd
schtasks /create /sc minute /mo 1 /tn "eSurat13 Scheduler" /tr "cmd /c cd /d C:\path\to\esurat13 && C:\path\to\php.exe artisan schedule:run >> NUL 2>&1" /f
```

Ganti:

- `C:\path\to\esurat13` dengan path proyek di server.
- `C:\path\to\php.exe` dengan path executable PHP di server Anda.

Checklist Task Scheduler Windows:

1. Trigger berjalan setiap 1 menit.
2. Opsi run whether user is logged on or not diaktifkan bila perlu.
3. Working directory mengarah ke folder proyek.
4. Akun service memiliki izin ke folder proyek, PHP, dan database.

## Rekomendasi Deployment

- Jalankan `php artisan optimize:clear` setelah update kode.
- Jalankan `php artisan migrate --force` saat deploy ke production.
- Pastikan file upload dan folder `storage` dapat ditulis oleh service web server.
- Jika trafik queue meningkat, pertimbangkan memisahkan queue worker menjadi service terpisah, meskipun konfigurasi saat ini sudah bisa dipicu lewat scheduler.

## Optimasi Server (Filament 5)

Agar panel admin Filament 5 dan proses backend berjalan lancar di production, gunakan checklist optimasi berikut.

### 1) Mode Production Laravel

- Pastikan `APP_ENV=production` dan `APP_DEBUG=false`.
- Setelah deploy, jalankan:

```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Jika ada perubahan konfigurasi atau route, clear cache dulu:

```bash
php artisan optimize:clear
```

### 2) OPcache PHP (Wajib)

Aktifkan OPcache untuk menurunkan waktu eksekusi PHP:

```ini
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
opcache.revalidate_freq=0
```

Catatan: jika `opcache.validate_timestamps=0`, restart PHP-FPM/Apache setiap kali deploy kode baru.

### 3) Web Server dan PHP-FPM

- Gunakan Nginx + PHP-FPM (atau Apache + PHP-FPM) untuk stabilitas beban menengah.
- Sesuaikan parameter `pm.max_children`, `pm.start_servers`, `pm.max_requests` berdasarkan RAM server.
- Set `client_max_body_size` (Nginx) atau `LimitRequestBody` (Apache) agar upload berkas surat tidak gagal.
- Gunakan HTTP/2 dan gzip/brotli untuk mempercepat asset panel admin.

### 4) Queue Worker Khusus

Untuk produksi, jalankan worker sebagai service terpisah (Supervisor/systemd di Linux atau Task Scheduler/Service Manager di Windows), bukan hanya mengandalkan scheduler.

Contoh worker:

```bash
php artisan queue:work --queue=default --sleep=1 --tries=3 --timeout=120 --max-time=3600
```

Lalu saat deploy:

```bash
php artisan queue:restart
```

### 5) Scheduler Harus Stabil

- Pastikan `schedule:run` benar-benar berjalan setiap menit (cron Linux / Task Scheduler Windows).
- Pantau log scheduler dan queue agar job Telegram atau expire booking tidak tertunda.

### 6) Database PostgreSQL

- Pastikan index tersedia untuk kolom yang sering difilter/sort (misal status surat, relasi user, timestamp).
- Gunakan connection pooling (contoh: PgBouncer) jika jumlah koneksi mulai tinggi.
- Jalankan `VACUUM`/`ANALYZE` berkala untuk menjaga performa query.

### 7) Session, Cache, dan File Storage

- Untuk beban tinggi, pertimbangkan Redis untuk `CACHE_STORE` dan `SESSION_DRIVER`.
- Simpan storage pada disk cepat (SSD) dan pastikan permission folder `storage` + `bootstrap/cache` benar.
- Gunakan CDN/reverse proxy bila akses panel berasal dari banyak lokasi jaringan.

### 8) Monitoring Minimum

- Pantau metrik CPU, RAM, disk I/O, dan latency database.
- Pantau failed jobs (`php artisan queue:failed`) dan lakukan retry jika diperlukan.
- Simpan log aplikasi dengan rotasi log agar disk tidak cepat penuh.

### 9) Baseline Spesifikasi Server Production

Berikut baseline praktis untuk deployment eSurat (Filament 5):

| Skala Penggunaan | CPU | RAM | Storage | Catatan |
| --- | --- | --- | --- | --- |
| Kecil (<= 50 user aktif) | 2 vCPU | 4 GB | SSD 40-80 GB | App + DB bisa 1 server |
| Menengah (50-200 user aktif) | 4 vCPU | 8 GB | SSD 80-160 GB | Disarankan pisah DB atau queue worker |
| Besar (> 200 user aktif) | 8 vCPU+ | 16 GB+ | SSD 160 GB+ | Pisah app, DB, dan queue worker |

Catatan baseline:

- Gunakan SSD, bukan HDD, terutama untuk database dan folder `storage`.
- Sediakan ruang kosong minimal 20-30% untuk mencegah degradasi performa.
- Jika notifikasi Telegram dan job background meningkat, tambah instance worker sebelum menaikkan spesifikasi app server utama.

### 10) Prioritas Scaling

Jika aplikasi mulai lambat, urutan tindakan yang direkomendasikan:

1. Aktifkan cache Laravel + OPcache dan validasi query lambat.
2. Pisahkan queue worker dari web process.
3. Pindahkan database ke server terpisah.
4. Tambahkan Redis untuk cache/session.
5. Scale-out app server di belakang reverse proxy/load balancer.

## Pengujian

Untuk menjalankan test:

```bash
php artisan test
```

## Struktur Singkat

- `app/Filament` berisi resource, page, dan widget panel admin.
- `app/Services` berisi service penomoran surat dan integrasi Telegram.
- `app/Jobs` berisi job pengiriman Telegram.
- `app/Console/Commands` berisi command untuk proses booking surat expired.
- `routes/console.php` berisi definisi scheduler aplikasi.

## Catatan Pengembangan

- Konfigurasi Telegram menggunakan `TELEGRAM_BOT_NAME`, `TELEGRAM_BOT_TOKEN`, dan `TELEGRAM_BASE_URL`.
- Queue default menggunakan database, sehingga migrasi tabel jobs wajib dijalankan.
- Untuk penggunaan lokal cepat, buka panel admin di `http://127.0.0.1:8000/admin` setelah server dijalankan.

## Lisensi

Repository ini mengikuti lisensi yang tercantum pada project. Jika akan digunakan ulang untuk institusi lain, sesuaikan identitas sekolah, alur persetujuan, dan template surat sebelum produksi.
