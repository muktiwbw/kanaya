# Kanaya Kebaya

## Langkah memulai:
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
