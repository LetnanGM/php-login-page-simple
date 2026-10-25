# php-login-page-simple
A simple login page with PDO file based and combined with bootstrap :D

## Menjalankan aplikasi

Jalankan `make dev`, lalu buka `http://localhost:8000`. Database SQLite dan tabel pengguna akan disiapkan otomatis.

Mode debug aktif saat memakai target `dev`: pesan teknis ditampilkan pada halaman error dan detail exception dicatat ke log server. Di luar mode development, biarkan `APP_DEBUG` tidak disetel agar detail internal tidak ditampilkan ke pengguna.

## Cara kerja token CSRF

Form login, daftar, dan keluar memiliki token rahasia di dalam kolom tersembunyi. Saat halaman dibuka, aplikasi membuat token dan menyimpannya di session pengguna. Saat form dikirim, aplikasi membandingkan token dari form dengan token di session. Jika tidak cocok atau tidak ada, aplikasi menolak permintaan dengan status `403 Forbidden`.

Token ini membantu memastikan aksi memang dikirim dari form aplikasi kita. Browser tidak menambahkan token tersembunyi itu secara otomatis ketika situs lain mencoba mengirim permintaan.

## Demo lupa password dan mailbox admin

Form `/forgot.php` menyimpan permintaan pemulihan ke tabel `password_reset_requests`. Permintaan muncul pada menu **Mailbox** di dashboard admin. Fitur demo ini belum mengirim email lewat SMTP dan belum mengganti password; admin harus menindaklanjuti permintaan di luar aplikasi. Permintaan dengan alamat email yang sama dibatasi satu kali per 15 menit.

Role admin diberikan langsung melalui SQLite oleh pengelola aplikasi, bukan lewat form pendaftaran. Setelah menjalankan `make dev`, ubah alamat akun yang dituju:

```sql
UPDATE users SET role = 'admin' WHERE email = 'admin@example.com';
```

Pengguna harus masuk kembali agar role admin dimuat ke session. Untuk mengembalikan akun menjadi pengguna biasa, ubah nilai role menjadi `user`.

## Pemendek URL

Pengguna yang sudah masuk dapat membuka menu **Pemendek URL** di dashboard untuk membuat tautan dengan alias otomatis atau alias khusus. Tautan yang dibuat mengarah ke `/go.php?code=...` dan tujuan aslinya disimpan di SQLite.
