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
Halaman awal.

### 2. Login Admin - localhost:8000/admin/login
Halaman login khusus untuk user admin (Super Admin dan Admin lainnya). Setelah pada proses installasi menjalankan command ```php artisan db:seed``` maka sudah dibuatkan user SUPER ADMIN. Untuk login ketikkan email ```superadmin``` dan password ```superadmin```.

### 3. List Produk - localhost:8000/admin/product
Halaman list produk. Berisi tabel produk yang terdiri dari 5 item di setiap halamannya.

### 4. Menambahkan Produk Baru - localhost:8000/admin/product/new
Halaman ini untuk menambahkan data produk baru. Isi informasi dasar produk (kecuali Code, karena otomatis ter-generate sesuai urutan produk pada database). User juga dapat menambahkan foto produk dengan mengklik tombol "Choose files" dan memilih 1 atau lebih gambar untuk diupload. Gambar yang diupload akan tersimpan di /public/img/products dengan nama sesuai dengan id produk dan urutan gambar pada produk tersebut.

### 5. Edit Produk - localhost:8000/admin/product/id_produk
Halaman untuk menampilkan detail produk beserta mengedit detail produk.

#### Terdapat beberapa fitur pada halaman ini, diantaranya:
**1. Edit detail produk:** Mengupdate detail produk yang berupa info teks (nama, harga, stok, dll).

**2. Hapus foto produk:** Dengan mengklik foto produk sampai muncul bingkai merah, lalu klik Update. Gambar akan terhapus dari data produk sekaligus menghapus file pada direktori /public/img/products.

**3. Menambahkan beberapa foto baru:** Dengan mengklik tombol "Choose files" maka user dapat memilih 1 atau lebih gambar untuk diupload dan dijadikan foto produk yang bersangkutan.

**4. Menghapus data produk:** Menghapus data produk beserta foto-foto dari produk yang bersangkutan yang ada di direktori /public/img/products.
