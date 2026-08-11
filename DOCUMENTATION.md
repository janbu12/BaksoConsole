# 📚 Dokumentasi Lengkap Aplikasi Bakso Console
### *Smart PlayStation Console Rental & Operations Hub (Standar Uji Kompetensi BNSP)*

---

## 📑 Daftar Isi
1. [Tentang Aplikasi](#1-tentang-aplikasi)
2. [Arsitektur & Tech Stack](#2-arsitektur--tech-stack)
3. [Skema Basis Data & Entitas](#3-skema-basis-data--entitas)
4. [Panduan Instalasi & Setup](#4-panduan-instalasi--setup)
5. [Akun Pengguna Default (Seeder)](#5-akun-pengguna-default-seeder)
6. [Alur Bisnis & Fitur Aplikasi (End-to-End Workflow)](#6-alur-bisnis--fitur-aplikasi-end-to-end-workflow)
7. [Integrasi Payment Gateway Midtrans & Simulasi](#7-integrasi-payment-gateway-midtrans--simulasi)
8. [Pemantauan Resource & Server Monitor Real-Time](#8-pemantauan-resource--server-monitor-real-time)
9. [Daftar Lengkap Routing (Routes Map)](#9-daftar-lengkap-routing-routes-map)
10. [Matriks Kesesuaian Persyaratan BNSP](#10-matriks-kesesuaian-persyaratan-bnsp)

---

## 1. 📖 Tentang Aplikasi

**Bakso Console** adalah sistem informasi manajemen rental konsol PlayStation (PS5, PS4, PS3) berbasis web yang dirancang khusus untuk memenuhi standar sertifikasi kompetensi keahlian **BNSP (Badan Nasional Sertifikasi Profesi)** skema *Junior Web Programmer / Web Developer*.

Aplikasi ini mencakup operasional rental hulu ke hilir:
- Rekomendasi pintar konsol (**Bakso SmartPick Algorithm**).
- Manajemen perangkat keras (**Hardware S/N, Model Rangka, Auto-Increment Code, Tipe Firmware Original vs Jailbreak**).
- Master game dan kustomisasi game pesanan penyewa.
- Layanan logistik terpadu (**Pickup di Toko & Antar-Jemput Kurir**).
- Payment gateway multi-channel (**Midtrans Snap Hosted Payment & Mode Simulasi Instan**).
- Cetak Invoice & Laporan Keuangan format **PDF**.
- Gamifikasi loyalitas pelanggan (**Bakso Rank & Leaderboard**).
- Pemantauan performa server real-time (**Live CPU, RAM, Disk, DB Latency Monitor**).

---

## 2. 🛠️ Arsitektur & Tech Stack

| Komponen | Teknologi | Keterangan |
|---|---|---|
| **Backend Framework** | **Laravel 11 / 13** | PHP ^8.2 / ^8.3 dengan arsitektur Domain-Driven & Action Layer |
| **Frontend Styling** | **Tailwind CSS & Vanilla CSS** | Tampilan Dark Mode modern bernuansa *gaming lounge* |
| **Interaktivitas UI** | **Alpine.js** | Reaktivitas dinamis, webcam capture, dan live polling 3 detik |
| **Basis Data** | **MySQL / SQLite** | Relasi antar tabel ternormalisasi dengan foreign keys cascade |
| **Payment Gateway** | **Midtrans Snap API** | Mendukung QRIS, GoPay, ShopeePay, Virtual Account, Kartu Kredit |
| **PDF Generation** | **DomPDF (`dompdf/dompdf`)** | Render invoice digital dan laporan cetak rekapitulasi admin |
| **Automated Testing** | **Pest PHP & PHPUnit** | 46 Automated Feature & Unit Tests (100% Passing) |

---

## 3. 🗄️ Skema Basis Data & Entitas

```
                       ┌────────────────┐
                       │     users      │
                       └───────┬────────┘
                               │ 1:1
                               ▼
                       ┌────────────────┐
                       │ user_profiles  │
                       └────────────────┘
                               │
            ┌──────────────────┴──────────────────┐
            ▼ 1:M                                 ▼ 1:M
    ┌───────────────┐                     ┌───────────────┐
    │   bookings    │                     │    rentals    │
    └───────┬───────┘                     └───────┬───────┘
            │                                     │
            │ N:1                                 ├──────────► 1:1  ┌───────────────┐
            ▼                                     │                 │ transactions  │
    ┌───────────────┐                             ├──────────► 1:M  └───────────────┘
    │     units     │◄────────────────────────────┤                 ┌───────────────┐
    └───┬───────┬───┘                             ├──────────► 1:M  │  deliveries   │
        │       │                                 │                 └───────────────┘
    N:M │       │ N:M                             ├──────────► 1:M  ┌───────────────┐
        ▼       ▼                                 │                 │  extensions   │
┌──────────┐ ┌─────────┐                          └──────────► 1:M  └───────────────┘
│categories│ │  games  │                                            ┌───────────────┐
└──────────┘ └─────────┘                                            │     fines     │
                                                                    └───────────────┘
```

### Penjelasan Tabel Utama:
1. **`users` & `user_profiles`**: Menyimpan kredensial (`admin` / `user`), foto avatar (kamera selfie), kontak WhatsApp, dan alamat.
2. **`units`**: Menyimpan identitas konsol (`code` format auto-increment `PS5-001`, `serial_number`, `model_number`, `firmware_type` [original/jailbreak], `daily_price`, `max_players`, `status`).
3. **`games` & `game_unit`**: Master katalog game terpasang pada masing-masing unit (*Assassin's Creed, The Warriors, F1 24, eFootball, GTA V, dll.*).
4. **`categories` & `category_unit`**: Relasi banyak-ke-banyak kategori konsol (*PlayStation 5, Multiplayer, Family, Mabar, dll.*).
5. **`combos`**: Paket bundling rental hemat.
6. **`bookings`**: Reservasi awal pelanggan, mencakup `requested_games` dan preferensi pengiriman.
7. **`rentals`**: Transaksi peminjaman aktif dengan batas aturan (maksimal 2 unit per anggota, maksimal durasi sewa 5 hari).
8. **`transactions`**: Pembukuan tagihan invoice, status lunas, diskon promo, denda, dan ongkos kirim.
9. **`deliveries`**: Pelacakan kurir logistik antar dan jemput unit (`waiting` ➔ `in_transit` ➔ `received`).
10. **`rental_extensions`**: Pengajuan perpanjangan sewa harian.
11. **`fines`**: Rekap denda keterlambatan (Rp 20.000/hari) dan kerusakan fisik/aksesoris.

---

## 4. 🚀 Panduan Instalasi & Setup

### Prasyarat Sistem:
- PHP >= 8.2 (dengan ekstensi `pdo`, `mbstring`, `openssl`, `curl`, `gd` aktif).
- Composer 2.x
- Node.js >= 18.x & NPM
- Database MySQL / MariaDB (atau SQLite bawaan).

### Langkah Instalasi:

1. **Clone Repository**:
   ```bash
   git clone https://github.com/janbu12/BaksoConsole.git
   cd BaksoConsole
   ```

2. **Instal Dependensi PHP & Node**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment (`.env`)**:
   Salin file template `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database di `.env`**:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=bakso_console
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Konfigurasi Payment Gateway Midtrans (Opsional)**:
   ```env
   MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxxx
   MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxxx
   MIDTRANS_IS_PRODUCTION=false
   MIDTRANS_MERCHANT_ID=
   ```
   > *Catatan: Jika `MIDTRANS_SERVER_KEY` dikosongkan, sistem otomatis mengaktifkan mode **Simulasi Pembayaran Instan** tanpa error.*

6. **Migrasi dan Seeding Data**:
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Kompilasi Asset Frontend & Jalankan Server**:
   ```bash
   # Terminal 1: Asset Vite
   npm run dev

   # Terminal 2: Laravel Development Server
   php artisan serve
   ```

8. **Akses Website**:
   Buka browser di `http://localhost:8000`.

---

## 5. 👥 Akun Pengguna Default (Seeder)

Setelah menjalankan `php artisan db:seed`, sistem menyediakan akun siap pakai:

| Role | Email | Password | Hak Akses |
|---|---|---|---|
| **Administrator** | `admin@baksoconsole.test` | `password` | Mengelola semua inventaris unit, verifikasi pembayaran, serah terima, pengembalian, logistik kurir, monitoring resource, dan cetak laporan. |
| **Member (Anggota 1)** | `budi@example.com` | `password` | Melakukan booking konsol, simulasi/Midtrans pay, perpanjangan sewa, request kurir, dan riwayat loyalty rank. |
| **Member (Anggota 2)** | `siti@example.com` | `password` | Akun anggota untuk pengujian multi-user dan leaderboard. |
| **Member (Anggota 3)** | `doni@example.com` | `password` | Akun anggota dengan riwayat rental aktif. |

---

## 6. 🔄 Alur Bisnis & Fitur Aplikasi (End-to-End Workflow)

```
[ Pelanggan ]                                                    [ Administrator ]
      │                                                                  │
      ├── 1. Registrasi / Login Member                                   │
      ├── 2. Eksplorasi Katalog (SmartPick)                              │
      │      - Filter Tipe: Original 🌐 vs Jailbreak 💾                  │
      │      - Filter Game Terpasang                                     │
      ├── 3. Buat Booking / Reservasi                                    │
      │      - Pilih Unit (Maks 2 unit)                                  │
      │      - Tentukan Durasi (Maks 5 hari)                             │
      │      - Pilih Metode Pengiriman (Ambil / Antar Kurir)             │
      │      - Request Game (Jika Konsol Original)                       │
      │                                                                  │
      ▼                                                                  ▼
  [ Invoice Terbit ] ──► Bayar via Midtrans / Simulasi ──► [ Antrean Serah Terima ]
                                                                   │
                                                                   ├── Verifikasi Pembayaran
                                                                   └── Serah Terima Unit (Handover)
                                                                         │
                                                                         ▼
[ Selama Masa Rental ]                                            [ Status: ACTIVE ]
      │
      ├── Pantau Hitung Mundur & Warning Alert
      ├── Opsi Perpanjangan Sewa (+Hari) ─────────────► Review & Approval Admin
      │
      ▼
[ Pengembalian Unit (Return) ]
      │
      ├── Pilih: Antar ke Toko (Gratis) / Jemput Kurir (+Rp 15.000)
      │
      ▼
[ Admin Proses Pengembalian ]
      │
      ├── Cek Keterlambatan (Denda Otomatis Rp 20.000/hari)
      ├── Cek Fisik & Aksesoris (Denda Kerusakan jika ada)
      ├── Konfirmasi Pembayaran Denda & Ongkir Cash
      │
      ▼
[ Selesai & Gamifikasi ]
      │
      ├── Akumulasi Hari Sewa bertambah
      ├── Kenaikan Tier Bakso Rank (Bronze ➔ Bakso Lord)
      └── Masuk Peringkat Leaderboard Member
```

---

## 7. 💳 Integrasi Payment Gateway Midtrans & Simulasi

Aplikasi dilengkapi arsitektur pembayaran adaptif di [`app/Services/MidtransService.php`](file:///e:/Kuliah/Semester%208/BNSP/Program/BaksoConsole/app/Services/MidtransService.php):

1. **Mode Live / Sandbox Midtrans (Snap Redirect URL)**:
   - Jika `MIDTRANS_SERVER_KEY` terisi di `.env`, pelanggan yang memilih pembayaran online akan di-redirect ke Hosted Payment Page Midtrans.
   - Mendukung metode pembayaran: **QRIS (GoPay, OVO, Dana, LinkAja, ShopeePay), Virtual Account (BCA, BNI, BRI, Mandiri, Permata), dan Kartu Kredit**.
   - Dilengkapi penanganan **cURL SSL Verification** yang kompatibel di Windows.
2. **Webhook & HTTP Notification Callback**:
   - Endpoint `POST /midtrans/notification` memverifikasi signature hash SHA512 dan secara otomatis mengubah status transaksi menjadi `Paid` saat status `settlement` atau `capture (accept)`.
3. **Mode Simulasi Instan**:
   - Disediakan tombol **"⚡ Simulasi Bayar Lunas"** untuk mempermudah pengujian offline / demonstrasi di hadapan asesor tanpa perlu transaksi uang nyata.

---

## 8. 📊 Pemantauan Resource & Server Monitor Real-Time

Akses melalui menu: **Admin Operations Hub > [Resource & Server Monitor](http://localhost:8000/admin/resources)**.

Fitur ini membaca metrik server secara live:
- **CPU Usage (%)**: Persentase penggunaan prosesor, jumlah core, dan model CPU (Cross-platform Windows & Linux).
- **System RAM (GB)**: Kapasitas RAM total, terpakai, dan bebas.
- **PHP Process Memory**: Konsumsi memori script aktif, puncak memori (*peak memory*), dan batas memori PHP (*memory limit*).
- **Storage Disk (GB)**: Kapasitas total harddisk, ruang terpakai, dan sisa ruang bebas.
- **Database Ping Latency (ms)**: Waktu respon query roundtrip ke basis data secara real-time.
- **Auto-Refresh Real-Time**: Data diperbarui secara otomatis setiap 3 detik via polling API JSON [`/admin/resources/metrics`](http://localhost:8000/admin/resources/metrics).

---

## 9. 🗺️ Daftar Lengkap Routing (Routes Map)

### A. Rute Publik & Autentikasi
| Method | URL | Nama Rute | Deskripsi |
|---|---|---|---|
| `GET` | `/` | `home` | Landing page publik & showcase konsol |
| `GET` | `/catalogue` | `catalogue` | Katalog konsol & SmartPick recommendation |
| `GET` | `/login` | `login` | Halaman login anggota & admin |
| `POST` | `/login` | - | Proses autentikasi login |
| `GET` | `/register` | `register` | Halaman pendaftaran anggota baru |
| `POST` | `/register` | - | Proses pendaftaran anggota |
| `POST` | `/logout` | `logout` | Keluar dari sesi aplikasi |

### B. Rute Portal Member (Middleware: `auth`)
| Method | URL | Nama Rute | Deskripsi |
|---|---|---|---|
| `GET` | `/dashboard` | `dashboard` | Redirect cerdas ke dashboard sesuai role |
| `GET` | `/bookings` | `bookings` | Daftar reservasi aktif milik member |
| `POST` | `/bookings` | - | Membuat booking / reservasi konsol baru |
| `DELETE`| `/bookings/{booking}` | - | Membatalkan reservasi pending |
| `GET` | `/rentals` | `rentals` | Daftar sewa aktif & tagihan invoice |
| `POST` | `/rentals/{rental}/pay` | `rentals.pay` | Bayar tagihan via Midtrans Snap / Simulasi |
| `POST` | `/rentals/{rental}/simulate-pay` | `rentals.pay.simulate` | Simulasi pembayaran lunas instan |
| `POST` | `/rentals/{rental}/extensions` | - | Mengajukan perpanjangan hari sewa |
| `POST` | `/rentals/{rental}/deliveries` | - | Memilih metode logistik antar/jemput |
| `GET` | `/rentals/{rental}/invoice` | `rentals.invoice.download` | Download invoice resmi format PDF |
| `GET` | `/history` | `history` | Riwayat peminjaman & status Bakso Rank |
| `GET` | `/profile` | `profile` | Edit profil & selfie webcam avatar |
| `PUT` | `/profile` | - | Update data nama, telepon, alamat |
| `POST` | `/profile/avatar` | - | Simpan foto avatar dari kamera |
| `GET` | `/leaderboard` | `leaderboard` | Peringkat loyalitas member |

### C. Rute Midtrans Webhook & Callback
| Method | URL | Nama Rute | Deskripsi |
|---|---|---|---|
| `POST` | `/midtrans/notification` | `midtrans.notification` | Webhook callback status pembayaran Midtrans |
| `POST` | `/midtrans/callback` | - | Alias webhook callback Midtrans |
| `GET` | `/midtrans/finish` | `midtrans.finish` | Return URL setelah transaksi sukses |
| `GET` | `/midtrans/unfinish` | `midtrans.unfinish` | Return URL jika pembayaran belum selesai |
| `GET` | `/midtrans/error` | `midtrans.error` | Return URL jika terjadi kegagalan bayar |

### D. Rute Admin Operations Hub (Middleware: `auth`, `role:admin`)
| Method | URL | Nama Rute | Deskripsi |
|---|---|---|---|
| `GET` | `/admin/dashboard` | `admin.dashboard` | Dashboard analitik, omzet, & heatmap |
| `GET` | `/admin/units` | `admin.units` | Manajemen unit konsol & serial number |
| `POST` | `/admin/units` | - | Tambah unit konsol (auto-increment code) |
| `PUT` | `/admin/units/{unit}` | - | Update spesifikasi, harga, & hardware unit |
| `DELETE`| `/admin/units/{unit}` | - | Hapus data unit konsol |
| `GET` | `/admin/categories` | `admin.categories` | Manajemen kategori & paket bundling combo |
| `POST` | `/admin/categories` | - | Tambah kategori konsol |
| `DELETE`| `/admin/categories/{category}` | - | Hapus kategori |
| `POST` | `/admin/games` | - | Tambah master game |
| `DELETE`| `/admin/games/{game}` | - | Hapus master game |
| `POST` | `/admin/combos` | - | Tambah paket combo bundling |
| `DELETE`| `/admin/combos/{combo}` | - | Hapus paket combo |
| `GET` | `/admin/members` | `admin.members` | Manajemen data akun anggota |
| `POST` | `/admin/members` | - | Tambah akun anggota manual |
| `PUT` | `/admin/members/{member}` | - | Edit akun anggota |
| `DELETE`| `/admin/members/{member}` | - | Hapus akun anggota |
| `GET` | `/admin/bookings` | `admin.bookings` | Antrean serah terima barang (handover) |
| `POST` | `/admin/rentals/{rental}/handover` | - | Konfirmasi serah terima barang |
| `POST` | `/admin/extensions/{extension}` | - | Review & persetujuan perpanjangan sewa |
| `GET` | `/admin/returns` | `admin.returns` | Antrean pengembalian & input denda |
| `POST` | `/admin/rentals/{rental}/return` | - | Proses pengembalian unit konsol |
| `POST` | `/admin/rentals/{rental}/fines` | - | Input denda kerusakan/keterlambatan |
| `POST` | `/admin/rentals/{rental}/confirm-fine-paid` | - | Konfirmasi pelunasan denda & ongkir |
| `GET` | `/admin/deliveries` | `admin.deliveries` | Antrean kurir pickup & delivery |
| `POST` | `/admin/deliveries/{delivery}` | - | Update status kurir (in transit/received) |
| `GET` | `/admin/history` | `admin.history` | Rekapitulasi laporan peminjaman |
| `GET` | `/admin/history/print` | `admin.history.print` | Cetak laporan rekapitulasi format PDF |
| `GET` | `/admin/leaderboard` | `admin.leaderboard` | Peringkat member loyalty |
| `GET` | `/admin/resources` | `admin.resources` | Dashboard pemantauan resource CPU/RAM/Disk |
| `GET` | `/admin/resources/metrics` | `admin.resources.metrics` | Live JSON metrics API untuk auto-refresh |

---

## 10. 📋 Matriks Kesesuaian Persyaratan BNSP

| No | Persyaratan BNSP | Status | Implementasi pada Kode & Fitur |
|---|---|:---:|---|
| **1** | Terdapat 2 jenis Anggota (Admin dan User) | ✅ Selesai | Enum `UserRole` (`admin`, `user`), Middleware `EnsureUserHasRole` (`role:admin`). |
| **2** | Setiap User harus login untuk akses sistem | ✅ Selesai | Middleware `auth` pada semua rute peminjaman & operasional. |
| **3** | User harus terdaftar sebagai anggota | ✅ Selesai | Registrasi akun terpadu dengan relasi `user_profiles`. |
| **4** | Satu user hanya memiliki satu profil (1:1) | ✅ Selesai | Relasi `hasOne(UserProfile)` pada model `User`. |
| **5** | User dapat mengubah profil masing-masing | ✅ Selesai | Fitur edit nama, kontak, alamat, dan foto selfie webcam Base64 di `/profile`. |
| **6** | Nama unit dapat lebih dari 1 dengan kode unik | ✅ Selesai | Auto-increment code generator (`Unit::generateNextCode`), e.g. `PS5-001`, `PS5-002`. |
| **7** | Unit memiliki multiple kategori | ✅ Selesai | Relasi Many-to-Many `Category` ↔ `Unit` via pivot `category_unit`. |
| **8** | Pencarian unit berdasarkan nama/game/kriteria | ✅ Selesai | Filter SmartPick pencarian teks, kapasitas pemain, durasi, budget, dan game terpasang. |
| **9** | Admin CRUD Unit, Kategori, dan Anggota | ✅ Selesai | Menu Admin: `/admin/units`, `/admin/categories`, dan `/admin/members`. |
| **10**| Anggota hanya dapat meminjam maksimal 2 unit | ✅ Selesai | Validasi ketat di `CreateBooking` & `StartRental` (maksimal 2 unit aktif per user). |
| **11**| Pinjaman maksimal 5 hari & denda keterlambatan | ✅ Selesai | Validasi durasi sewa 1-5 hari, denda otomatis Rp 20.000/hari di `ProcessReturn`. |
| **12**| Pengembalian unit wajib dikonfirmasi Admin | ✅ Selesai | Alur pengembalian diproses oleh admin di `/admin/returns`. |
| **13**| Admin dapat melihat list unit yang dipinjam | ✅ Selesai | Dashboard antrean serah terima & unit aktif di `/admin/bookings`. |
| **14**| User hanya dapat melihat rental miliknya | ✅ Selesai | Query scoped `$request->user()->rentals()` di `/rentals` & `/history`. |
| **15**| Admin dapat melihat & mencetak riwayat (PDF) | ✅ Selesai | Halaman `/admin/history` dan tombol cetak PDF via DomPDF di `/admin/history/print`. |
| **16**| Validasi required di setiap form input | ✅ Selesai | Laravel Form Request Validation pada seluruh controller. |
| **17**| Skema Database Migration terstruktur | ✅ Selesai | 11 file migrasi Laravel ternormalisasi dengan foreign key constraints. |
| **18**| Database Seeder lengkap | ✅ Selesai | `DatabaseSeeder.php` menyediakan data admin, member, master game, unit konsol, dan combo. |

---

## 🧪 Pengujian Otomatis (*Automated Testing*)

Jalankan perintah berikut untuk memvalidasi seluruh fungsionalitas sistem:

```bash
php artisan test
```

Hasil pengujian: **46 passed, 358 assertions (100% Lulus)**.

---
*Dibuat oleh Tim Bakso Console untuk Uji Kompetensi Keahlian BNSP.*
