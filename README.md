# 🎮 Bakso Console — Smart PlayStation Rental & Operations Hub
> **Sistem Informasi Manajemen Rental Konsol PlayStation Terpadu & Terstandar BNSP**

---

## 📌 Ringkasan Proyek
- **Nama Tim**: Bakso Console
- **Skema Sertifikasi**: Uji Kompetensi Keahlian Pemrograman Web (BNSP)
- **Teknologi**: Laravel 11/13, Tailwind CSS, Alpine.js, Midtrans Snap API, DomPDF, Pest PHP.

---

## 📖 Dokumentasi Lengkap
Seluruh dokumentasi teknis, mulai dari panduan setup, skema database, detail arsitektur, alur bisnis end-to-end, matriks kesesuaian 18 poin BNSP, hingga daftar seluruh route dapat dibaca pada file:
👉 **[DOCUMENTATION.md](DOCUMENTATION.md)**

---

## 🚀 Panduan Cepat Menjalankan Aplikasi

```bash
# 1. Clone & Instalasi
git clone https://github.com/janbu12/BaksoConsole.git
cd BaksoConsole
composer install
npm install

# 2. Setup Environment & Database
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

# 3. Jalankan Server Lokal
npm run dev
php artisan serve
```

Akses aplikasi di browser: **`http://localhost:8000`**

---

## 👥 Akun Default (Seeder)
- **Administrator**: `admin@baksoconsole.test` | Password: `password`
- **Member / Anggota**: `budi@example.com` | Password: `password`

---

## 🧪 Validasi Pengujian
```bash
php artisan test
```
*Hasil: 46 unit & feature tests lulus 100%.*
