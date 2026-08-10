# Shared Data Foundation Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Build and verify the complete shared Laravel database, enum, and Eloquent model contract before two developers begin feature work.

**Architecture:** Use ordered Laravel migrations for normalized application tables, string-backed PHP enums for every shared workflow state, and Eloquent models as the single relationship/cast contract. Derived features query transactional data instead of persisting duplicate rank, timer, analytics, availability, or history records.

**Tech Stack:** PHP 8.3, Laravel 13, Eloquent ORM, Laravel migrations, Pest 5, SQLite test database, Laravel Pint.

---

### Task 1: Define the schema contract with failing tests

**Files:**
- Create: `tests/Feature/Database/SharedSchemaTest.php`

**Step 1: Write the failing table-presence test**

Create a Pest feature test using `RefreshDatabase` that asserts the presence of `profiles`, `units`, `categories`, `category_unit`, `combos`, `bookings`, `rentals`, `rental_extensions`, `fines`, `deliveries`, and `transactions`.

**Step 2: Write failing required-column tests**

Use `Schema::hasColumns()` to define the agreed contract:

- `users`: `role`
- `profiles`: `user_id`, `phone`, `address`, `date_of_birth`
- `units`: `name`, `code`, `description`, `daily_price`, `max_players`, `status`
- `categories`: `name`, `slug`, `description`
- `combos`: `name`, `slug`, `description`, `duration_days`, `controller_count`, `price`, `is_active`
- `bookings`: `booking_code`, `user_id`, `unit_id`, `start_date`, `end_date`, `duration_days`, `status`, `notes`
- `rentals`: `rental_code`, `user_id`, `unit_id`, `booking_id`, `combo_id`, `start_date`, `due_date`, `duration_days`, `daily_price`, `subtotal`, `status`, `returned_at`, `return_notes`
- `rental_extensions`: `rental_id`, `requested_due_date`, `additional_days`, `additional_cost`, `reason`, `status`, `reviewed_by`, `reviewed_at`, `review_notes`
- `fines`: `rental_id`, `type`, `late_days`, `amount`, `reason`, `status`, `paid_at`
- `deliveries`: `rental_id`, `type`, `method`, `address`, `contact_number`, `delivery_fee`, `courier_name`, `status`, `scheduled_at`, `completed_at`
- `transactions`: `invoice_number`, `rental_id`, `user_id`, `rental_amount`, `fine_amount`, `delivery_fee`, `discount_amount`, `total_amount`, `payment_method`, `status`, `paid_at`, `notes`

**Step 3: Run the tests and verify failure**

Run: `php artisan test tests/Feature/Database/SharedSchemaTest.php`

Expected: FAIL because application tables and columns do not exist.

**Step 4: Commit the contract test**

```bash
git add tests/Feature/Database/SharedSchemaTest.php
git commit -m "test: define shared database contract"
```

### Task 2: Create shared enum contracts

**Files:**
- Create: `app/Enums/UserRole.php`
- Create: `app/Enums/UnitStatus.php`
- Create: `app/Enums/BookingStatus.php`
- Create: `app/Enums/RentalStatus.php`
- Create: `app/Enums/ExtensionStatus.php`
- Create: `app/Enums/FineType.php`
- Create: `app/Enums/PaymentStatus.php`
- Create: `app/Enums/DeliveryType.php`
- Create: `app/Enums/DeliveryMethod.php`
- Create: `app/Enums/DeliveryStatus.php`
- Test: `tests/Unit/Enums/SharedEnumsTest.php`

**Step 1: Write failing enum-value tests**

Assert the exact string values:

- `UserRole`: `admin`, `user`
- `UnitStatus`: `available`, `booked`, `rented`, `returned`, `maintenance`
- `BookingStatus`: `pending`, `confirmed`, `cancelled`, `completed`, `expired`
- `RentalStatus`: `pending`, `active`, `overdue`, `returned`, `cancelled`
- `ExtensionStatus`: `pending`, `approved`, `rejected`
- `FineType`: `late`, `damage`, `other`
- `PaymentStatus`: `pending`, `paid`, `cancelled`, `refunded`
- `DeliveryType`: `delivery_out`, `delivery_return`
- `DeliveryMethod`: `pickup`, `delivery`
- `DeliveryStatus`: `ready_for_pickup`, `waiting`, `in_transit`, `received`, `picked_up`, `returned_to_outlet`, `cancelled`

**Step 2: Run the enum test and verify failure**

Run: `php artisan test tests/Unit/Enums/SharedEnumsTest.php`

Expected: FAIL because enum classes do not exist.

**Step 3: Implement string-backed enums**

Create each enum under namespace `App\Enums` using `enum Name: string` and the exact values above.

**Step 4: Run the enum test**

Run: `php artisan test tests/Unit/Enums/SharedEnumsTest.php`

Expected: PASS.

**Step 5: Commit**

```bash
git add app/Enums tests/Unit/Enums/SharedEnumsTest.php
git commit -m "feat: add shared workflow enums"
```

### Task 3: Implement master-data migrations

**Files:**
- Modify: `database/migrations/0001_01_01_000000_create_users_table.php`
- Create: `database/migrations/2026_08_10_000100_create_profiles_table.php`
- Create: `database/migrations/2026_08_10_000200_create_units_table.php`
- Create: `database/migrations/2026_08_10_000300_create_categories_table.php`
- Create: `database/migrations/2026_08_10_000400_create_category_unit_table.php`
- Create: `database/migrations/2026_08_10_000500_create_combos_table.php`
- Test: `tests/Feature/Database/SharedSchemaTest.php`

**Step 1: Add role to users**

Add `role` as a string defaulting to `user`, with an index.

**Step 2: Create profiles**

Use a unique `user_id`, nullable phone/address/date of birth, timestamps, and cascade deletion because a profile has no identity without its user.

**Step 3: Create units and categories**

Use unique unit codes and category names/slugs. Use `decimal(12, 2)` for price, unsigned small integer for capacity, a string status defaulting to `available`, timestamps, and indexes on searchable/status fields.

**Step 4: Create category pivot and combos**

Use a composite primary key for `category_unit` and cascades on both master records. Give combos a unique slug, positive duration/controller fields, `decimal(12, 2)` price, `is_active` default true, and timestamps.

**Step 5: Run schema tests**

Run: `php artisan test tests/Feature/Database/SharedSchemaTest.php`

Expected: Remaining transactional table assertions fail, while master-table assertions pass.

**Step 6: Commit**

```bash
git add database/migrations tests/Feature/Database/SharedSchemaTest.php
git commit -m "feat: add shared master data schema"
```

### Task 4: Implement booking and rental migrations

**Files:**
- Create: `database/migrations/2026_08_10_000600_create_bookings_table.php`
- Create: `database/migrations/2026_08_10_000700_create_rentals_table.php`
- Create: `database/migrations/2026_08_10_000800_create_rental_extensions_table.php`
- Create: `database/migrations/2026_08_10_000900_create_fines_table.php`
- Test: `tests/Feature/Database/SharedSchemaTest.php`

**Step 1: Create bookings**

Use a unique booking code; restricted user/unit foreign keys; date range, stored duration, string status defaulting to `pending`, nullable notes, timestamps; and composite indexes for unit/date/status and user/status queries.

**Step 2: Create rentals**

Use a unique rental code; restricted user/unit foreign keys; nullable unique booking foreign key with null-on-delete; nullable combo foreign key with null-on-delete; date range, prices, string status, nullable returned timestamp and notes; timestamps; and unit/status/date plus user/status indexes.

**Step 3: Create extensions and fines**

Extensions cascade with rentals and reference the reviewing user with null-on-delete. Fines cascade with rentals. Use decimals for monetary values, strings for types/statuses, nullable review/payment timestamps, and useful rental/status indexes.

**Step 4: Run schema tests**

Run: `php artisan test tests/Feature/Database/SharedSchemaTest.php`

Expected: Only delivery and transaction assertions remain failing.

**Step 5: Commit**

```bash
git add database/migrations tests/Feature/Database/SharedSchemaTest.php
git commit -m "feat: add rental workflow schema"
```

### Task 5: Implement delivery and transaction migrations

**Files:**
- Create: `database/migrations/2026_08_10_001000_create_deliveries_table.php`
- Create: `database/migrations/2026_08_10_001100_create_transactions_table.php`
- Test: `tests/Feature/Database/SharedSchemaTest.php`

**Step 1: Create deliveries**

Cascade from rental, store PRD delivery type/method/address/contact/fee/courier/status and nullable schedule/completion timestamps, then index rental/type and method/status.

**Step 2: Create transactions**

Use a unique invoice number and unique rental foreign key, restrict user deletion, store each monetary component as `decimal(12, 2)` with zero defaults, nullable payment method, string payment status, nullable payment timestamp/notes, timestamps, and user/status indexes.

**Step 3: Run schema contract and migration reset**

Run: `php artisan test tests/Feature/Database/SharedSchemaTest.php`

Expected: PASS.

Run: `php artisan migrate:fresh --env=testing`

Expected: All migrations complete successfully.

**Step 4: Commit**

```bash
git add database/migrations tests/Feature/Database/SharedSchemaTest.php
git commit -m "feat: add delivery and transaction schema"
```

### Task 6: Define the Eloquent contract with failing tests

**Files:**
- Create: `tests/Unit/Models/SharedModelContractTest.php`

**Step 1: Write relationship tests**

Instantiate each model and assert relationship object types for:

- `User`: profile, bookings, rentals, reviewed extensions, transactions
- `Profile`: user
- `Unit`: categories, bookings, rentals
- `Category`: units
- `Combo`: rentals
- `Booking`: user, unit, rental
- `Rental`: user, unit, booking, combo, extensions, fines, deliveries, transaction
- `RentalExtension`: rental, reviewer
- `Fine`: rental
- `Delivery`: rental
- `Transaction`: rental, user

**Step 2: Write cast tests**

Assert enum casts and relevant `date`, `datetime`, `decimal:2`, `integer`, and `boolean` casts through each model's cast map.

**Step 3: Run and verify failure**

Run: `php artisan test tests/Unit/Models/SharedModelContractTest.php`

Expected: FAIL because application model classes and relationships do not exist.

**Step 4: Commit the failing contract test**

```bash
git add tests/Unit/Models/SharedModelContractTest.php
git commit -m "test: define shared eloquent contract"
```

### Task 7: Implement shared Eloquent models

**Files:**
- Modify: `app/Models/User.php`
- Create: `app/Models/Profile.php`
- Create: `app/Models/Unit.php`
- Create: `app/Models/Category.php`
- Create: `app/Models/Combo.php`
- Create: `app/Models/Booking.php`
- Create: `app/Models/Rental.php`
- Create: `app/Models/RentalExtension.php`
- Create: `app/Models/Fine.php`
- Create: `app/Models/Delivery.php`
- Create: `app/Models/Transaction.php`
- Test: `tests/Unit/Models/SharedModelContractTest.php`

**Step 1: Extend User and implement master models**

Use Laravel 13 attribute-based `Fillable` where appropriate, relationship return types, enum/date/decimal casts, and existing authentication casts on `User`.

**Step 2: Implement workflow and financial models**

Add the agreed fillable fields, casts, and relationship methods. Explicitly map `RentalExtension::reviewer()` to `reviewed_by`.

**Step 3: Run model tests**

Run: `php artisan test tests/Unit/Models/SharedModelContractTest.php`

Expected: PASS.

**Step 4: Run all focused tests**

Run: `php artisan test tests/Unit/Enums tests/Unit/Models tests/Feature/Database`

Expected: PASS.

**Step 5: Commit**

```bash
git add app/Models tests/Unit/Models/SharedModelContractTest.php
git commit -m "feat: add shared eloquent models"
```

### Task 8: Document shared ownership in the task split

**Files:**
- Modify: `Pembagian_Tugas_Bakso_Console.md`

**Step 1: Add a shared-foundation section**

State that models, enums, and initial migrations are completed before feature branching and are jointly consumed. Remove initial migration/model ownership from Mizan and Nable's individual table lists. Require coordinated additive migrations for later schema changes and prohibit independent edits to the shared initial migrations.

**Step 2: Update sprint responsibilities**

Replace Mizan's “Database Migration + Seeder” Sprint 1 item with consumption/verification of the shared foundation. Note that Nable owns feature queries and delivery behavior, not independent definitions of shared models.

**Step 3: Check documentation diff**

Run: `git diff --check -- Pembagian_Tugas_Bakso_Console.md`

Expected: no whitespace errors.

**Step 4: Commit**

```bash
git add Pembagian_Tugas_Bakso_Console.md
git commit -m "docs: establish shared data ownership"
```

### Task 9: Final verification

**Files:**
- Modify only files required by failures discovered during verification.

**Step 1: Format PHP code**

Run: `vendor/bin/pint --dirty`

Expected: formatter completes successfully.

**Step 2: Re-run migrations from scratch**

Run: `php artisan migrate:fresh --env=testing`

Expected: every migration reports `DONE`.

**Step 3: Run the complete suite**

Run: `composer test`

Expected: all tests pass.

**Step 4: Inspect repository state**

Run: `git status --short && git log --oneline -10`

Expected: only intentional changes, with no uncommitted implementation files.

**Step 5: Commit formatting or verification fixes if needed**

```bash
git add app database tests Pembagian_Tugas_Bakso_Console.md
git commit -m "chore: finalize shared data foundation"
```
