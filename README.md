# PHP-Assessment-Magdalisa

## Menajalankan BE ASSIGNMENT 1
Proyek ini bertujuan untuk mengembangkan aplikasi PHP menggunakan Laravel 11 dan MySQL. Di dalamnya juga akan diinstal Laravel Telescope untuk memonitor aktivitas aplikasi. Folder terdapat di Implementation-test di folde be-1.

## Prasyarat

Sebelum memulai, pastikan Anda telah menginstal perangkat lunak berikut:

- PHP >= 8.1
- Composer
- MySQL

## Instalasi

Ikuti langkah-langkah berikut untuk menginstal Laravel 11 dan Laravel Telescope:

### 1. Instal Dependensi

Jalankan perintah berikut untuk menginstal dependensi PHP menggunakan Composer:
```bash
composer install
```

### 2. Generate Application Key
Jalankan perintah berikut untuk menghasilkan application key:
```bash
php artisan key:generate
```

### 3.Migrasi Database
Jalankan perintah berikut untuk melakukan migrasi database:
```bash
php artisan migrate
```

### 4.Jalankan Server
Jalankan perintah berikut untuk memulai server pengembangan Laravel:
```bash
php artisan serve
```

### 5.Akses Laravel Telescope
Anda dapat mengakses Laravel Telescope di
```bash
 http://localhost:8000/telescope
```

### 4.Jalankan Unit test
Jalankan perintah berikut untuk memulai unit test pengembangan Laravel:
```bash
php artisan test
```



