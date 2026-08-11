# Raw SQL Cheat Sheet Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Membuat cheat sheet raw SQL MySQL 8 lengkap untuk logic terbaru Bakso Console dan kebutuhan uji kompetensi.

**Architecture:** Satu dokumen Markdown berlapis: quick reference, query per use case, objek database tingkat lanjut, lalu panduan jawaban asesor. Query diturunkan dari migration dan application flow aktual.

**Tech Stack:** MySQL 8, Laravel 13, Eloquent ORM, Blade, Markdown.

---

### Task 1: Petakan schema dan aturan domain

**Files:**
- Create: `docs/cheat-sheet-raw-sql-bakso-console.md`

1. Dokumentasikan konfigurasi MySQL tanpa kredensial rahasia.
2. Petakan tabel, PK, FK, status, dan cardinality.
3. Tuliskan parameter sesi yang dipakai seluruh contoh.

### Task 2: Tulis query interaksi aplikasi

**Files:**
- Modify: `docs/cheat-sheet-raw-sql-bakso-console.md`

1. Tulis query dashboard admin dan pengguna.
2. Tulis katalog, SmartPick, availability, dan overlap check.
3. Tulis CRUD, booking/rental, payment, handover, extension, return, delivery, fine, history, invoice, dan leaderboard.

### Task 3: Tulis bukti SQL tingkat lanjut

**Files:**
- Modify: `docs/cheat-sheet-raw-sql-bakso-console.md`

1. Tambahkan index dan `EXPLAIN ANALYZE`.
2. Tambahkan view dashboard/history/leaderboard/invoice.
3. Tambahkan function, procedure, trigger, serta transaction/rollback.
4. Tambahkan hak akses user database.

### Task 4: Validasi dokumen

**Files:**
- Verify: `docs/cheat-sheet-raw-sql-bakso-console.md`

1. Cocokkan semua nama tabel/kolom/status dengan migration dan enum.
2. Pastikan setiap query mutasi memiliki batas `WHERE` atau transaksi aman.
3. Pastikan unit SQL dan akses basis data dari dokumen asesmen terjawab.
4. Jalankan pemeriksaan struktur Markdown dan diff.
