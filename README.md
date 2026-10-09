# Undangan Digital

Starter undangan pernikahan berbasis Laravel dan MySQL. Tamu dapat membuka undangan personal, melihat detail acara, mengirim RSVP, dan meninggalkan ucapan. RSVP disimpan di MySQL; notifikasi ke WhatsApp owner dapat diaktifkan menggunakan WhatsApp Business Cloud API.

## Menjalankan di Laragon

1. Jalankan layanan MySQL dari Laragon dan buat database `undangan_digital` (collation `utf8mb4_unicode_ci`).
2. Periksa konfigurasi database pada `.env`. Nilai bawaan ditujukan untuk instalasi Laragon lokal (`root`, tanpa password); sesuaikan jika kredensial Anda berbeda.
3. Jalankan `php artisan migrate`.
4. Jalankan `php artisan serve`, lalu buka `http://localhost:8000`.

Data contoh pasangan dan acara dapat disesuaikan di `config/invitation.php`.

## Tampilan dan personalisasi visual

Halaman undangan memakai font Cormorant Garamond, Great Vibes, dan DM Sans dari Google Fonts; koneksi internet diperlukan agar font tersebut dapat dimuat, dengan font sistem sebagai cadangan. Ornamen bunga dan ilustrasi sampul merupakan SVG orisinal. Galeri masih memakai placeholder dekoratif—ganti dengan foto milik sendiri atau foto yang Anda punya izin untuk gunakan saat mempersonalisasi halaman di `resources/views/invitation.blade.php`.

## Notifikasi WhatsApp

Integrasi ini mengirim **pesan template keluar** dari WhatsApp Business Cloud API ke nomor owner. Ini bukan webhook WhatsApp masuk. Buat dan ajukan persetujuan template berbahasa Indonesia bernama `rsvp_notification` dengan empat placeholder isi pesan, lalu isi variabel berikut di `.env`:

- `WHATSAPP_PHONE_NUMBER_ID`: ID nomor telepon dari Meta WhatsApp Cloud API.
- `WHATSAPP_TOKEN`: access token API; jangan masukkan token ke source control.
- `WHATSAPP_OWNER_PHONE`: nomor owner dalam format internasional tanpa tanda `+` (contoh: `6281234567890`).
- `WHATSAPP_TEMPLATE_NAME` dan `WHATSAPP_TEMPLATE_LANGUAGE`: nama serta bahasa template yang disetujui.

Susunan placeholder template yang dikirim: nama tamu, status kehadiran, jumlah tamu, dan ucapan. Setelah konfigurasi lengkap, jalankan worker agar antrean notifikasi diproses:

```sh
php artisan queue:work --queue=whatsapp,default
```

Worker perlu tetap berjalan. Percobaan yang gagal akan dicoba ulang hingga tiga kali dan kegagalan permanen tercatat pada tabel `failed_jobs`.

## Personalisasi tautan undangan

Nama tamu dapat ditampilkan pada sampul melalui query `to`, misalnya:

```text
http://localhost:8000/?to=Bapak%20Budi
```

RSVP membatasi frekuensi pengiriman per IP dan memvalidasi nama, status kehadiran, jumlah tamu, serta panjang ucapan di server.
