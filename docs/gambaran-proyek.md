# Kenalan dengan Simple Login

Dokumen ini menjelaskan isi proyek, cara kerjanya, dan ide untuk mengembangkannya. Bahasanya sengaja dibuat santai supaya teman yang baru belajar PHP juga bisa mengikuti.

## Proyek ini tentang apa?

Simple Login adalah aplikasi web sederhana untuk membuat akun dan masuk. Aplikasi memakai PHP untuk mengolah halaman dan SQLite untuk menyimpan data pengguna.

Alurnya seperti ini:

1. Pengguna membuka halaman masuk atau daftar.
2. Pengguna mengisi form.
3. PHP memeriksa data yang dikirim.
4. Aplikasi membaca atau menyimpan data di database.
5. Jika berhasil, aplikasi membawa pengguna ke dashboard.

## Teknologi yang dipakai

- **PHP** mengatur halaman, form, dan proses login.
- **HTML** membentuk isi halaman dan form.
- **Bootstrap** membantu mengatur tata letak dan komponen dashboard.
- **HTMX** memuat konten fitur dashboard tanpa memuat ulang seluruh halaman.
- **CSS** mengatur detail tampilan yang khusus untuk proyek ini.
- **SQLite** menyimpan akun di sebuah file database.
- **PDO** menjadi cara PHP berbicara dengan database.
- **Session** mengingat pengguna yang sudah masuk.

## Fitur yang sudah ada

- Pengguna bisa membuat akun dengan nama, email, dan password.
- Aplikasi menyimpan password dalam bentuk hash, bukan teks biasa.
- Pengguna bisa masuk menggunakan email dan password.
- Dashboard hanya bisa dibuka oleh pengguna yang sudah masuk.
- Dashboard memiliki menu Beranda dan Profil yang kontennya dapat dimuat tanpa memuat ulang halaman.
- Pengguna yang sudah masuk dapat membuat tautan ringkas dengan alias otomatis atau alias khusus.
- Form lupa password menyimpan permintaan di mailbox dashboard admin; pengiriman SMTP dan penggantian password belum tersedia.
- Tombol keluar menghapus session login.
- Aplikasi memeriksa token CSRF saat form login, daftar, atau keluar dikirim.
- Aplikasi menolak metode request yang tidak diizinkan.
- Pesan error membantu pengguna mengetahui jika proses gagal.

## Peta folder

| Lokasi | Isinya |
| --- | --- |
| `src/app/public/` | Halaman yang dibuka pengguna, seperti login, daftar, dan dashboard |
| `src/app/core/` | Fungsi utama untuk autentikasi, token CSRF, dan pemeriksaan request |
| `src/app/config/` | Pengaturan aplikasi dan koneksi database |
| `src/app/database/` | File database SQLite |
| `src/app/setup_db.php` | Membuat tabel pengguna jika tabel belum ada |
| `Makefile` | Perintah singkat untuk menjalankan aplikasi |

## Cara mencoba aplikasi

1. Pastikan PHP sudah terpasang.
2. Buka terminal di folder utama proyek.
3. Jalankan `make dev`.
4. Buka `http://localhost:8000` di browser.
5. Buat akun, lalu coba masuk dan keluar.

Target `dev` menyalakan mode debug untuk belajar. Jangan menampilkan detail error teknis seperti ini di aplikasi yang sudah dipakai pengguna sungguhan.

## Sedikit tentang keamanan

### Password

Saat pengguna mendaftar, aplikasi membuat hash password sebelum menyimpannya. Saat login, PHP membandingkan password yang diketik dengan hash tadi. Jadi, database tidak perlu menyimpan password asli.

### Token CSRF

Form memiliki token rahasia yang disimpan di session. Saat form dikirim, aplikasi membandingkan token dari form dengan token di session. Kalau token hilang atau berbeda, aplikasi menolak permintaan.

Bayangkan token seperti tiket khusus: form yang benar membawa tiket yang cocok, sedangkan permintaan asing tidak punya tiket yang tepat.

## Ide pengembangan yang realistis

Ide-ide ini cocok dikerjakan bertahap:

1. **Fitur lupa password** — buat alur untuk meminta tautan penggantian password.
2. **Validasi email yang lebih rapi** — cek format email di server dan tampilkan pesan yang mudah dimengerti.
3. **Halaman profil** — izinkan pengguna melihat dan mengubah nama mereka.
4. **Pesan form yang lebih ramah** — pertahankan data yang aman saat form gagal, tanpa pernah mengisi ulang password.
5. **Tes otomatis** — periksa login, pendaftaran, logout, dan token CSRF tanpa menguji semuanya secara manual.
6. **Tampilan ponsel yang lebih nyaman** — cek ukuran tombol, teks, dan form di layar kecil.
7. **Pengaturan produksi** — matikan debug dan gunakan konfigurasi yang sesuai sebelum aplikasi dipasang di server sungguhan.

## Ide liar

Ide ini lebih seru dan mungkin butuh waktu atau teknologi tambahan:

- **Login pakai avatar peliharaan digital** — setiap pengguna merawat makhluk virtual di dashboard.
- **Misi keamanan mingguan** — aplikasi memberi tantangan kecil, seperti membuat password yang kuat.
- **Peta komunitas kelas** — pengguna bisa berbagi proyek, tetapi hanya setelah ada aturan privasi dan izin yang jelas.
- **Tema yang berubah sesuai cuaca** — dashboard menyesuaikan warna berdasarkan cuaca di kota pengguna.
- **Mode detektif bug** — aplikasi memberi petunjuk saat pengguna belajar memperbaiki error latihan.

Ide liar tetap perlu diuji. Sebelum membuatnya, pikirkan manfaat, waktu pengerjaan, keamanan, dan data apa yang perlu dikumpulkan.

## PR (Pekerjaan Rumah)

Pilih satu tugas dulu. Buat perubahan kecil, jalankan aplikasinya, lalu cek hasilnya di browser.

### PR 1 — Tambahkan tombol tampilkan password

- Tambahkan tombol kecil di samping kolom password.
- Saat tombol ditekan, ubah tipe kolom dari `password` menjadi `text`.
- Saat tombol ditekan lagi, sembunyikan password.
- Jangan mengirim atau menyimpan password ke tempat lain.

**Tanda selesai:** pengguna bisa melihat lalu menyembunyikan password tanpa mengirim form.

### PR 2 — Tambahkan batas panjang nama pengguna

- Tentukan panjang nama minimum dan maksimum.
- Periksa panjangnya di PHP, bukan hanya di browser.
- Tampilkan pesan yang jelas jika nama terlalu pendek atau panjang.

**Tanda selesai:** nama yang tidak sesuai aturan tidak masuk ke database.

### PR 3 — Tambahkan sapaan waktu di dashboard

- Tampilkan sapaan pagi, siang, sore, atau malam.
- Gunakan waktu dari PHP.
- Pastikan sapaan tetap tampil benar jika jam berubah.

**Tanda selesai:** sapaan berubah sesuai waktu saat halaman dibuka.

### PR 4 — Buat tes untuk token CSRF

- Tes bahwa token baru berhasil dibuat.
- Tes bahwa token yang cocok diterima.
- Tes bahwa token yang salah atau kosong ditolak.

**Tanda selesai:** semua tes bisa dijalankan lagi setelah kode berubah dan hasilnya tetap benar.

## Urutan belajar yang disarankan

Mulai dari PR 1 atau PR 3 karena keduanya cocok untuk latihan dasar. Setelah itu coba PR 2 untuk belajar validasi di server, lalu PR 4 untuk mulai mengenal pengujian dan keamanan.

Tidak perlu mengerjakan semua ide sekaligus. Pilih satu, pahami cara kerjanya, lalu lanjut ke ide berikutnya.
