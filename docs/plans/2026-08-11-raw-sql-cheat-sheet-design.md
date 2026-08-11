# Desain Cheat Sheet Raw SQL Bakso Console

## Tujuan

Menyediakan satu referensi MySQL 8 yang bisa dipakai saat demonstrasi dan wawancara uji kompetensi Programmer SKKNI J.620100, berdasarkan schema dan logic terbaru Bakso Console.

## Pendekatan

Dokumen memakai format hybrid: query siap salin di bagian operasional, disertai penjelasan singkat tentang tujuan, relasi dengan logic Laravel, output yang diharapkan, dan kalimat jawaban asesor. Semua contoh memakai MySQL 8 dan database `bakso_console`.

## Cakupan

- Pemetaan schema, relasi, constraint, dan index.
- DML CRUD seluruh domain utama.
- Query halaman landing, dashboard, katalog, SmartPick, availability, riwayat, leaderboard, invoice, dan analytics.
- Transaksi atomic dengan row locking untuk booking/rental dan return.
- View, stored procedure, stored function, trigger, commit/rollback, EXPLAIN, serta keamanan koneksi.
- Pembedaan tegas antara implementasi Laravel aktual dan ekuivalen raw SQL untuk bukti kompetensi.
- Catatan audit terhadap ketidaksesuaian trigger `completed` dengan status aktual `returned`.

## Validasi

Query diverifikasi secara statis terhadap migration, enum, action, query object, controller, dan test terbaru. Dokumen tidak mengubah schema atau data aplikasi secara otomatis; query yang bersifat mutasi diberi peringatan dan contoh parameter.
