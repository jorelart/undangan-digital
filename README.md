# Undangan Pernikahan

Frontend undangan, Laravel 12, API webhook, dan database MySQL berada dalam
satu project ini. Halaman utama dapat dibuka tanpa token. Tautan pribadi yang
dihasilkan API memakai `#token=...`; nama penerima sampul mengikuti data tamu
di database. Token di fragment tidak dikirim browser pada request halaman.
Token hilang/tidak dikenal tetap menampilkan undangan umum.

## Menjalankan secara lokal

1. Nyalakan MySQL Laragon.
2. Pastikan database `invitation` sudah ada. Database ini sudah dibuat untuk
   instalasi lokal ini.
3. Dari folder project, jalankan:

   ```powershell
   php artisan serve
   ```

4. Buka `http://127.0.0.1:8000/`.

Route utama dirender sebagai komponen halaman penuh Livewire 4 dari
`resources/views/components/⚡home.blade.php` dengan layout utama
`resources/views/layouts/app.blade.php`. Section undangan tetap tersusun dari
partial di `resources/views/partials/invitation/`, sedangkan metadata, aset,
dan scripts dimuat melalui layout tersebut. Personalisasi nama tamu membaca
token undangan dari fragment URL melalui JavaScript; asset tetap dilayani
dari `public/assets/`.

Jika URL berubah, sesuaikan `APP_URL` di `.env`. Untuk webhook WhatsApp yang
berjalan di luar komputer ini, `APP_URL` harus memakai domain HTTPS publik.

## API webhook WhatsApp

Semua endpoint API memerlukan header:

```http
Authorization: Bearer <WHATSAPP_WEBHOOK_TOKEN>
Accept: application/json
```

Atur `WHATSAPP_WEBHOOK_TOKEN` dengan nilai acak yang kuat di `.env`. Contoh
menghasilkan nilai acak:

```powershell
php -r "echo bin2hex(random_bytes(32)), PHP_EOL;"
```

Setelah mengubah `.env`, jalankan `php artisan config:clear`. Jangan masukkan
secret ke source control. Endpoint:

| Method | Endpoint | Fungsi |
| --- | --- | --- |
| `GET` | `/api/invitation` | Resolve token undangan dari header Bearer untuk menampilkan nama penerima. |
| `POST` | `/api/v1/guests` | Membuat tamu dari nama, nomor WhatsApp, dan batas jumlah tamu; menghasilkan token serta tautan undangan. |
| `GET` | `/api/v1/guests` | Daftar tamu berpaginasi (`per_page`, maksimal 100). |
| `GET` | `/api/v1/guests?q=Ayu` | Cari nama atau nomor tamu. |
| `GET` | `/api/v1/guests?phone=6281234567890` | Cari nomor WhatsApp yang dinormalisasi. |
| `GET` | `/api/v1/guests/{id}` | Ambil detail tamu dan RSVP. |
| `POST` | `/api/v1/guests/{id}/rsvp` | Simpan atau perbarui RSVP tamu. |
| `POST` | `/api/v1/guests/{id}/rsvp/reply` | Simpan balasan pemilik (`owner_reply`) untuk RSVP tamu. |

Gunakan URL dasar server yang sedang berjalan. Contoh untuk pengembangan lokal:

```powershell
$tokenLine = Get-Content .env | Where-Object { $_ -like 'WHATSAPP_WEBHOOK_TOKEN=*' }
$token = $tokenLine -replace '^WHATSAPP_WEBHOOK_TOKEN=', ''
$headers = @{
    Authorization = "Bearer $token"
    Accept = "application/json"
}
Invoke-RestMethod http://127.0.0.1:8000/api/v1/guests -Headers $headers
Invoke-RestMethod "http://127.0.0.1:8000/api/v1/guests?q=Ayu" -Headers $headers
```

Contoh membuat tamu:

```json
{
  "name": "Ayu Putri",
  "phone": "+62 812-3456-7890",
  "max_guests": 2
}
```

Nomor telepon disimpan sebagai digit saja, misalnya `6281234567890`; nomor
duplikat ditolak. `invitation_token` unik disimpan di database dan respons pembuatan tamu
menyertakan token serta `invitation_url` untuk dikirim dari webhook WhatsApp.
Endpoint daftar dan detail tidak membocorkan token.

Contoh memperbarui RSVP:

```json
{
  "presence": "hadir",
  "guest_count": 2,
  "message": "Insyaallah hadir."
}
```

`presence` menerima `hadir` atau `tidak`. `guest_count` wajib diisi bila hadir
dan tidak boleh melampaui batas pada undangan.

Contoh membalas ucapan RSVP sebagai pemilik:

```json
{
  "owner_reply": "Terima kasih atas doa dan ucapannya."
}
```

Kirim objek tersebut ke `POST /api/v1/guests/{id}/rsvp/reply`. Halaman
undangan menampilkan satu ucapan yang memiliki balasan dan satu ucapan tanpa
balasan secara acak, jika masing-masing tersedia di database.

Project ini belum mempunyai dashboard admin; daftar dan pengelolaan data
dilakukan lewat API yang dilindungi secret webhook.

## Tes

```powershell
php artisan test
```
