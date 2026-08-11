# Cheat Sheet Raw SQL MySQL 8 — Bakso Console

> Referensi cepat untuk demonstrasi uji kompetensi Programmer SKKNI J.620100.020.02 (Menggunakan SQL) dan J.620100.021.02 (Menerapkan Akses Basis Data). Disusun dari migration dan logic Bakso Console per 11 Agustus 2026.

## 0. Cara menggunakan dokumen

- Jalankan pada database latihan, bukan database produksi.
- Ganti variabel `@...` sebelum menjalankan query.
- Password aplikasi tetap di `.env`; jangan ditulis di source code atau dipresentasikan.
- Query `DELETE`, `DROP`, procedure, function, dan trigger diberi bagian khusus agar tidak dijalankan tanpa sengaja.
- Aplikasi memakai Eloquent/Query Builder. SQL di sini adalah ekuivalen MySQL dari logic aktual serta bukti kompetensi SQL.

## 1. Koneksi dan inspeksi database

Konfigurasi aktif project telah terverifikasi memakai MySQL, host `127.0.0.1`, port `3306`, database `bakso_console`.

```bash
mysql -h 127.0.0.1 -P 3306 -u root -p bakso_console
```

```sql
SELECT VERSION();
SELECT DATABASE();
SHOW TABLES;
SHOW CREATE TABLE rentals\G
SHOW INDEX FROM rentals;
SHOW TRIGGERS FROM bakso_console;
SHOW PROCEDURE STATUS WHERE Db = 'bakso_console';
SHOW FUNCTION STATUS WHERE Db = 'bakso_console';
```

Parameter contoh untuk sesi demo:

```sql
USE bakso_console;
SET @user_id = 2;
SET @unit_id = 1;
SET @start_date = '2026-08-15';
SET @end_date = '2026-08-17';
SET @players = 4;
SET @duration = 3;
SET @budget = 180000;
SET @category_id = 2;
SET @delivery_method = 'delivery';
SET @delivery_fee = 15000.00;
```

## 2. Peta schema dan relasi

| Tabel | Fungsi | Relasi utama |
|---|---|---|
| `users` | Admin dan anggota | 1:1 profile, 1:N booking/rental/transaction |
| `profiles` | Telepon, alamat, tanggal lahir | `user_id` unik |
| `units` | Unit fisik console | N:M category, 1:N booking/rental |
| `categories` | Kelompok console | N:M unit melalui `category_unit` |
| `combos` | Paket durasi/controller/harga | 1:N rental |
| `bookings` | Reservasi jadwal dan metode delivery | N:1 user dan unit, maksimal 1 rental |
| `rentals` | Penyewaan aktual | N:1 user/unit/combo, 1:1 transaction |
| `rental_extensions` | Pengajuan perpanjangan | N:1 rental dan reviewer |
| `fines` | Denda terlambat/kerusakan | N:1 rental |
| `deliveries` | Antar keluar/penjemputan kembali | N:1 rental |
| `transactions` | Invoice dan total pembayaran | 1:1 rental, N:1 user |

Status penting:

```text
users.role              : admin, user
units.status            : available, booked, rented, returned, maintenance
bookings.status         : pending, confirmed, cancelled, completed, expired
rentals.status          : pending, active, overdue, returned, cancelled
transactions/fines      : pending, paid, cancelled, refunded
extensions.status       : pending, approved, rejected
deliveries.type         : delivery_out, delivery_return
deliveries.method       : pickup, delivery
deliveries.status       : ready_for_pickup, waiting, in_transit, received,
                          picked_up, returned_to_outlet, cancelled
```

Cardinality penting untuk dijelaskan kepada asesor:

```text
users 1──1 profiles
users 1──N bookings  N──1 units
users 1──N rentals   N──1 units
units N──M categories (category_unit)
bookings 1──0..1 rentals
rentals 1──1 transactions
rentals 1──N fines / deliveries / rental_extensions
```

## 3. DML dasar: INSERT, SELECT, UPDATE, DELETE

### Anggota dan profile atomic

Password contoh harus berupa hash bcrypt dari aplikasi, bukan teks asli.

```sql
START TRANSACTION;

INSERT INTO users (name, email, password, role, created_at, updated_at)
VALUES ('Budi Console', 'budi@example.com', '$2y$12$HASH_BCRYPT_DARI_LARAVEL', 'user', NOW(), NOW());

SET @new_user_id = LAST_INSERT_ID();

INSERT INTO profiles (user_id, phone, address, date_of_birth, created_at, updated_at)
VALUES (@new_user_id, '081234567890', 'Bandung', '2002-01-10', NOW(), NOW());

COMMIT;
```

```sql
SELECT u.id, u.name, u.email, u.role, p.phone, p.address, p.date_of_birth
FROM users u
LEFT JOIN profiles p ON p.user_id = u.id
WHERE u.role = 'user'
ORDER BY u.created_at DESC;

UPDATE users u
JOIN profiles p ON p.user_id = u.id
SET u.name = 'Budi Diperbarui', p.phone = '0899999999', u.updated_at = NOW(), p.updated_at = NOW()
WHERE u.id = @user_id AND u.role = 'user';

-- Hanya boleh dihapus jika tidak memiliki rental aktif/overdue.
DELETE u
FROM users u
WHERE u.id = @user_id
  AND u.role = 'user'
  AND NOT EXISTS (
      SELECT 1 FROM rentals r
      WHERE r.user_id = u.id AND r.status IN ('active', 'overdue')
  );
```

### Unit, kategori, dan pivot N:M

```sql
INSERT INTO units (name, code, description, daily_price, max_players, status, created_at, updated_at)
VALUES ('PlayStation 5', 'PS5-099', 'DualSense + kabel', 50000, 4, 'available', NOW(), NOW());
SET @new_unit_id = LAST_INSERT_ID();

INSERT INTO categories (name, slug, description, created_at, updated_at)
VALUES ('Tournament', 'tournament', 'Cocok untuk kompetisi', NOW(), NOW());
SET @new_category_id = LAST_INSERT_ID();

INSERT INTO category_unit (category_id, unit_id)
VALUES (@new_category_id, @new_unit_id);

SELECT u.id, u.code, u.name, u.daily_price, u.max_players, u.status,
       GROUP_CONCAT(c.name ORDER BY c.name SEPARATOR ', ') AS categories
FROM units u
LEFT JOIN category_unit cu ON cu.unit_id = u.id
LEFT JOIN categories c ON c.id = cu.category_id
GROUP BY u.id
ORDER BY u.code;
```

### Combo

```sql
INSERT INTO combos
    (name, slug, description, duration_days, controller_count, price, is_active, created_at, updated_at)
VALUES
    ('Bakso Tournament', 'bakso-tournament', 'Paket kompetisi', 3, 4, 175000, 1, NOW(), NOW());

UPDATE combos SET price = 165000, updated_at = NOW() WHERE slug = 'bakso-tournament';
UPDATE combos SET is_active = 0, updated_at = NOW() WHERE id = 1;
```

## 4. Query per halaman dan interaksi

### Landing page

```sql
SELECT * FROM units WHERE status = 'available' ORDER BY id LIMIT 6;
SELECT * FROM combos WHERE is_active = 1 ORDER BY id LIMIT 3;

SELECT
  (SELECT COUNT(*) FROM units) AS units,
  (SELECT COUNT(*) FROM users WHERE role = 'user') AS members,
  (SELECT COUNT(*) FROM rentals) AS rentals,
  (SELECT COUNT(*) FROM categories) AS categories;
```

### Dashboard anggota

```sql
SELECT
  SUM(r.status IN ('active','overdue')) AS active_rentals,
  SUM(r.status = 'pending') AS pending_rentals,
  SUM(r.status = 'returned') AS completed_rentals,
  COALESCE(SUM(CASE WHEN r.status = 'returned' THEN r.duration_days ELSE 0 END), 0) AS total_days
FROM rentals r
WHERE r.user_id = @user_id;
```

### Dashboard admin — seluruh kartu statistik

```sql
SELECT
  (SELECT COUNT(*) FROM rentals) AS total_rental,
  (SELECT COUNT(*) FROM rentals WHERE status IN ('active','overdue')) AS unit_aktif_disewa,
  (SELECT COUNT(*) FROM units WHERE status = 'available') AS unit_tersedia,
  (SELECT COUNT(*) FROM users WHERE role = 'user') AS total_anggota,
  (SELECT COALESCE(SUM(duration_days),0) FROM rentals) AS total_hari_sewa,
  (SELECT COALESCE(SUM(total_amount),0) FROM transactions WHERE status = 'paid') AS total_pendapatan,
  (SELECT COALESCE(SUM(amount),0) FROM fines) AS total_denda;
```

Unit terlaris dan anggota teraktif:

```sql
SELECT u.id, u.code, u.name, COUNT(r.id) AS rental_count
FROM units u LEFT JOIN rentals r ON r.unit_id = u.id
GROUP BY u.id
ORDER BY rental_count DESC, u.name
LIMIT 1;

SELECT u.id, u.name, u.email, COUNT(r.id) AS rental_count
FROM users u LEFT JOIN rentals r ON r.user_id = u.id
WHERE u.role = 'user'
GROUP BY u.id
ORDER BY rental_count DESC, u.name
LIMIT 1;
```

Proporsi pickup vs delivery:

```sql
SELECT method, COUNT(*) AS total,
       ROUND(COUNT(*) * 100.0 / SUM(COUNT(*)) OVER (), 2) AS percentage
FROM deliveries
GROUP BY method;
```

Heatmap harian dan hari puncak:

```sql
SELECT start_date, COUNT(*) AS total_rental, SUM(duration_days) AS total_days
FROM rentals
GROUP BY start_date
ORDER BY start_date;

SELECT start_date, COUNT(*) AS total_rental
FROM rentals
GROUP BY start_date
ORDER BY total_rental DESC, start_date
LIMIT 1;
```

### Katalog, Mabar Capacity, dan SmartPick

Pencarian biasa:

```sql
SELECT DISTINCT u.*
FROM units u
LEFT JOIN category_unit cu ON cu.unit_id = u.id
WHERE (@keyword IS NULL OR u.name LIKE CONCAT('%', @keyword, '%') OR u.code LIKE CONCAT('%', @keyword, '%'))
  AND (@players IS NULL OR u.max_players >= @players)
  AND (@category_id IS NULL OR cu.category_id = @category_id)
ORDER BY u.daily_price, u.name;
```

SmartPick adalah rule-based scoring, bukan AI. Empat kriteria masing-masing bernilai 25 poin.

```sql
SELECT u.id, u.code, u.name, u.status, u.max_players, u.daily_price,
       u.daily_price * @duration AS estimated_price,
       ROUND(100 * (
           (u.max_players >= @players) +
           (u.daily_price * @duration <= @budget) +
           EXISTS (SELECT 1 FROM category_unit cu WHERE cu.unit_id = u.id AND cu.category_id = @category_id) +
           (@duration BETWEEN 1 AND 5)
       ) / 4, 0) AS smartpick_score,
       CASE WHEN u.status = 'available'
                  AND u.max_players >= @players
                  AND u.daily_price * @duration <= @budget
                  AND EXISTS (SELECT 1 FROM category_unit cu WHERE cu.unit_id = u.id AND cu.category_id = @category_id)
                  AND @duration BETWEEN 1 AND 5
            THEN 1 ELSE 0 END AS is_best_match
FROM units u
ORDER BY smartpick_score DESC, (u.status = 'available') DESC, estimated_price ASC;
```

### Live availability dan pencegahan bentrok

Dua rentang tanggal bentrok jika `existing_start <= requested_end` dan `existing_end >= requested_start`.

```sql
SELECT u.id, u.code, u.status,
       CASE
         WHEN u.status = 'maintenance' THEN 0
         WHEN EXISTS (
           SELECT 1 FROM bookings b
           WHERE b.unit_id = u.id
             AND b.status IN ('pending','confirmed')
             AND b.start_date <= @end_date AND b.end_date >= @start_date
         ) THEN 0
         WHEN EXISTS (
           SELECT 1 FROM rentals r
           WHERE r.unit_id = u.id
             AND r.status IN ('pending','active','overdue')
             AND r.start_date <= @end_date AND r.due_date >= @start_date
         ) THEN 0
         ELSE 1
       END AS is_available
FROM units u
WHERE u.id = @unit_id;
```

### Booking + rental + invoice atomic

Ini ekuivalen `CreateBooking` → `StartRental` → `RecalculateTransaction`. `FOR UPDATE` mencegah dua request mengambil unit yang sama secara bersamaan.

```sql
START TRANSACTION;

SELECT id, status FROM units WHERE id = @unit_id FOR UPDATE;

SET @duration_days = DATEDIFF(@end_date, @start_date) + 1;

-- Aplikasi harus menolak jika hasil bukan 1..5.
SELECT CASE WHEN @duration_days BETWEEN 1 AND 5 THEN 'VALID' ELSE 'INVALID' END AS duration_check;

SELECT COUNT(*) INTO @conflict_count
FROM (
  SELECT id FROM bookings
  WHERE unit_id = @unit_id AND status IN ('pending','confirmed')
    AND start_date <= @end_date AND end_date >= @start_date
  UNION ALL
  SELECT id FROM rentals
  WHERE unit_id = @unit_id AND status IN ('pending','active','overdue')
    AND start_date <= @end_date AND due_date >= @start_date
) conflicts;

SELECT COUNT(*) INTO @active_count
FROM rentals
WHERE user_id = @user_id AND status IN ('active','overdue');

-- Lanjut hanya jika @conflict_count = 0, @active_count < 2, dan durasi 1..5.
SET @booking_code = CONCAT('BKG-', UPPER(SUBSTRING(REPLACE(UUID(),'-',''),1,10)));
SET @rental_code = CONCAT('RNT-', UPPER(SUBSTRING(REPLACE(UUID(),'-',''),1,10)));
SET @invoice_number = CONCAT('INV-', UPPER(SUBSTRING(REPLACE(UUID(),'-',''),1,10)));

INSERT INTO bookings
  (booking_code,user_id,unit_id,start_date,end_date,duration_days,status,notes,
   delivery_method,delivery_address,contact_number,delivery_fee,created_at,updated_at)
SELECT @booking_code,@user_id,@unit_id,@start_date,@end_date,@duration_days,'pending',NULL,
       @delivery_method,
       CASE WHEN @delivery_method='delivery' THEN p.address ELSE NULL END,
       CASE WHEN @delivery_method='delivery' THEN p.phone ELSE NULL END,
       CASE WHEN @delivery_method='delivery' THEN 15000 ELSE 0 END,NOW(),NOW()
FROM profiles p WHERE p.user_id=@user_id;
SET @booking_id = LAST_INSERT_ID();

INSERT INTO rentals
  (rental_code,user_id,unit_id,booking_id,start_date,due_date,duration_days,daily_price,subtotal,status,created_at,updated_at)
SELECT @rental_code,@user_id,u.id,@booking_id,@start_date,@end_date,@duration_days,
       u.daily_price,u.daily_price*@duration_days,'pending',NOW(),NOW()
FROM units u WHERE u.id=@unit_id;
SET @rental_id = LAST_INSERT_ID();

UPDATE units SET status='rented', updated_at=NOW() WHERE id=@unit_id;
UPDATE bookings SET status='confirmed', updated_at=NOW() WHERE id=@booking_id;

INSERT INTO deliveries
  (rental_id,type,method,address,contact_number,delivery_fee,status,scheduled_at,created_at,updated_at)
SELECT @rental_id,'delivery_out',b.delivery_method,b.delivery_address,b.contact_number,b.delivery_fee,
       IF(b.delivery_method='delivery','waiting','ready_for_pickup'),NOW(),NOW(),NOW()
FROM bookings b WHERE b.id=@booking_id;

INSERT INTO transactions
  (invoice_number,rental_id,user_id,rental_amount,fine_amount,delivery_fee,discount_amount,total_amount,status,created_at,updated_at)
SELECT @invoice_number,@rental_id,@user_id,r.subtotal,0,b.delivery_fee,0,r.subtotal+b.delivery_fee,'pending',NOW(),NOW()
FROM rentals r JOIN bookings b ON b.id=r.booking_id WHERE r.id=@rental_id;

COMMIT;
-- Jika validasi gagal: ROLLBACK;
```

### Pembayaran dan serah-terima

```sql
UPDATE transactions
SET status='paid', paid_at=NOW(), updated_at=NOW()
WHERE rental_id=@rental_id AND status='pending';

START TRANSACTION;
SELECT status INTO @payment_status FROM transactions WHERE rental_id=@rental_id FOR UPDATE;
UPDATE rentals SET status='active', updated_at=NOW()
WHERE id=@rental_id AND status='pending' AND @payment_status='paid';
UPDATE bookings b JOIN rentals r ON r.booking_id=b.id
SET b.status='completed', b.updated_at=NOW()
WHERE r.id=@rental_id AND @payment_status='paid';
COMMIT;
```

### Pembatalan booking

```sql
UPDATE bookings
SET status='cancelled', updated_at=NOW()
WHERE id=@booking_id AND user_id=@user_id AND status='pending';
```

### Perpanjangan

```sql
SET @requested_due_date='2026-08-19';
INSERT INTO rental_extensions
  (rental_id,requested_due_date,additional_days,additional_cost,reason,status,created_at,updated_at)
SELECT r.id,@requested_due_date,DATEDIFF(@requested_due_date,r.due_date),
       DATEDIFF(@requested_due_date,r.due_date)*r.daily_price,
       'Perpanjangan masa rental','pending',NOW(),NOW()
FROM rentals r
WHERE r.id=@rental_id AND @requested_due_date>r.due_date;

-- Persetujuan admin
START TRANSACTION;
SELECT * FROM rental_extensions WHERE id=@extension_id FOR UPDATE;
UPDATE rental_extensions
SET status='approved',reviewed_by=@admin_id,reviewed_at=NOW(),updated_at=NOW()
WHERE id=@extension_id AND status='pending';
UPDATE rentals r JOIN rental_extensions e ON e.rental_id=r.id
SET r.due_date=e.requested_due_date,
    r.duration_days=r.duration_days+e.additional_days,
    r.subtotal=r.subtotal+e.additional_cost,
    r.updated_at=NOW()
WHERE e.id=@extension_id AND e.status='approved';
COMMIT;
```

### Return, keterlambatan, denda, dan kalkulasi transaksi

```sql
START TRANSACTION;
SELECT * FROM rentals WHERE id=@rental_id FOR UPDATE;
SET @returned_at='2026-08-20';
SET @daily_fine=10000;

INSERT INTO fines (rental_id,type,late_days,amount,reason,status,created_at,updated_at)
SELECT id,'late',GREATEST(DATEDIFF(@returned_at,due_date),0),
       GREATEST(DATEDIFF(@returned_at,due_date),0)*@daily_fine,
       'Denda keterlambatan','pending',NOW(),NOW()
FROM rentals
WHERE id=@rental_id AND status IN ('active','overdue') AND @returned_at>due_date;

UPDATE rentals
SET status='returned',returned_at=@returned_at,return_notes='Unit lengkap',updated_at=NOW()
WHERE id=@rental_id AND status IN ('active','overdue');

UPDATE units u JOIN rentals r ON r.unit_id=u.id
SET u.status='available',u.updated_at=NOW()
WHERE r.id=@rental_id;

UPDATE transactions t
JOIN rentals r ON r.id=t.rental_id
SET t.rental_amount=r.subtotal,
    t.fine_amount=(SELECT COALESCE(SUM(f.amount),0) FROM fines f WHERE f.rental_id=r.id),
    t.delivery_fee=(SELECT COALESCE(SUM(d.delivery_fee),0) FROM deliveries d WHERE d.rental_id=r.id),
    t.total_amount=GREATEST(r.subtotal
      +(SELECT COALESCE(SUM(f.amount),0) FROM fines f WHERE f.rental_id=r.id)
      +(SELECT COALESCE(SUM(d.delivery_fee),0) FROM deliveries d WHERE d.rental_id=r.id)
      -t.discount_amount,0),
    t.updated_at=NOW()
WHERE r.id=@rental_id;
COMMIT;
```

Denda kerusakan manual:

```sql
INSERT INTO fines (rental_id,type,late_days,amount,reason,status,created_at,updated_at)
VALUES (@rental_id,'damage',0,50000,'Controller rusak','pending',NOW(),NOW());
```

### Delivery dan ongkir

```sql
INSERT INTO deliveries
  (rental_id,type,method,address,contact_number,delivery_fee,status,scheduled_at,created_at,updated_at)
VALUES
  (@rental_id,'delivery_return','delivery','Bandung','08123456789',15000,'waiting',NOW(),NOW(),NOW())
ON DUPLICATE KEY UPDATE
  method=VALUES(method),address=VALUES(address),contact_number=VALUES(contact_number),
  delivery_fee=VALUES(delivery_fee),status=VALUES(status),scheduled_at=VALUES(scheduled_at),updated_at=NOW();

UPDATE deliveries
SET status='in_transit',courier_name='Kurir Bakso',updated_at=NOW()
WHERE id=@delivery_id;
```

Catatan: migration saat ini hanya memiliki index biasa `(rental_id,type)`, bukan unique key. Agar `ON DUPLICATE KEY UPDATE` menjamin satu jenis delivery per rental:

```sql
ALTER TABLE deliveries ADD UNIQUE KEY uq_delivery_rental_type (rental_id,type);
```

### Riwayat, invoice, dan cetak

```sql
SELECT r.rental_code,u.name AS member,u.email,un.code AS unit_code,un.name AS unit_name,
       r.start_date,r.due_date,r.returned_at,r.duration_days,r.status,
       t.invoice_number,t.rental_amount,t.fine_amount,t.delivery_fee,t.discount_amount,t.total_amount,t.status AS payment_status
FROM rentals r
JOIN users u ON u.id=r.user_id
JOIN units un ON un.id=r.unit_id
LEFT JOIN transactions t ON t.rental_id=r.id
WHERE r.user_id=@user_id AND r.status='returned'
ORDER BY r.created_at DESC;
```

### Bakso Rank dan leaderboard

```sql
SELECT u.id,u.name,u.email,COALESCE(SUM(r.duration_days),0) AS total_days,
       CASE
         WHEN COALESCE(SUM(r.duration_days),0)>30 THEN 'Bakso Legend'
         WHEN COALESCE(SUM(r.duration_days),0)>=16 THEN 'Bakso Pro'
         WHEN COALESCE(SUM(r.duration_days),0)>=6 THEN 'Bakso Player'
         ELSE 'Bakso Rookie'
       END AS bakso_rank
FROM users u
LEFT JOIN rentals r ON r.user_id=u.id AND r.status='returned'
WHERE u.role='user'
GROUP BY u.id
ORDER BY total_days DESC,u.name
LIMIT 10;
```

## 5. VIEW untuk laporan

```sql
CREATE OR REPLACE VIEW vw_rental_history AS
SELECT r.id,r.rental_code,r.user_id,u.name AS member_name,r.unit_id,un.code AS unit_code,
       r.start_date,r.due_date,r.returned_at,r.duration_days,r.status,
       t.invoice_number,t.total_amount,t.status AS payment_status
FROM rentals r
JOIN users u ON u.id=r.user_id
JOIN units un ON un.id=r.unit_id
LEFT JOIN transactions t ON t.rental_id=r.id;

CREATE OR REPLACE VIEW vw_member_leaderboard AS
SELECT u.id,u.name,u.email,COALESCE(SUM(r.duration_days),0) AS total_days,
       CASE WHEN COALESCE(SUM(r.duration_days),0)>30 THEN 'Bakso Legend'
            WHEN COALESCE(SUM(r.duration_days),0)>=16 THEN 'Bakso Pro'
            WHEN COALESCE(SUM(r.duration_days),0)>=6 THEN 'Bakso Player'
            ELSE 'Bakso Rookie' END AS bakso_rank
FROM users u LEFT JOIN rentals r ON r.user_id=u.id AND r.status='returned'
WHERE u.role='user' GROUP BY u.id;

CREATE OR REPLACE VIEW vw_daily_rental_heatmap AS
SELECT start_date,COUNT(*) AS total_rental,SUM(duration_days) AS total_days
FROM rentals GROUP BY start_date;

SELECT * FROM vw_rental_history ORDER BY id DESC;
SELECT * FROM vw_member_leaderboard ORDER BY total_days DESC LIMIT 10;
SELECT * FROM vw_daily_rental_heatmap ORDER BY start_date;
```

## 6. Stored function

```sql
DELIMITER $$
CREATE FUNCTION fn_calculate_transaction_total(
  p_rental DECIMAL(12,2),p_fine DECIMAL(12,2),
  p_delivery DECIMAL(12,2),p_discount DECIMAL(12,2)
) RETURNS DECIMAL(12,2)
DETERMINISTIC
BEGIN
  RETURN GREATEST(COALESCE(p_rental,0)+COALESCE(p_fine,0)+COALESCE(p_delivery,0)-COALESCE(p_discount,0),0);
END$$

CREATE FUNCTION fn_bakso_rank(p_days INT) RETURNS VARCHAR(30)
DETERMINISTIC
BEGIN
  RETURN CASE WHEN p_days>30 THEN 'Bakso Legend'
              WHEN p_days>=16 THEN 'Bakso Pro'
              WHEN p_days>=6 THEN 'Bakso Player'
              ELSE 'Bakso Rookie' END;
END$$
DELIMITER ;

SELECT fn_calculate_transaction_total(150000,20000,15000,10000) AS total;
SELECT fn_bakso_rank(20) AS rank_name;
```

## 7. Stored procedure recalculation

```sql
DELIMITER $$
CREATE PROCEDURE sp_recalculate_transaction(IN p_rental_id BIGINT)
BEGIN
  UPDATE transactions t
  JOIN rentals r ON r.id=t.rental_id
  SET t.rental_amount=r.subtotal,
      t.fine_amount=(SELECT COALESCE(SUM(amount),0) FROM fines WHERE rental_id=r.id),
      t.delivery_fee=(SELECT COALESCE(SUM(delivery_fee),0) FROM deliveries WHERE rental_id=r.id),
      t.total_amount=fn_calculate_transaction_total(
        r.subtotal,
        (SELECT COALESCE(SUM(amount),0) FROM fines WHERE rental_id=r.id),
        (SELECT COALESCE(SUM(delivery_fee),0) FROM deliveries WHERE rental_id=r.id),
        t.discount_amount),
      t.updated_at=NOW()
  WHERE r.id=p_rental_id;
END$$
DELIMITER ;

CALL sp_recalculate_transaction(@rental_id);
```

## 8. Trigger sinkronisasi status unit

Migration terbaru memakai `completed`, padahal enum rental aktual memakai `returned`. Versi MySQL yang konsisten:

```sql
DROP TRIGGER IF EXISTS trg_update_unit_status;
DELIMITER $$
CREATE TRIGGER trg_update_unit_status
AFTER UPDATE ON rentals
FOR EACH ROW
BEGIN
  IF NEW.status <> OLD.status THEN
    UPDATE units
    SET status = CASE
      WHEN NEW.status IN ('pending','active','overdue') THEN 'rented'
      WHEN NEW.status IN ('returned','cancelled') THEN 'available'
      ELSE status
    END,
    updated_at=NOW()
    WHERE id=NEW.unit_id;
  END IF;
END$$
DELIMITER ;
```

Uji trigger secara aman:

```sql
START TRANSACTION;
SELECT unit_id,status FROM rentals WHERE id=@rental_id;
SELECT id,status FROM units WHERE id=(SELECT unit_id FROM rentals WHERE id=@rental_id);
UPDATE rentals SET status='returned' WHERE id=@rental_id;
SELECT id,status FROM units WHERE id=(SELECT unit_id FROM rentals WHERE id=@rental_id);
ROLLBACK;
```

## 9. Index dan optimasi

Index migration yang sudah relevan:

```text
units(name), units(code UNIQUE), units(max_players), units(status)
bookings(unit_id,status,start_date,end_date), bookings(user_id,status)
rentals(unit_id,status,start_date,due_date), rentals(user_id,status)
rental_extensions(rental_id,status)
fines(rental_id,status)
deliveries(rental_id,type), deliveries(method,status)
transactions(rental_id UNIQUE), transactions(user_id,status)
users(email UNIQUE), users(role)
```

Analisis query:

```sql
EXPLAIN ANALYZE
SELECT id FROM rentals
WHERE unit_id=@unit_id
  AND status IN ('pending','active','overdue')
  AND start_date<=@end_date AND due_date>=@start_date;

EXPLAIN ANALYZE
SELECT user_id,SUM(duration_days)
FROM rentals
WHERE status='returned'
GROUP BY user_id
ORDER BY SUM(duration_days) DESC
LIMIT 10;
```

Index tambahan yang layak bila data leaderboard besar:

```sql
CREATE INDEX idx_rentals_status_user_duration ON rentals(status,user_id,duration_days);
```

Jawaban asesor: index mempercepat baca/filter tetapi menambah biaya penyimpanan dan memperlambat INSERT/UPDATE, sehingga dibuat hanya untuk pola query yang terukur lewat `EXPLAIN ANALYZE`.

## 10. COMMIT, ROLLBACK, SAVEPOINT, dan deadlock

```sql
START TRANSACTION;
SELECT * FROM units WHERE id=@unit_id FOR UPDATE;
SAVEPOINT before_booking;
-- INSERT booking/rental/transaksi
-- Jika bagian opsional gagal:
ROLLBACK TO SAVEPOINT before_booking;
-- Jika seluruh invariant terpenuhi:
COMMIT;
-- Jika gagal total:
ROLLBACK;
```

Prinsip locking:

1. Mulai transaksi.
2. Kunci unit dengan `SELECT ... FOR UPDATE`.
3. Cek overlap dan batas dua rental aktif di transaksi yang sama.
4. Tulis booking, rental, delivery, dan transaksi.
5. Commit; exception menyebabkan rollback.
6. Akses row dalam urutan konsisten untuk menurunkan risiko deadlock.

## 11. Hak akses dan keamanan koneksi

Jangan gunakan `root` untuk aplikasi production.

```sql
CREATE USER 'bakso_app'@'127.0.0.1' IDENTIFIED BY 'PASSWORD_KUAT_UNIK';
GRANT SELECT,INSERT,UPDATE,DELETE ON bakso_console.* TO 'bakso_app'@'127.0.0.1';
FLUSH PRIVILEGES;
SHOW GRANTS FOR 'bakso_app'@'127.0.0.1';
```

Untuk menjalankan migration, gunakan user deployment terpisah yang boleh `CREATE`, `ALTER`, `DROP`, `INDEX`, `TRIGGER`, `CREATE ROUTINE`, dan `ALTER ROUTINE`; user aplikasi runtime tidak memerlukan hak tersebut.

Laravel menggunakan prepared statement/binding. Jangan merangkai input user langsung ke SQL:

```php
DB::select('SELECT * FROM units WHERE name LIKE ?', ['%'.$keyword.'%']);
```

## 12. Query pengujian dan data integrity

```sql
-- Booking dengan tanggal tidak valid
SELECT id FROM bookings WHERE end_date<start_date;

-- Rental lebih dari lima hari pada sewa awal
SELECT id,rental_code,duration_days FROM rentals WHERE duration_days>5;

-- Anggota memiliki lebih dari dua rental aktif
SELECT user_id,COUNT(*) total FROM rentals
WHERE status IN ('active','overdue') GROUP BY user_id HAVING COUNT(*)>2;

-- Jadwal rental yang saling bentrok pada unit sama
SELECT a.id AS rental_a,b.id AS rental_b,a.unit_id
FROM rentals a JOIN rentals b ON a.unit_id=b.unit_id AND a.id<b.id
WHERE a.status IN ('pending','active','overdue')
  AND b.status IN ('pending','active','overdue')
  AND a.start_date<=b.due_date AND a.due_date>=b.start_date;

-- Total transaksi yang tidak konsisten
SELECT t.* FROM transactions t
WHERE t.total_amount<>GREATEST(t.rental_amount+t.fine_amount+t.delivery_fee-t.discount_amount,0);

-- Unit available tetapi masih memiliki rental aktif
SELECT u.id,u.code FROM units u
WHERE u.status='available' AND EXISTS (
  SELECT 1 FROM rentals r WHERE r.unit_id=u.id AND r.status IN ('active','overdue')
);
```

## 13. Jawaban singkat untuk asesor

**Mengapa memakai SQL dan Eloquent?**

Eloquent dipakai untuk operasi domain harian agar kode aman dan mudah dipelihara. Raw SQL dipakai untuk query agregat kompleks, view, trigger, procedure, function, locking, dan analisis performa.

**Apa beda DDL, DML, DQL, DCL, dan TCL?**

DDL membentuk schema (`CREATE/ALTER/DROP`), DML mengubah data (`INSERT/UPDATE/DELETE`), DQL membaca (`SELECT`), DCL mengatur hak (`GRANT/REVOKE`), TCL mengatur transaksi (`COMMIT/ROLLBACK/SAVEPOINT`).

**Mengapa booking dibungkus transaction?**

Booking membuat beberapa record yang harus konsisten: booking, rental, delivery, transaction, dan status unit. Jika satu gagal, seluruh perubahan di-rollback.

**Mengapa `FOR UPDATE`?**

Untuk mencegah race condition ketika dua user memilih unit dan jadwal yang sama secara bersamaan. Row unit dikunci sampai commit/rollback.

**Bagaimana overlap diperiksa?**

Rentang bentrok jika tanggal mulai data lama lebih kecil/sama dengan tanggal akhir baru dan tanggal akhir lama lebih besar/sama dengan tanggal mulai baru.

**Mengapa menggunakan trigger?**

Trigger menjaga status unit konsisten walaupun perubahan rental datang dari proses selain controller. Logic utama tetap eksplisit di application service; trigger menjadi defense in depth.

**Apa beda procedure dan function?**

Procedure dipanggil dengan `CALL` dan cocok untuk rangkaian operasi; function mengembalikan satu nilai dan bisa dipakai dalam `SELECT`.

**Bagaimana menguji trigger/procedure tanpa merusak data?**

Mulai transaction, siapkan kondisi, jalankan objek database, verifikasi `SELECT`, lalu `ROLLBACK`.

**Bagaimana keamanan database diterapkan?**

Kredensial berada di `.env`, query memakai parameter binding, password user di-hash, dan user database runtime menerapkan least privilege tanpa hak DDL.

**Bagaimana performa diuji?**

Gunakan data representatif, ukur `EXPLAIN ANALYZE`, periksa index yang dipakai dan rows examined, lalu bandingkan sebelum/sesudah index.

**Apakah SmartPick memakai AI?**

Tidak. SmartPick adalah algoritma rule-based dengan scoring pemain, durasi, budget, kategori, dan bonus ketersediaan. Keuntungannya deterministik, transparan, murah, dan mudah diuji.

## 14. Urutan demo SQL 5–10 menit

1. `SHOW TABLES`, `SHOW CREATE TABLE rentals`, dan jelaskan FK/index.
2. Jalankan query dashboard admin.
3. Jalankan SmartPick dan query availability.
4. Tunjukkan overlap rule serta `EXPLAIN ANALYZE`.
5. Demonstrasikan transaction dengan `FOR UPDATE`, lalu `ROLLBACK` agar data tetap bersih.
6. Tampilkan `vw_member_leaderboard` dan `vw_daily_rental_heatmap`.
7. Panggil `fn_bakso_rank()` dan `sp_recalculate_transaction()`.
8. Uji trigger di dalam transaction dan akhiri dengan `ROLLBACK`.
9. Tutup dengan `SHOW GRANTS` dan jelaskan least privilege.

## 15. Pemetaan bukti unit kompetensi

| Kriteria SQL/database | Bukti pada cheat sheet |
|---|---|
| DML dan isi tabel | Bagian 3–4 |
| Index | Bagian 9 |
| View | Bagian 5 |
| JOIN/operasi relasional | Dashboard, history, invoice, leaderboard |
| Stored procedure | `sp_recalculate_transaction` |
| Function | `fn_calculate_transaction_total`, `fn_bakso_rank` |
| Trigger | `trg_update_unit_status` |
| Commit/rollback | Booking, return, bagian 10 |
| Akses basis data | Eloquent, raw SQL, parameter binding |
| Koneksi aman | `.env`, least privilege, user runtime/deployment |
| Pengujian query | Integrity queries dan `EXPLAIN ANALYZE` |

---

### Catatan audit project terbaru

1. `.env` aktif sudah memakai MySQL `bakso_console`; rahasia tidak dicantumkan di dokumen.
2. Trigger migration saat ini memeriksa rental `completed`, sedangkan status aktual adalah `returned`. SQL rekomendasi di bagian 8 sudah diperbaiki.
3. `deliveries` belum memiliki unique constraint `(rental_id,type)`, padahal aplikasi memakai `updateOrCreate` berdasarkan pasangan tersebut. Unique key direkomendasikan untuk menjamin invariant di level database.
4. Maksimal dua rental aktif dan maksimal lima hari saat ini dijaga application service. Untuk fleksibilitas workflow extension, aturan tersebut tidak dipaksakan sebagai `CHECK` global pada tabel rental.
