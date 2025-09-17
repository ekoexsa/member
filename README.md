# Sistem Member Berbasis Bootstrap & PHP

Ini adalah aplikasi sistem member sederhana yang dibangun menggunakan PHP, MySQL, dan Bootstrap 5.

## Fitur

### Panel Admin
- Manajemen CRUD untuk Pengguna (Member)
- Manajemen CRUD untuk Produk
- Validasi Donasi (Approve/Reject)
- Dashboard dengan statistik total

### Panel Pengguna
- Melihat dan membuat donasi
- Mengedit/menghapus donasi yang masih pending
- Mengunduh produk jika donasi telah divalidasi dan memenuhi syarat

## Prasyarat

- Web Server (contoh: Apache)
- PHP (versi 7.4 atau lebih baru)
- MySQL / MariaDB

## Cara Instalasi

1.  **Clone atau Unduh Repositori**
    - Unduh file zip dan ekstrak ke direktori `htdocs` (untuk XAMPP) atau `www` (untuk WAMP) Anda.

2.  **Buat Database**
    - Buka phpMyAdmin atau klien database pilihan Anda.
    - Buat database baru dengan nama `member_system`.
    - Pilih database `member_system`, lalu impor file `db/database.sql` yang ada di dalam proyek ini. Ini akan membuat semua tabel yang diperlukan dan menyisipkan beberapa data contoh.

3.  **Konfigurasi Koneksi Database**
    - Buka file `config/database.php`.
    - Sesuaikan nilai `DB_SERVER`, `DB_USERNAME`, `DB_PASSWORD`, dan `DB_NAME` jika berbeda dengan pengaturan default Anda.
    ```php
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'member_system');
    ```

4.  **Jalankan Aplikasi**
    - Buka browser Anda dan navigasikan ke direktori proyek. Contoh: `http://localhost/nama_folder_proyek/auth/login.php`

## Akun Demo

Anda dapat menggunakan akun berikut untuk login:

-   **Admin**
    -   Username: `admin`
    -   Password: `password123`

-   **Member**
    -   Username: `member`
    -   Password: `password123`

Catatan: Password di database di-hash menggunakan bcrypt. Gunakan `password123` untuk login.
