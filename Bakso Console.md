# 🍜 Bakso Console

**Bakso Console** adalah sistem informasi manajemen penyewaan console yang membantu admin dan anggota mengelola proses peminjaman console secara terstruktur. Sistem ini mencakup pengelolaan anggota, unit console, kategori, booking, penyewaan, pengembalian, transaksi, hingga riwayat peminjaman.

Bakso Console memiliki beberapa fitur tambahan yang menjadi **selling point**, yaitu rekomendasi unit berdasarkan kebutuhan pengguna, sistem loyalitas berdasarkan total hari penyewaan, pemantauan ketersediaan unit, pengingat masa sewa, analisis penyewaan, serta pencarian console berdasarkan kapasitas pemain.

---

# 🎮 Fitur Utama

## 1. 🔐 Authentication & Role Management

Sistem memiliki dua jenis pengguna:

- **Admin**
- **User / Anggota**

### Admin
Admin dapat mengelola seluruh data dan proses penyewaan.

### User
User harus melakukan login dan terdaftar sebagai anggota untuk dapat melakukan penyewaan.

---

## 2. 👤 Member Management

Admin dapat mengelola data anggota.

Fitur:

- Tambah anggota
- Lihat data anggota
- Update data anggota
- Hapus anggota
- Melihat riwayat penyewaan anggota

Setiap user hanya memiliki **satu profile**.

User juga dapat mengubah informasi profile miliknya sendiri.

---

# 🎮 3. Console Unit Management

Admin dapat mengelola seluruh unit console yang tersedia.

Setiap unit memiliki:

- Nama unit
- Kode unit
- Kategori
- Status
- Informasi pendukung lainnya

Satu nama console dapat memiliki banyak unit.

Contoh:

```text
PlayStation 5

PS5-001
PS5-002
PS5-003
PS5-004
```

Setiap unit memiliki **kode unik** sehingga unit dapat dibedakan satu sama lain.

---

# 🏷️ 4. Multiple Category

Satu unit dapat memiliki lebih dari satu kategori.

Contoh:

```text
PS5-001

Kategori:
- PlayStation 5
- Multiplayer
- Mabar
- Family
```

Fitur ini memungkinkan pencarian dan pengelompokan unit menjadi lebih fleksibel.

---

# 🔎 5. Console Search

User dapat mencari unit berdasarkan nama unit.

Contoh:

```text
Search: PlayStation 5
```

Sistem akan menampilkan unit yang sesuai dengan kata pencarian.

---

# 📅 6. Booking / Reservasi

User dapat melakukan booking unit sebelum melakukan penyewaan.

Informasi booking meliputi:

- Unit
- Tanggal mulai
- Tanggal selesai
- Durasi sewa
- Status booking

Sistem akan memeriksa ketersediaan unit agar tidak terjadi bentrokan jadwal penyewaan.

---

# 📝 7. Rental Management

User dapat melakukan penyewaan unit yang tersedia.

Ketentuan:

- Maksimal **2 unit aktif** untuk setiap anggota.
- Durasi penyewaan maksimal **5 hari**.
- Penyewaan lebih dari 5 hari akan dikenakan denda sesuai aturan sistem.

Contoh:

```text
Tanggal mulai : 10 Agustus 2026
Tanggal kembali: 14 Agustus 2026

Durasi: 4 hari
Status: Aktif
```

---

# ⏳ 8. Smart Rental Timer

**Smart Rental Timer** merupakan countdown yang menampilkan sisa masa penyewaan berdasarkan **hari**, bukan jam.

Contoh:

```text
PS5-001

Sisa masa sewa:
2 Hari

Tanggal kembali:
14 Agustus 2026
```

Sistem dapat memberikan peringatan ketika masa sewa mendekati batas pengembalian.

Contoh:

```text
⚠️ Masa sewa tersisa 1 hari.
Segera lakukan pengembalian unit.
```

Jika masa sewa telah melewati batas:

```text
🔴 Masa sewa telah berakhir.
Denda dapat dikenakan.
```

---

# 🔔 9. Rental Warning System

Fitur ini terintegrasi dengan Smart Rental Timer.

Sistem memberikan peringatan kepada user berdasarkan kondisi penyewaan.

Contoh:

### 🟢 Aman

```text
Masa sewa masih tersedia.
Sisa: 4 hari
```

### 🟡 Segera Berakhir

```text
Masa sewa akan berakhir.
Sisa: 1 hari
```

### 🔴 Terlambat

```text
Masa sewa telah melewati batas.
Denda akan dihitung oleh sistem.
```

Fitur ini membantu mengurangi keterlambatan pengembalian unit.

---

# 🟢 10. Live Console Availability

**Live Console Availability** menampilkan kondisi setiap unit secara langsung berdasarkan status penyewaan.

Contoh:

```text
PS5-001    🟢 AVAILABLE
PS5-002    🔴 RENTED
PS5-003    🟡 BOOKED
PS5-004    🟢 AVAILABLE
```

Status unit dapat berupa:

- Available
- Booked
- Rented
- Returned
- Maintenance

User dapat mengetahui unit yang tersedia sebelum melakukan booking.

---

# 👥 11. Mabar Capacity

**Mabar Capacity** merupakan fitur pencarian unit berdasarkan jumlah pemain.

User dapat menentukan jumlah pemain sebelum memilih unit.

Contoh:

```text
Jumlah pemain:

○ 1 Player
○ 2 Players
○ 3 Players
● 4 Players
```

Sistem kemudian menampilkan unit yang sesuai dengan kebutuhan tersebut.

Contoh:

```text
PS5-003

Capacity:
4 Players

Status:
Available
```

### Selling Point

User tidak perlu memilih console secara manual. Sistem membantu menemukan unit yang sesuai dengan jumlah pemain.

---

# 🤖 12. Smart Rental Recommendation

**Bakso SmartPick** merupakan fitur rekomendasi penyewaan yang membantu user memilih unit berdasarkan kebutuhan.

User dapat memasukkan:

- Jumlah pemain
- Durasi penyewaan
- Kategori console
- Budget

Contoh:

```text
Jumlah pemain : 4 orang
Durasi        : 3 hari
Budget        : Rp150.000
```

Sistem memberikan rekomendasi:

```text
🎮 Recommended Unit

PS5-003

Capacity : 4 Players
Durasi   : 3 Hari
Harga    : Rp120.000

✓ Sesuai jumlah pemain
✓ Sesuai durasi
✓ Sesuai budget
```

### Selling Point

**Bakso Console tidak hanya menyediakan daftar console. Sistem membantu anggota menentukan pilihan console berdasarkan kebutuhan penyewaan.**

---

# 🏆 13. Bakso Rank

**Bakso Rank** merupakan sistem loyalitas berdasarkan **total hari penyewaan**, bukan jumlah transaksi.

Semakin lama anggota menggunakan layanan Bakso Console, semakin tinggi rank yang diperoleh.

Contoh:

| Rank | Total Hari Sewa | Benefit |
|---|---:|---|
| 🥉 Bakso Rookie | 0-5 Hari | Member |
| 🥈 Bakso Player | 6-15 Hari | Benefit Level 1 |
| 🥇 Bakso Pro | 16-30 Hari | Benefit Level 2 |
| 👑 Bakso Legend | >30 Hari | Benefit Level 3 |

Contoh profile:

```text
Nable

Rank:
🥇 BAKSO PRO

Total Rental:
23 Hari
```

### Selling Point

Sistem membuat proses penyewaan lebih interaktif dengan memberikan **progress dan rank berdasarkan aktivitas penyewaan anggota**.

---

# 📦 14. Bakso Combo

**Bakso Combo** merupakan fitur bundling unit dan layanan penyewaan.

Contoh:

### Bakso Mabar

```text
PS5
+ 4 Controller
+ 3 Hari

Harga Paket:
Rp150.000
```

### Bakso Family

```text
PS4
+ 2 Controller
+ 2 Hari

Harga Paket:
Rp80.000
```

Bundling dapat membantu admin membuat paket penyewaan yang lebih menarik.

---

# 🔄 15. Rental Extension

User dapat mengajukan perpanjangan masa sewa sebelum atau ketika masa sewa berakhir.

Namun, sistem tetap mengikuti aturan maksimal penyewaan.

Contoh:

```text
Sewa awal:
3 Hari

Perpanjangan:
+2 Hari

Total:
5 Hari
```

Jika melebihi batas maksimal, sistem dapat memberikan informasi mengenai ketentuan denda atau meminta persetujuan admin.

---

# ↩️ 16. Return Management

Pengembalian unit hanya dapat dilakukan oleh **Admin**.

User harus menghubungi admin untuk mengembalikan unit.

Admin dapat:

- Memproses pengembalian
- Memeriksa keterlambatan
- Menghitung denda
- Mengubah status unit menjadi Available
- Menyimpan transaksi pengembalian

---

# 💰 17. Transaction Management

Sistem mencatat transaksi penyewaan.

Data transaksi dapat meliputi:

- Anggota
- Unit
- Tanggal sewa
- Tanggal kembali
- Durasi
- Harga
- Denda
- Total pembayaran
- Status transaksi

---

# 📊 18. Rental Analytics

**Bakso Insight** merupakan dashboard analitik untuk membantu admin memahami aktivitas penyewaan.

Admin dapat melihat:

- Total penyewaan
- Total unit
- Unit yang sedang disewa
- Unit tersedia
- Unit paling sering disewa
- Anggota paling aktif
- Total hari penyewaan
- Total transaksi
- Total denda
- Statistik penyewaan

Contoh:

```text
Total Rental       : 128
Active Rental      : 17
Available Unit     : 23
Total Member       : 87

Most Rented:
PlayStation 5

Most Active Member:
Nable

Total Rental Days:
436 Hari
```

---

# 📈 19. Rental Heatmap

**Rental Heatmap** digunakan untuk melihat pola penyewaan berdasarkan **tanggal atau hari**.

Karena sistem menggunakan satuan hari, heatmap tidak menggunakan jam.

Contoh:

| Tanggal | Jumlah Penyewaan |
|---|---:|
| 1 Agustus | 5 |
| 2 Agustus | 8 |
| 3 Agustus | 3 |
| 4 Agustus | 12 |
| 5 Agustus | 15 |

Admin dapat mengetahui tanggal atau periode yang memiliki aktivitas penyewaan tinggi.

### Contoh insight

```text
🔥 Peak Rental Period

10-15 Agustus

Total Rental:
42 Penyewaan
```

Fitur ini dapat membantu admin memahami periode dengan permintaan tinggi.

---

# 📜 20. Rental History

Admin dapat melihat seluruh riwayat peminjaman anggota.

Informasi yang tersedia:

- Nama anggota
- Unit
- Tanggal sewa
- Tanggal kembali
- Durasi
- Status
- Denda
- Total transaksi

Admin juga dapat **mencetak riwayat peminjaman**.

User hanya dapat melihat riwayat penyewaan miliknya sendiri.

---

# 🛡️ 21. Validation System

Sistem memberikan validasi pada field yang membutuhkan validasi.

Contoh:

- Field wajib diisi
- Email harus valid
- Kode unit harus unik
- Password harus memenuhi ketentuan
- Tanggal selesai tidak boleh sebelum tanggal mulai
- Durasi sewa tidak boleh melebihi batas
- User tidak dapat menyewa lebih dari 2 unit aktif
- Unit yang sedang disewa tidak dapat disewa user lain
- Booking yang bertabrakan tidak dapat dibuat

---

# 🗄️ 22. Database Migration

Database dirancang menggunakan migration untuk menyesuaikan kebutuhan sistem.

Beberapa tabel utama:

```text
users
profiles
units
categories
category_unit
rentals
bookings
transactions
fines
rental_extensions
```

Struktur tabel dapat disesuaikan kembali dengan implementasi akhir project.

---

# 🌱 23. Database Seeder

Seeder digunakan untuk menyediakan data awal ketika sistem pertama kali dijalankan.

Contoh:

### Admin

```text
Email:
admin@baksoconsole.com
```

### Category

```text
PlayStation 4
PlayStation 5
Multiplayer
Family
```

### Unit

```text
PS4-001
PS4-002
PS5-001
PS5-002
PS5-003
```

Seeder membuat proses pengujian sistem menjadi lebih mudah.

---

# ⭐ Selling Point Bakso Console

Bakso Console memiliki **7 selling point utama**.

### 1. 🤖 Smart Rental Recommendation

Sistem merekomendasikan unit berdasarkan:

- Jumlah pemain
- Durasi sewa
- Kategori
- Budget

---

### 2. 🏆 Bakso Rank

Sistem memberikan rank berdasarkan **total hari penyewaan anggota**.

Semakin banyak hari menyewa, semakin tinggi progress anggota.

---

### 3. ⏳ Smart Rental Timer

Sistem menghitung **sisa masa penyewaan dalam hari** dan memberikan peringatan sebelum masa sewa berakhir.

---

### 4. 🟢 Live Console Availability

User dapat mengetahui status unit secara langsung:

```text
🟢 Available
🟡 Booked
🔴 Rented
⚙️ Maintenance
```

---

### 5. 👥 Mabar Capacity

User dapat mencari console berdasarkan jumlah pemain.

Contoh:

> "Saya ingin bermain 4 orang."

Sistem langsung menampilkan unit yang mendukung kebutuhan tersebut.

---

### 6. 📊 Rental Heatmap

Admin dapat melihat pola penyewaan berdasarkan **tanggal dan periode**, sehingga dapat mengetahui kapan permintaan rental meningkat.

---

### 7. 📈 Rental Analytics

Admin mendapatkan data mengenai:

- Unit terpopuler
- Anggota paling aktif
- Total hari penyewaan
- Jumlah transaksi
- Unit tersedia
- Unit sedang disewa
- Total denda

---

# 🔥 Unique Value Proposition

> **"Bakso Console adalah sistem rental console berbasis kebutuhan anggota yang menggabungkan rekomendasi unit, pemantauan masa sewa, sistem loyalitas berbasis hari, pencarian berdasarkan kapasitas pemain, serta analisis aktivitas penyewaan dalam satu platform."**

---

# 🎯 Target Pengguna

## Admin

Admin bertanggung jawab terhadap:

- Pengelolaan anggota
- Pengelolaan unit
- Pengelolaan kategori
- Booking
- Penyewaan
- Pengembalian
- Transaksi
- Denda
- Riwayat
- Analytics

## User / Anggota

User dapat:

- Login
- Mengelola profile
- Mencari unit
- Melihat ketersediaan
- Melakukan booking
- Melakukan penyewaan
- Melihat masa sewa
- Mengajukan perpanjangan
- Melihat unit yang sedang dipinjam
- Melihat riwayat penyewaan
- Melihat rank

---

# 🏗️ Business Flow

```text
LOGIN
  │
  ▼
USER / ADMIN
  │
  ├───────────────┐
  │               │
 USER            ADMIN
  │               │
  ▼               ▼
Cari Unit       Kelola Unit
  │             Kelola User
  ▼             Kelola Kategori
Cek Availability Kelola Rental
  │             Kelola Return
  ▼             Kelola Transaksi
Booking         Lihat Analytics
  │
  ▼
Rental
  │
  ▼
Smart Timer
  │
  ├── Perpanjangan
  │
  ▼
Pengembalian
  │
  ▼
Rental History
  │
  ▼
Bakso Rank
```

---

# 💡 Konsep Utama Bakso Console

Bakso Console tidak hanya berfungsi sebagai sistem **CRUD penyewaan console**.

Sistem memiliki tiga fokus utama:

### 🎮 Customer Experience

- SmartPick
- Mabar Capacity
- Live Availability
- Smart Timer

### 🏆 Customer Loyalty

- Bakso Rank
- Rental History
- Bakso Combo

### 📊 Business Management

- Rental Analytics
- Rental Heatmap
- Transaction Management
- Unit Management

Dengan pembagian tersebut, setiap fitur memiliki tujuan yang jelas dan tidak sekadar menjadi fitur tambahan.

---

# 🚀 Project Identity

**Nama Kelompok:** Gupron in da House

**Nama Team:** Bakso Console

**Nama Project:** Bakso

**Kategori:** Console Rental Management System

**Konsep:** Smart Console Rental & Management System

**Core Value:**

> **Rent Smarter. Play Better.**

---