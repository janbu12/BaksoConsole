# 📄 Product Requirements Document (PRD)
## Bakso Console — Smart Console Rental & Management System

| | |
|---|---|
| **Nama Project** | Bakso |
| **Nama Sistem** | Bakso Console |
| **Nama Kelompok** | Gupron in da House |
| **Kategori** | Console Rental Management System |
| **Versi Dokumen** | 1.0 |
| **Status** | Draft |
| **Core Value** | *Rent Smarter. Play Better.* |

---

## 1. Executive Summary

Bakso Console adalah sistem informasi manajemen penyewaan console yang membantu **admin** dan **anggota (user)** mengelola proses peminjaman console secara terstruktur — mulai dari pencarian unit, booking, penyewaan, pengembalian, hingga transaksi dan riwayat.

Selain fungsi CRUD dasar, sistem ini dibangun di atas tiga pilar utama:

- **Customer Experience** — SmartPick, Mabar Capacity, Live Availability, Smart Timer, dan (baru) **Pickup & Delivery**
- **Customer Loyalty** — Bakso Rank, Rental History, Bakso Combo
- **Business Management** — Rental Analytics, Rental Heatmap, Transaction Management, Unit Management

---

## 2. Problem Statement

Proses penyewaan console secara manual (dicatat di buku/spreadsheet, komunikasi via chat) sering menimbulkan masalah:

- Sulit memantau ketersediaan unit secara real-time, sehingga terjadi bentrok jadwal.
- Tidak ada sistem yang membantu user memilih unit sesuai kebutuhan (jumlah pemain, budget, durasi).
- Tidak ada insentif/loyalitas bagi pelanggan tetap.
- Admin kesulitan menganalisis unit mana yang paling laku dan kapan periode ramai.
- **User harus datang langsung ke tempat untuk mengambil dan mengembalikan unit**, padahal tidak semua user memiliki waktu/akses transportasi ke outlet.

---

## 3. Goals & Objectives

- Menyediakan sistem penyewaan console yang terstruktur, transparan, dan mudah dipantau baik oleh admin maupun anggota.
- Meningkatkan pengalaman pelanggan lewat rekomendasi unit otomatis (SmartPick) dan pencarian berbasis kapasitas pemain (Mabar Capacity).
- Membangun loyalitas pelanggan lewat sistem rank berbasis total hari sewa (Bakso Rank).
- Memberikan visibilitas bisnis melalui analitik dan heatmap penyewaan (Bakso Insight).
- **Memperluas jangkauan layanan dengan opsi pengambilan/pengantaran unit (Pickup & Delivery), sehingga user tidak wajib datang ke outlet.**

---

## 4. Target Users

### 4.1 Admin
Bertanggung jawab atas pengelolaan anggota, unit, kategori, booking, rental, return, transaksi, denda, riwayat, dan analytics — termasuk **mengatur metode pengambilan/pengantaran dan kurir**.

### 4.2 User / Anggota
Dapat login, mengelola profil, mencari unit, mengecek ketersediaan, melakukan booking & rental, memantau masa sewa, mengajukan perpanjangan, melihat riwayat dan rank — serta **memilih apakah unit diambil sendiri di outlet atau diantar ke alamat mereka**.

---

## 5. Scope

### In Scope
Seluruh 23 fitur pada dokumen fitur awal (Authentication, Member Management, Console Unit Management, Multiple Category, Console Search, Booking, Rental Management, Smart Rental Timer, Rental Warning System, Live Console Availability, Mabar Capacity, Smart Rental Recommendation, Bakso Rank, Bakso Combo, Rental Extension, Return Management, Transaction Management, Rental Analytics, Rental Heatmap, Rental History, Validation System, Database Migration, Database Seeder) **ditambah fitur baru Pickup & Delivery Service**.

### Out of Scope (v1)
- Pembayaran online (payment gateway) — asumsi transaksi masih manual/konfirmasi admin, kecuali dinyatakan lain di implementasi akhir.
- Live tracking GPS kurir secara real-time (v1 hanya status pengantaran bertahap).
- Multi-cabang/multi-outlet (asumsi satu outlet, radius pengantaran tunggal).

---

## 6. Functional Requirements

### 6.1 Authentication & Role Management
- Sistem memiliki dua role: **Admin** dan **User/Anggota**.
- User wajib login dan terdaftar sebagai anggota sebelum dapat melakukan penyewaan.

### 6.2 Member Management
- Admin dapat: tambah, lihat, update, hapus anggota, serta melihat riwayat penyewaan anggota.
- Setiap user memiliki satu profile dan dapat mengubah profilnya sendiri.

### 6.3 Console Unit Management
- Admin mengelola unit console: nama unit, kode unit (unik), kategori, status, informasi pendukung.
- Satu nama console dapat memiliki banyak unit fisik (mis. PS5-001, PS5-002, dst).

### 6.4 Multiple Category
- Satu unit dapat memiliki lebih dari satu kategori (mis. PlayStation 5, Multiplayer, Mabar, Family) untuk mendukung pencarian dan pengelompokan yang fleksibel.

### 6.5 Console Search
- User dapat mencari unit berdasarkan nama unit; sistem menampilkan unit yang sesuai kata kunci.

### 6.6 Booking / Reservasi
- User dapat booking unit sebelum menyewa, dengan data: unit, tanggal mulai, tanggal selesai, durasi, status booking.
- Sistem memvalidasi ketersediaan agar tidak ada bentrok jadwal.

### 6.7 Rental Management
- Maksimal **2 unit aktif** per anggota.
- Durasi maksimal **5 hari**; lebih dari itu dikenakan denda sesuai aturan sistem.
- Setiap rental mencatat tanggal mulai, tanggal kembali, durasi, dan status.

### 6.8 Smart Rental Timer
- Menampilkan sisa masa sewa dalam satuan **hari** (bukan jam).
- Memberi peringatan saat masa sewa mendekati batas, dan status "berakhir" saat lewat batas.

### 6.9 Rental Warning System
- Terintegrasi dengan Smart Rental Timer, dengan tiga level status:
  - 🟢 Aman
  - 🟡 Segera Berakhir
  - 🔴 Terlambat (denda dihitung otomatis)

### 6.10 Live Console Availability
- Menampilkan status setiap unit secara langsung: Available, Booked, Rented, Returned, Maintenance.

### 6.11 Mabar Capacity
- User memilih jumlah pemain (1–4+), sistem menampilkan unit yang sesuai kapasitas tersebut.

### 6.12 Smart Rental Recommendation (Bakso SmartPick)
- User memasukkan jumlah pemain, durasi, kategori, dan budget.
- Sistem merekomendasikan unit yang paling sesuai dengan seluruh kriteria tersebut.

### 6.13 Bakso Rank
- Sistem loyalitas berbasis **total hari sewa kumulatif**, bukan jumlah transaksi:

| Rank | Total Hari Sewa | Benefit |
|---|---:|---|
| 🥉 Bakso Rookie | 0–5 Hari | Member |
| 🥈 Bakso Player | 6–15 Hari | Benefit Level 1 |
| 🥇 Bakso Pro | 16–30 Hari | Benefit Level 2 |
| 👑 Bakso Legend | >30 Hari | Benefit Level 3 |

### 6.14 Bakso Combo
- Paket bundling unit + aksesoris + durasi dengan harga khusus (mis. Bakso Mabar, Bakso Family).

### 6.15 Rental Extension
- User dapat mengajukan perpanjangan sebelum/saat masa sewa berakhir, tetap mengikuti batas maksimal durasi sistem; jika melebihi, perlu persetujuan admin atau dikenakan denda.

### 6.16 Return Management
- Pengembalian **hanya diproses oleh Admin** (atau kurir yang ditugaskan Admin — lihat 6.24).
- Admin dapat memproses pengembalian, memeriksa keterlambatan, menghitung denda, mengubah status unit menjadi Available, dan menyimpan transaksi pengembalian.

### 6.17 Transaction Management
- Mencatat: anggota, unit, tanggal sewa, tanggal kembali, durasi, harga, denda, **biaya antar (jika ada)**, total pembayaran, status transaksi.

### 6.18 Rental Analytics (Bakso Insight)
- Dashboard admin: total rental, unit aktif disewa, unit tersedia, total member, unit paling sering disewa, anggota paling aktif, total hari sewa, total transaksi, total denda, **dan proporsi metode pickup vs delivery**.

### 6.19 Rental Heatmap
- Visualisasi pola penyewaan berdasarkan tanggal/periode untuk mengetahui waktu permintaan tinggi (per hari, bukan per jam).

### 6.20 Rental History
- Admin dapat melihat & mencetak seluruh riwayat peminjaman; user hanya dapat melihat riwayat miliknya sendiri.

### 6.21 Validation System
- Field wajib diisi, format email valid, kode unit unik, ketentuan password, tanggal selesai ≥ tanggal mulai, durasi tidak melebihi batas, maksimal 2 unit aktif per user, unit yang sedang disewa tidak bisa disewa user lain, booking tidak boleh bentrok.

### 6.22 Database Migration & 6.23 Database Seeder
- Struktur tabel awal: `users`, `profiles`, `units`, `categories`, `category_unit`, `rentals`, `bookings`, `transactions`, `fines`, `rental_extensions` (+ tabel baru `deliveries`, lihat 6.24.4).
- Seeder menyediakan data awal (admin, kategori, unit contoh) untuk mempermudah pengujian.

---

### 6.24 🚚 Pickup & Delivery Service *(Fitur Baru)*

**Deskripsi:**
Saat melakukan rental (dan juga saat pengembalian), user dapat memilih salah satu dari dua metode pengambilan unit:

1. **Ambil di Tempat (Pickup/Self-Service)** — user datang langsung ke outlet sesuai jadwal yang disepakati, tanpa biaya tambahan.
2. **Diantar (Delivery)** — unit diantar oleh kurir ke alamat yang diisi user, dengan biaya antar tambahan.

Hal yang sama berlaku saat pengembalian: user dapat mengembalikan unit langsung ke outlet, atau meminta unit **dijemput** oleh kurir.

#### 6.24.1 Alur Fitur
```text
Rental Dibuat
   │
   ▼
Pilih Metode Pengambilan
   │
   ├── Ambil di Tempat ──► Jadwal kedatangan ke outlet ──► Status: "Siap Diambil"
   │
   └── Diantar ──► Isi alamat & catat estimasi ongkir
                     │
                     ▼
              Admin assign kurir
                     │
                     ▼
        Status: Menunggu Diantar → Dalam Perjalanan → Diterima User
```

Alur serupa berlaku untuk pengembalian: **Antar Sendiri ke Outlet** vs **Dijemput Kurir**.

#### 6.24.2 Data yang Dicatat
- Metode: `pickup` atau `delivery`
- Alamat pengantaran/penjemputan (khusus delivery)
- Nomor kontak penerima
- Estimasi biaya antar (dihitung berdasarkan zona/jarak dari outlet)
- Kurir yang ditugaskan (diisi Admin)
- Status pengantaran: `Menunggu Diantar`, `Dalam Perjalanan`, `Diterima`, `Dijemput`, `Dikembalikan ke Outlet`
- Tanggal & waktu (hari) estimasi serta aktual pengantaran/penjemputan

#### 6.24.3 Aturan & Validasi Tambahan
- Alamat **wajib diisi** jika metode = Diantar.
- Area pengantaran dibatasi radius/zona tertentu yang ditentukan Admin; di luar zona, opsi Diantar otomatis tidak tersedia (fallback ke Pickup).
- Biaya antar ditambahkan ke total transaksi (lihat 6.17).
- Admin dapat mengubah status pengantaran secara manual (belum ada tracking real-time di v1).
- Jika delivery dipilih untuk pengembalian, unit dianggap "masih disewa" sampai kurir mengonfirmasi penjemputan berhasil — status unit baru berubah menjadi Available setelah Admin memverifikasi unit diterima kembali di outlet.

#### 6.24.4 Tabel Database Tambahan
```text
deliveries
- id
- rental_id (FK ke rentals)
- type            → 'delivery_out' | 'delivery_return'
- method          → 'pickup' | 'delivery'
- address
- contact_number
- delivery_fee
- courier_name (nullable)
- status          → menunggu_diantar | dalam_perjalanan | diterima | dijemput | dikembalikan
- scheduled_date
- completed_date
```

#### 6.24.5 Selling Point
> **"User tidak perlu repot datang ke outlet — cukup pilih Diantar, dan console siap dimainkan di rumah."**

Fitur ini menjadikan Bakso Console lebih fleksibel dibanding sistem rental console konvensional yang mengharuskan pelanggan datang langsung.

---

## 7. Updated Business Flow

```text
LOGIN
  │
  ▼
USER / ADMIN
  │
  ├───────────────────────┐
  │                        │
 USER                    ADMIN
  │                        │
  ▼                        ▼
Cari Unit               Kelola Unit
  │                      Kelola User
  ▼                      Kelola Kategori
Cek Availability         Kelola Rental
  │                      Kelola Return
  ▼                      Kelola Transaksi
Booking                  Assign Kurir Delivery
  │                      Lihat Analytics
  ▼
Rental
  │
  ▼
Pilih Metode: Ambil di Tempat / Diantar
  │
  ├── Diantar ──► Kurir Antar Unit ──► Diterima User
  │
  └── Ambil Sendiri ──► User ke Outlet
  │
  ▼
Smart Timer
  │
  ├── Perpanjangan
  │
  ▼
Pengembalian: Antar ke Outlet / Dijemput Kurir
  │
  ▼
Rental History
  │
  ▼
Bakso Rank
```

---

## 8. Selling Points (Diperbarui — 8 Selling Point Utama)

1. 🤖 **Smart Rental Recommendation** — rekomendasi unit berdasarkan pemain, durasi, kategori, budget.
2. 🏆 **Bakso Rank** — loyalitas berdasarkan total hari sewa.
3. ⏳ **Smart Rental Timer** — sisa masa sewa dalam hari + peringatan otomatis.
4. 🟢 **Live Console Availability** — status unit real-time.
5. 👥 **Mabar Capacity** — pencarian unit berdasarkan jumlah pemain.
6. 📊 **Rental Heatmap** — pola penyewaan berdasarkan tanggal/periode.
7. 📈 **Rental Analytics** — insight bisnis untuk admin.
8. 🚚 **Pickup & Delivery Service** *(baru)* — fleksibilitas pengambilan/pengantaran unit tanpa harus ke outlet.

---

## 9. Non-Functional Requirements

- **Usability**: alur booking → rental → pilih metode pengambilan harus dapat diselesaikan dalam sedikit langkah (minim friction).
- **Data Integrity**: validasi ketat pada bentrok jadwal, kode unit unik, dan batas unit aktif per user (lihat 6.21).
- **Auditability**: seluruh transaksi (termasuk biaya antar) tercatat dan dapat ditelusuri lewat Rental History.
- **Scalability**: struktur tabel `deliveries` dipisah dari `rentals` agar mudah dikembangkan ke multi-outlet/multi-kurir di versi berikutnya.

---

## 10. Success Metrics (KPI)

| Metrik | Target Indikatif |
|---|---|
| Adopsi fitur Delivery | % rental yang memilih "Diantar" dari total rental |
| Tingkat keterlambatan pengembalian | Menurun setelah Smart Rental Timer & Warning System aktif |
| Retensi anggota (naik rank) | % anggota yang naik rank dalam 3 bulan |
| Akurasi rekomendasi SmartPick | % rekomendasi yang benar-benar disewa user |
| Waktu proses pengantaran | Rata-rata waktu dari "Menunggu Diantar" → "Diterima" |

---

## 11. Assumptions & Constraints

- Sistem berjalan untuk **satu outlet** dengan radius pengantaran terbatas (v1).
- Pembayaran diasumsikan dikonfirmasi manual oleh Admin (belum ada payment gateway).
- Kurir dapat berupa staf internal (bukan pihak ketiga/ojek online) pada v1.
- Tracking kurir bersifat **update status manual**, bukan GPS real-time.

## 12. Open Questions

- Apakah biaya antar bersifat flat, per-zona, atau per-jarak (perlu ditentukan sebelum implementasi akhir)?
- Apakah delivery bisa dibatalkan/diubah menjadi pickup setelah kurir ditugaskan?
- Apakah ada limit jumlah delivery yang bisa ditangani per hari (kapasitas kurir)?

---

*Dokumen ini merupakan turunan dari dokumen fitur "Bakso Console" (23 fitur awal) dengan penambahan fitur Pickup & Delivery Service sebagai selling point ke-8.*
