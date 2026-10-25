# php-login-page-simple
A simple login page with PDO file based and combined with bootstrap :D

## Menjalankan aplikasi

Jalankan `make dev`, lalu buka `http://localhost:8000`. Database SQLite dan tabel pengguna akan disiapkan otomatis.

Mode debug aktif saat memakai target `dev`: pesan teknis ditampilkan pada halaman error dan detail exception dicatat ke log server. Di luar mode development, biarkan `APP_DEBUG` tidak disetel agar detail internal tidak ditampilkan ke pengguna.

## Cara kerja token CSRF

Form login, daftar, dan keluar memiliki token rahasia di dalam kolom tersembunyi. Saat halaman dibuka, aplikasi membuat token dan menyimpannya di session pengguna. Saat form dikirim, aplikasi membandingkan token dari form dengan token di session. Jika tidak cocok atau tidak ada, aplikasi menolak permintaan dengan status `403 Forbidden`.

Token ini membantu memastikan aksi memang dikirim dari form aplikasi kita. Browser tidak menambahkan token tersembunyi itu secara otomatis ketika situs lain mencoba mengirim permintaan.
