# Sistem Antrian Real-Time — Design Spec

## Overview

Sistem Antrian Digital Real-Time menggunakan SSE (Server-Sent Events) untuk menyinkronkan informasi antrian antar 3 role: Guest, Admin, dan Papan Antrian.

## Keputusan Desain

| Keputusan | Pilihan |
|---|---|
| Storage | Database (persistent) |
| Nomor antrian | Auto-increment, tanpa reset harian |
| Auth admin | Pakai sistem auth yang sudah ada |
| Audio | Web Speech API + file dingdong.mp3 |
| Real-time | Native SSE Stream (`StreamedResponse`) |

## Database

### Tabel `antrian`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key, auto-increment |
| `nomor_antrian` | int | Nomor urut (auto-increment tanpa reset) |
| `nama` | string | Nama guest |
| `status` | enum('menunggu','dipanggil','selesai','terlewat') | Status antrian |
| `called_at` | timestamp, nullable | Waktu dipanggil |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

`nomor_antrian` terus increment tanpa reset — nilai sama dengan `id` atau di-generate dari counter sederhana.

## Routes

| Method | Path | Auth | Deskripsi |
|---|---|---|---|
| GET | `/guest` | No | Halaman form pendaftaran guest |
| POST | `/antrian` | No | Simpan antrian baru |
| GET | `/antrian/{id}` | No | Tiket antrian personal guest |
| GET | `/admin` | Yes | Dashboard admin antrian |
| POST | `/antrian/{id}/panggil` | Yes | Panggil nomor antrian |
| POST | `/antrian/{id}/selesai` | Yes | Tandai selesai |
| POST | `/antrian/{id}/terlambat` | Yes | Tandai terlambat |
| GET | `/sse/antrian` | No | SSE stream endpoint |

## Controller: AntrianController

| Method | Action | Deskripsi |
|---|---|---|
| `index()` | guest form | Tampilkan halaman pendaftaran |
| `store()` | buat antrian | Simpan ke DB, redirect ke tiket di tab baru |
| `show($id)` | tiket | Tampilkan nomor + nama (read-only) |
| `admin()` | dashboard | List antrian menunggu/dipanggil/terlewat |
| `panggil($id)` | panggil | Update status=dipanggil, set called_at, update cache |
| `selesai($id)` | selesai | Update status=selesai |
| `terlambat($id)` | terlambat | Update status=terlewat |
| `streamSse()` | SSE stream | `StreamedResponse` loop, broadcast perubahan |

## SSE Stream

- `StreamedResponse` dengan header: `Content-Type: text/event-stream`, `X-Accel-Buffering: no`, `Cache-Control: no-cache`
- `set_time_limit(0)` di awal method
- Loop `while(true)` dengan `sleep(1)`
- Setiap iterasi cek last update timestamp via Laravel Cache
- Jika ada perubahan, kirim JSON event: `{ action, antrian }`
- Event types: `antrian_baru`, `antrian_dipanggil`, `antrian_selesai`, `antrian_terlewat`

Cache key: `antrian_last_update` (timestamp) + `antrian_latest_event` (JSON payload).

## Frontend

### Guest Form (`/guest`)
- Input nama, submit via form POST
- Setelah submit: buka tab baru (`window.open`) menampilkan tiket
- Tab asli tetap di form (bisa daftar lagi)

### Tiket Guest (`/antrian/{id}`)
- Tampilkan nomor antrian (besar) + nama
- SSE connection untuk update status real-time
- Ketika status berubah ke `dipanggil`, tampilkan notifikasi visual

### Admin Dashboard (`/admin`)
- 3 section: Menunggu, Sedang Dipanggil, Terlambat
- Tabel antrian per section dengan tombol aksi
- Tombol "Panggil" pada antrian menunggu
- Tombol "Selesai" pada antrian dipanggil
- Tombol "Panggil Ulang" pada antrian terlambat
- Semua update via AJAX + SSE refresh

### Papan Antrian (`/papan`)
- Layout besar, mudah dibaca dari jarak jauh
- Nomor antrian yang sedang dipanggil (sangat besar)
- Nama yang dipanggil
- Daftar antrian menunggu (sidebar)
- Tombol "Aktifkan Suara" (untuk user gesture policy)
- Audio: `dingdong.mp3` di `public/audio/`
- Web Speech API: "Nomor antrian {x}, {nama}. Silakan masuk."

### Audio Flow
1. SSE event `antrian_dipanggil` diterima
2. Play `dingdong.mp3`
3. Setelah audio selesai (`onended`), speak teks via Web Speech API
4. Bahasa: `id-ID`, rate: 0.85, pitch: 1.0

## Alur Lengkap

1. Guest buka `/guest`, masukkan nama, submit
2. Server simpan ke DB (status=menunggu), update cache
3. Redirect ke `/antrian/{id}` di tab baru
4. Admin di `/admin` melihat antrian baru via SSE
5. Admin klik "Panggil" → POST → server update status=dipanggil
6. SSE broadcast ke semua client
7. Papan antrian terima event, update tampilan, play suara
8. Jika guest tidak hadir, admin klik "Terlambat" → status=terlewat
9. Admin bisa "Panggil Ulang" dari list terlambat
