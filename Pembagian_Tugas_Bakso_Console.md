# 🧑‍🤝‍🧑 Pembagian Tugas & Sprint Plan — Bakso Console
### (Tim 2 Orang, berdasarkan PRD Bakso Console)

---

## 1. Prinsip Pembagian

Karena tim hanya **2 orang**, pembagian dilakukan per **modul end-to-end** (backend + frontend digabung per orang), bukan dipisah backend/frontend murni. Tujuannya:

- Masing-masing orang punya **kepemilikan penuh** atas modulnya (tidak saling menunggu).
- Sprint 1 difokuskan pada **fitur inti (core flow)** yang harus jalan mulus dulu, karena akan **dipresentasikan/didemo**.
- Fitur "selling point" (SmartPick, Rank, Heatmap, dsb.) baru masuk di sprint berikutnya, setelah alur inti stabil.

Total 24 fitur (23 fitur awal + Pickup & Delivery) dibagi menjadi dua peran:

| Peran | Fokus |
|---|---|
| **Mizan** | Alur transaksi inti: Auth, Member, Unit, Kategori, Booking, Rental, Return, Transaction |
| **Nable** | Pengalaman pengguna & fitur pendukung: Search, Availability, Timer/Warning, SmartPick, Mabar Capacity, Rank, Combo, Analytics, Heatmap, History, Delivery |

---

## 2. Fondasi Data Bersama (Sebelum Pembagian Fitur)

Model, enum, dan migration awal diselesaikan terlebih dahulu sebagai **shared foundation**. Fondasi ini bukan kepemilikan individual Mizan atau Nable. Keduanya wajib menggunakan nama tabel, kolom, relasi Eloquent, cast, dan enum status yang sudah tersedia.

Aturan kerja fondasi data:

- Jangan mengubah migration awal yang sudah dipakai bersama setelah feature branch mulai berjalan.
- Kebutuhan schema baru harus didiskusikan dan dibuat sebagai migration tambahan.
- Jangan membuat model atau enum kedua untuk data/status yang sama.
- Perubahan relasi model harus diselaraskan dengan kedua modul yang menggunakannya.
- Analytics, heatmap, history, rank, timer, warning, dan availability dihitung dari tabel transaksi bersama; tidak membuat tabel salinan.

Model bersama yang tersedia: `User`, `Profile`, `Unit`, `Category`, `Combo`, `Booking`, `Rental`, `RentalExtension`, `Fine`, `Delivery`, dan `Transaction`.

Tabel bersama yang tersedia: `users`, `profiles`, `units`, `categories`, `category_unit`, `combos`, `bookings`, `rentals`, `rental_extensions`, `fines`, `deliveries`, dan `transactions`.

---

## 3. Pembagian Tugas per Modul

### 👤 Mizan — "Core Transaction & Data Owner"

| No | Fitur (dari PRD) | Detail Tugas |
|---|---|---|
| 1 | Authentication & Role Management | Login, register, middleware role Admin/User |
| 2 | Member Management | CRUD anggota, profile (1 user = 1 profile), edit profile sendiri |
| 3 | Console Unit Management | CRUD unit, kode unit unik |
| 4 | Multiple Category | Relasi many-to-many unit ↔ kategori |
| 6 | Booking / Reservasi | Form booking, validasi bentrok jadwal |
| 7 | Rental Management | Proses sewa, aturan max 2 unit aktif, max 5 hari |
| 15 | Rental Extension | Ajukan perpanjangan + validasi batas maksimal |
| 16 | Return Management | Proses pengembalian oleh Admin, hitung denda |
| 17 | Transaction Management | Pencatatan transaksi (sewa, denda, total bayar) |
| 21 | Validation System | Validasi lintas modul (field wajib, tanggal, kode unik, dst) |
| 23 | Database Seeder | Seeder admin, kategori, unit, dan data uji memakai model bersama |

**Model yang digunakan:** `User`, `Profile`, `Unit`, `Category`, `Booking`, `Rental`, `RentalExtension`, `Fine`, dan `Transaction`. Struktur model dan tabel tetap menjadi fondasi bersama.

---

### 👤 Nable — "Experience & Insight Owner"

| No | Fitur (dari PRD) | Detail Tugas |
|---|---|---|
| 5 | Console Search | Pencarian unit berdasarkan nama |
| 8 | Smart Rental Timer | Hitung sisa hari sewa, tampilan countdown |
| 9 | Rental Warning System | Status 🟢🟡🔴 terhubung ke Smart Timer |
| 10 | Live Console Availability | Status unit real-time (Available/Booked/Rented/dll) |
| 11 | Mabar Capacity | Filter unit berdasarkan jumlah pemain |
| 12 | Smart Rental Recommendation (SmartPick) | Rekomendasi unit dari pemain + durasi + kategori + budget |
| 13 | Bakso Rank | Hitung total hari sewa → level rank + benefit |
| 14 | Bakso Combo | Paket bundling unit + aksesoris |
| 18 | Rental Analytics (Bakso Insight) | Dashboard admin (total rental, unit populer, dsb) |
| 19 | Rental Heatmap | Visualisasi pola penyewaan per tanggal |
| 20 | Rental History | Riwayat penyewaan (admin: semua, user: milik sendiri) + cetak |
| 24 | Pickup & Delivery Service *(fitur baru)* | Pilihan ambil di tempat/diantar, assign kurir, status pengantaran |

**Model yang digunakan:** `Unit`, `Category`, `Combo`, `Rental`, `Fine`, `Delivery`, dan `Transaction`. Analytics dan heatmap menggunakan query/agregasi dari tabel bersama.

> ⚠️ Catatan: modul Nable banyak yang **bergantung pada data dari modul Mizan** (rentals, transactions). Maka urutan pengerjaan Sprint 1 penting — lihat bagian 3.

---

## 4. Sprint Plan

### 🏁 Sprint 1 — "Core Flow Harus Jalan Mulus" (Prioritas Demo)

Tujuan: **alur utama end-to-end bisa didemokan** — login → cari/lihat unit → booking → rental → kembalikan unit — tanpa fitur "manis" dulu.

| Mizan | Nable |
|---|---|
| Authentication & Role Management | Console Search (dasar) |
| Console Unit Management + Multiple Category | Live Console Availability (status dasar) |
| Member Management (CRUD + profile) | — (bantu testing/integrasi) |
| Booking (dengan validasi bentrok) | — |
| Rental Management (max 2 unit, max 5 hari) | Smart Rental Timer (sisa hari) |
| Return Management (proses admin, hitung denda dasar) | Rental Warning System (🟢🟡🔴) |
| Transaction Management (dasar) | Rental History (tampilan sederhana) |
| Database Seeder memakai shared foundation | Verifikasi query fitur terhadap shared foundation |
| Validation System (validasi inti) | — |

**Definition of Done Sprint 1:**
- User bisa login, cari unit, lihat status ketersediaan.
- User bisa booking → rental → lihat sisa masa sewa + peringatan.
- Admin bisa proses return + lihat riwayat & denda dasar.
- Semua alur di atas **bisa didemo tanpa error**, data konsisten (tidak ada unit "double booked").

---

### 🚀 Sprint 2 — "Selling Point & Diferensiasi"

| Mizan | Nable |
|---|---|
| Rental Extension | Smart Rental Recommendation (SmartPick) |
| Refinement Transaction (denda, perhitungan lebih detail) | Mabar Capacity |
| — | Bakso Rank |
| — | Bakso Combo |

**Definition of Done Sprint 2:** Fitur rekomendasi, rank, dan combo sudah terintegrasi ke alur rental yang sudah ada di Sprint 1.

---

### 📊 Sprint 3 — "Business Insight & Layanan Tambahan"

| Mizan | Nable |
|---|---|
| Support data untuk Analytics (pastikan data transaksi lengkap & akurat) | Rental Analytics (Bakso Insight) |
| — | Rental Heatmap |
| — | Pickup & Delivery Service (lengkap: assign kurir, status pengantaran) |
| Integration & bug fixing lintas modul | Integration & bug fixing lintas modul |

**Definition of Done Sprint 3:** Dashboard analytics & heatmap tampil dari data real, fitur delivery aktif dan bisa diuji end-to-end (rental → pilih diantar → status berubah → selesai).

---

## 5. Ringkasan Prioritas

```text
Sprint 1  →  CORE FLOW (wajib demo mulus)
Sprint 2  →  SELLING POINT (nilai jual utama)
Sprint 3  →  INSIGHT + DELIVERY (pelengkap & business value)
```

Dengan urutan ini, presentasi tetap aman meskipun waktu terbatas — karena **fungsional inti (Sprint 1) sudah cukup untuk didemo**, dan setiap sprint tambahan murni menambah "wow factor" tanpa mengorbankan stabilitas alur utama.
