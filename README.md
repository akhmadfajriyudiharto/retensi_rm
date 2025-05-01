# Vuexy Laravel Admin Template - Instalasi

Panduan ini menjelaskan langkah-langkah instalasi dan konfigurasi template admin Vuexy dengan Laravel.

## Persyaratan Sistem

Pastikan sistem Anda memenuhi persyaratan berikut:

- Node: v18.12.0 atau lebih tinggi (LTS)
- PHP: v8.2.0 atau lebih tinggi
- Composer: v2.2 atau lebih tinggi
- Laravel: v11.0.0 atau lebih tinggi

Important for mix!
Versi Mix sudah tidak berlaku lagi dan kami akan menghapus paket Mix dan dokumentasi Mix dalam 3 hingga 6 bulan dari sekarang!!
Jika Anda memutuskan untuk menggunakan npm, pastikan Anda menggunakan perintah berikut: npm install --legacy-peer-deps

## Langkah Instalasi

Untuk memigrasikan versi terbaru laravel, ikuti langkah-langkah Langkah-Langkah Migrasi Laravel lalu lanjutkan dengan instalasi.

Menginstal dan menjalankan Vuexy sangat mudah, silakan ikuti langkah-langkah di bawah ini dan Anda akan siap untuk memulai

### 1. Buka terminal di direktori root Vuexy Laravel Anda.

### 2. Gunakan perintah berikut untuk menginstal composer

```bash
composer install
```

### 3. Temukan file .env.example di folder root dan salin ke .env dengan menjalankan perintah di bawah ini Atau juga dapat menyalinnya secara manual (jika tidak memiliki file .env):

```bash
cp .env.example .env
```

### 4. Jalankan perintah berikut untuk menghasilkan key

```bash
php artisan key:generate
```

### 5. Dengan menjalankan perintah berikut, Anda akan bisa mendapatkan semua dependensi di folder node_modules Anda:

```bash
yarn
```

### 6. Untuk memulai server pengembangan, Perintah ini akan membangun aset frontend Anda dengan template:

Informasi untuk vite:
Anda dapat menggunakan Vite dalam dua mode:

Mode Pengembangan: Jalankan <b>yarn dev</b> untuk memulai server lokal dengan hot-reloading, ideal untuk membuat dan melihat pratinjau perubahan secara real-time.
Build Produksi: Jalankan <b>yarn build</b> untuk menggabungkan dan mengoptimalkan aset untuk penyebaran, mempersiapkan aplikasi Anda untuk produksi.

```bash
yarn build
```

### 7. Untuk menjalankan aplikasi, Anda perlu menjalankan perintah berikut di direktori proyek

```bash
php artisan serve
```

### 8. Sekarang navigasikan ke alamat yang diberikan, Anda akan melihat aplikasi Anda sedang berjalan.🥳

Untuk mengubah alamat port, jalankan perintah berikut:

```bash
php artisan serve --port=8080 // For port 8080
```

## Untuk melakukan integrasi dengan migrasi basis data:#

### 1. Anda harus memiliki basis data atau membuat basis data baru untuk melanjutkan lebih lanjut. Temukan informasi selengkapnya tentang koneksi basis data di sini

### 2. Anda harus menetapkan kredensial basis data Anda dalam file .env.

Untuk mengubah alamat port, jalankan perintah berikut:

```bash
DB_CONNECTION = mysql
DB_HOST = 127.0.0.1
DB_PORT = 3306
DB_DATABASE = DATABASE_NAME
DB_USERNAME = DATABASE_USERNAME
DB_PASSWORD = DATABASE_PASSWORD
```

### 3. Jalankan perintah berikut untuk membuat tabel migrasi.

```bash
php artisan migrate
```

### 4. Jalankan perintah berikut untuk membuat tabel migrasi baru. (Ini akan menghapus tabel Anda saat ini dari Basis Data dan membuat yang baru.)

```bash
php artisan migrate:fresh
```

### 5. Jalankan perintah penyemaian basis data berikut untuk membuat pengguna palsu dalam basis data untuk aplikasi CRUD.

```bash
php artisan db:seed
```

### 6. Anda siap menggunakan aplikasi CRUD 👍🏻
