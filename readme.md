# Kanaya Kebaya

## Langkah Installasi:
1. Clone atau download zip file
1. Jalankan XAMPP
1. Buka PHPMyAdmin, buat database baru dengan nama terserah (contoh: kanaya)
1. Buka folder file yang sudah didownload
1. Copy .env-example di tempat yang sama, lalu rename dengan nama .env
1. Buka file .env, ganti value DB_DATABASE dengan nama database yang baru saja dibuat di PHPMyAdmin
1. Sesuaikan DB_USERNAME dan DB_PASSWORD juga jika pernah diganti. Kalau tidak, biarkan saja
1. Save file .env
1. Buka CMD pada direktori folder
1. Jalankan command ```composer install```
1. Setelah selesai jalankan command ```php artisan key:generate```
1. Untuk migrasi database, jalankan command ```php artisan migrate``` 
1. Jalankan ```php artisan db:seed``` untuk membuat user SUPER ADMIN
1. Untuk menjalankan web server, jalankan command ```php artisan serve```

## List Route:
### 1. Home - localhost:8000/
Halaman awal

### 1. Login Admin - localhost:8000/admin/login
Halaman login khusus untuk user admin (Super Admin dan Admin lainnya)
