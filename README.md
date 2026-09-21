# Event-Osis — Sistem Pengelolaan Event & Pendaftaran OSIS

*Lembar Jawaban LKPD Sumatif Tengah Semester*
- *Nama*: Wyanet In Nakeisha
- *Kelas*: XI-2
- *Jurusan*: Pengembangan Perangkat Lunak dan GIM (RPL)
- *Tema Desain Web*: Emerald / Green Palette 

---

## Deskripsi Project
*Event-Osis* adalah aplikasi berbasis web menggunakan framework *Laravel MVC* yang dirancang untuk menyelesaikan permasalahan pencatatan event OSIS secara terpisah, mempermudah pemantauan event (akan datang, berlangsung, selesai, dibatalkan), serta menyediakan sistem pendaftaran online untuk peserta dengan validasi kapasitas dan pencegahan pendaftaran ganda.

---

##  Fitur Utama
1. *Multi-Role Authentication & Authorization*:
   - Pembatasan hak akses berbasis *Custom Middleware* (`RoleMiddleware`) untuk 3 role: *Admin*, *Panitia*, dan *Peserta*.
2. **Event Management (CRUD)**:
   - Pengelolaan event lengkap beserta kategori, tanggal pelaksanaan, lokasi, deskripsi, kuota/kapasitas peserta, banner gambar, dan status event (`upcoming`, `ongoing`, `completed`, `cancelled`).
3. **Event Registration System**:
   - Pendaftaran event secara online bagi peserta.
   - Pengecekan otomatis kuota/kapasitas event.
   - Pencegahan pendaftaran ganda (duplicate registration prevention).
4. **Search + Filter + Pagination**:
   - Pencarian event & peserta berdasarkan kata kunci, filter kategori, dan filter status yang **bekerja secara bersamaan** (combined state via `withQueryString()`).
5. **Dashboard Interaktif**:
   - Ringkasan statistik statistik event, pendaftaran, dan pengguna yang disesuaikan untuk masing-masing role.
6. **Notification System**:
   - Notifikasi database real-time saat peserta mendaftar event dan ketika status pendaftaran diubah oleh panitia/admin.
7. **Eloquent ORM & Eager Loading**:
   - Implementasi relasi data optimal dengan eager loading (`with`, `withCount`) untuk mencegah N+1 query problem.
8. **FormRequest Validation**:
   - Validasi input terpisah dari Controller menggunakan FormRequest dedicated (`StoreEventRequest`, `RegisterRequest`, `LoginRequest`, dll).

---

## Role & Hak Akses

| Fitur / Halaman | Admin | Panitia | Peserta | Guest |
| :--- | :---: | :---: | :---: | :---: |
| Melihat Daftar & Detail Event | can | can | can | can |
| Mendaftar Event | can't | can't | can | can't |
| Melihat Riwayat Pendaftaran | can't | can't | can | can't |
| Membuat & Mengedit Event | can | can | can't | can't |
| Menghapus Event | can | can | can't | can't |
| Kelola Status Pendaftar | can | can | can't | can't |
| Kelola Kategori Event | can | can't | can't | can't |
| Kelola Hak Akses User & Role | can | can't | can't | can't |

---

## Teknologi yang Digunakan
- **Framework**: Laravel 12.x / 13.x (PHP 8.4)
- **Database**: SQLite / MySQL
- **Templating**: Laravel Blade Component Layout
- **Frontend & Styling**: Tailwind CSS (Emerald Green Theme)
- **Testing**: Pest PHP / PHPUnit
- **Interactive JS**: Alpine.js

---

## Cara Menjalankan Project

### 1. Clone Repository & Install Dependencies
```bash
git clone <repository-url>
cd Event-Osis
composer install
```

### 2. Konfigurasi Environment & Key
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Migrasi & Seeder Database
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

### 4. Akun Uji Coba (Seeder Demo)
Setelah me-run `db:seed`, gunakan akun berikut untuk menguji 3 role:
- **Admin**: `admin@osis.sch.id` / `password`
- **Panitia**: `panitia@osis.sch.id` / `password`
- **Peserta**: `peserta@osis.sch.id` / `password`

### 5. Jalankan Server Lokal
```bash
php artisan serve
```
Akses aplikasi di browser: [http://127.0.0.1:8000](http://127.0.0.1:8000)

### 6. Jalankan Pengujian Automated (Testing)
```bash
php artisan test
```

---

## Catatan Tugas LKPD
Dikembangkan oleh *Wyanet In Nakeisha (XI-2)* untuk memenuhi Lembar Jawab LKPD Sumatif Tengah Semester.
