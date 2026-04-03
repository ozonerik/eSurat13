# PRODUCT REQUIREMENTS DOCUMENT (PRD)  
**APLİKASI eSURAT SMKN 1 KRANGKENG**

**Versi:** 1.3 (Final - Notifikasi Telegram)  
**Tanggal:** 3 April 2026  
**Pembuat:** Tim Pengembangan (Grok + Harper + Benjamin + Lucas)  
**Status:** Approved for Development  

---

## 1. Pendahuluan

### 1.1 Latar Belakang
Aplikasi eSurat adalah sistem digital terintegrasi untuk mengelola proses pembuatan, penomoran, persetujuan, penandatanganan elektronik, dan pengarsipan surat resmi di SMKN 1 Krangkeng. Sistem ini menggantikan proses manual berbasis kertas agar lebih cepat, transparan, aman, dan sesuai dengan prosedur birokrasi standar di Indonesia.

### 1.2 Tujuan Bisnis
- Mempercepat proses pembuatan dan persetujuan surat hingga 70%.
- Menjamin nomor surat unik, tidak duplikat, dan sesuai format resmi sekolah.
- Menyediakan tanda tangan elektronik + stempel otomatis + QR Code verifikasi.
- Memastikan traceability lengkap melalui audit trail.
- Mengarsipkan surat secara otomatis dan mudah diverifikasi keasliannya.
- Memberikan notifikasi real-time via **Telegram Bot** (gratis, cepat, dan reliable).

### 1.3 Ruang Lingkup (Fase 1 - MVP)
**In Scope:**
- Pembuatan surat keluar dengan template
- Penomoran otomatis + booking system
- Watermark otomatis pada draft
- Workflow approval & tanda tangan digital
- Stempel otomatis + QR Code verifikasi
- Notifikasi **Telegram Bot API**
- Arsip surat final & searchable
- Audit trail lengkap

**Out of Scope (Fase 1):**
- Surat masuk
- Integrasi TTE bersertifikat BSrE (Fase 2)
- Mobile app native (mulai dengan web responsive)

---

## 2. Stakeholder & User Roles

| Role                  | Deskripsi                                      | Akses                          |
|-----------------------|------------------------------------------------|--------------------------------|
| **Admin**             | Kelola jenis surat, template, user, Telegram Bot | Full Control                   |
| **Pembuat Surat**     | Guru/Staf yang membuat surat                   | Create, Edit, View own         |
| **Pimpinan/Approver** | Kepala Sekolah / Wakil Kepala                  | Review, Sign, Approve/Reject   |
| **Viewer/Arsip**      | User lain (opsional)                           | Read-only arsip                |

---

## 3. Alur Penggunaan Utama (User Flow)

1. **Login** → Dashboard
2. **Pilih Jenis Surat** → template (.docx/.xlsx) otomatis tersedia.
3. **Generate Nomor Surat** → nomor terbooking otomatis + notifikasi Telegram.
4. **Download → Edit → Upload Draft** → upload PDF → sistem otomatis embed watermark “DRAFT”.
5. **Kirim untuk Persetujuan** → status “Menunggu Persetujuan” + notifikasi Telegram ke pimpinan.
6. **Proses di Pimpinan** → buka PDF → tanda tangan (upload gambar / draw dengan mouse/stylus, bisa drag-scale-rotate) → stempel otomatis → Approve/Reject.
7. **Finalisasi** → watermark dihapus → QR Code verifikasi diembed → notifikasi Telegram ke pembuat.
8. **Download & Arsip** → download PDF final + arsip otomatis.

**Catatan Penting:**  
Jika dalam **24 jam** setelah booking belum di-upload draft → nomor surat **otomatis dibatalkan** + notifikasi Telegram.

---

## 4. Logika & Algoritma Penting

### 4.1 Penomoran Surat (Format Resmi SMKN 1 Krangkeng)
**Format:**  
`[NO URUT 4 DIGIT]/[KODE KATEGORI]/SMKN1-Krangkeng/[TAHUN]`

**Contoh:** `0123/KET/SMKN1-Krangkeng/2026`

**Algoritma:**  
- Generate nomor unik per kategori + tahun.  
- Booking system dengan cleanup otomatis setelah 24 jam.  
- Update counter dan log audit.

### 4.2 Watermark Draft
- Saat upload PDF draft → overlay teks **“DRAFT”** / **“RANCANGAN”** (diagonal, opacity 30–40%, ukuran besar).  
- Saat Approved → regenerate PDF tanpa watermark.

### 4.3 Tanda Tangan & Stempel Otomatis
- Canvas drawing atau upload gambar ttd (bisa drag, scale, rotate).  
- Stempel resmi otomatis embed relatif terhadap posisi ttd.

### 4.4 QR Code Verifikasi
- Generate verification token saat approve.  
- Embed QR Code di pojok kanan bawah halaman terakhir.  
- Halaman verifikasi publik: `/verify?token=xxx`

### 4.5 Notifikasi Telegram (Bot API Resmi)
- Menggunakan **Telegram Bot API**[](https://core.telegram.org/bots/api).  
- Setiap user/pimpinan memasukkan **Chat ID** Telegram mereka di profil (atau admin bisa mengatur).  
- Bot mengirim pesan langsung ke Chat ID pribadi atau group (jika diperlukan).  
- Implementasi dengan queue untuk menghindari blocking.  
- Retry otomatis maksimal 3 kali jika gagal.  
- Log semua pengiriman ke tabel `telegram_logs`.

**Cara Setup Bot (untuk Admin):**
1. Buka Telegram → cari **@BotFather** → /newbot.
2. Dapatkan **BOT TOKEN**.
3. Simpan token di pengaturan Admin eSurat.
4. User/pimpinan mulai chat dengan bot tersebut untuk mendapatkan Chat ID mereka (bisa otomatis detect saat pertama kali interaksi).

**Daftar Pesan Notifikasi Telegram (contoh):**

1. **Booking Nomor Surat**  
   “✅ Nomor surat *0123/KET/SMKN1-Krangkeng/2026* telah dibooking. Silakan upload draft dalam 24 jam.”

2. **Draft Diupload**  
   “📄 Draft surat *0123/KET/SMKN1-Krangkeng/2026* berhasil diupload.”

3. **Menunggu Persetujuan (ke Pimpinan)**  
   “📬 Ada surat baru menunggu persetujuan:\nNo: 0123/KET/SMKN1-Krangkeng/2026\nJenis: Keterangan\nPembuat: Nama Guru\nLink: [Tombol Review]”

4. **Surat Disetujui**  
   “🎉 Surat *0123/KET/SMKN1-Krangkeng/2026* telah disetujui oleh Kepala Sekolah. Surat sudah final dan dapat didownload.”

5. **Surat Ditolak**  
   “❌ Surat *0123/KET/SMKN1-Krangkeng/2026* ditolak dengan catatan: [catatan]. Silakan perbaiki.”

6. **Booking Expired**  
   “⚠️ Nomor surat *0123/KET/SMKN1-Krangkeng/2026* telah dibatalkan otomatis karena tidak ada aktivitas dalam 24 jam.”

---

## 5. Persyaratan Fungsional Detail

### Fitur Utama
- Manajemen jenis surat & template (CRUD)
- Generate & booking nomor surat
- PDF uploader dengan auto-watermark
- PDF editor tanda tangan
- Auto stempel + QR Code
- Workflow approval
- **Notifikasi Telegram Bot** otomatis
- Arsip surat (pencarian lanjutan)
- Audit trail lengkap
- Halaman verifikasi publik via QR

### Fitur Admin
- Konfigurasi **Telegram Bot Token**
- Kelola Chat ID user
- Manajemen pesan notifikasi (template teks)
- Laporan penggunaan surat

---

## 6. Persyaratan Non-Fungsional

**Teknologi Stack Rekomendasi:**
- **Backend:** Laravel / Node.js / Django
- **Frontend:** React / Vue.js + Tailwind
- **PDF Processing:** pdf-lib atau PyMuPDF
- **Database:** PostgreSQL
- **Storage:** MinIO / S3
- **Queue:** Redis
- **Telegram Integration:** Laravel Notification Channels Telegram / Telegraf (Node.js) / requests ke Bot API

**Performa:**
- Generate nomor < 2 detik
- Proses watermark + QR < 5 detik
- Notifikasi Telegram < 3 detik

**Keamanan:**
- Bot Token disimpan terenkripsi
- Audit log immutable
- HTTPS + CSP
- Rate limiting sesuai limit Telegram (30 msg/detik)

**Lainnya:**
- Responsive design
- Support stylus pen
- Gratis (tidak ada biaya per pesan seperti WhatsApp Business)

---

## 7. Model Data Utama

- `jenis_surat`
- `counter_surat`
- `surat` (no_surat, status, verification_token, dll.)
- `audit_log`
- `telegram_logs` (chat_id, message, status, sent_at)
- `users` (tambah kolom `telegram_chat_id`)

---

## 8. Acceptance Criteria

- Nomor surat sesuai format & unik
- Semua draft wajib punya watermark “DRAFT”
- PDF final tidak ada watermark + wajib ada QR Code
- Scan QR menuju halaman verifikasi yang akurat
- Booking otomatis batal setelah 24 jam + notifikasi Telegram
- Semua perubahan status memicu notifikasi Telegram yang tepat
- Audit log mencatat setiap aksi

---

## 9. Roadmap

**Fase 1 (MVP – 1.3):** Semua fitur di atas dengan Telegram  
**Fase 2:** Integrasi TTE BSrE, multi-approver, laporan statistik, mobile app