# Shared Data Foundation Design

**Date:** 2026-08-10  
**Project:** Bakso Console

## Objective

Create one shared database and Eloquent contract before Mizan and Nable begin their feature assignments. Models, migrations, relationships, status values, and important constraints are owned by the shared foundation and must not be independently redefined in feature branches.

## Chosen Approach

Use a complete, centralized schema for all PRD features. This costs more setup time than creating tables per sprint, but prevents both developers from inventing different columns, relationships, and status values. It also lets later features use stable data from the first sprint onward.

The alternatives rejected were:

- Schema per sprint, because parallel migrations can conflict and later features can force breaking changes.
- A minimal PRD-only schema, because unspecified fields and relationships would still be decided independently by each developer.

## Data Model

The foundation contains these application tables:

- `users`: authentication identity and the `admin` or `user` role.
- `profiles`: one profile per user, containing contact and address information.
- `units`: physical console inventory with a unique code, daily price, player capacity, and availability status.
- `categories`: reusable unit classifications.
- `category_unit`: many-to-many relationship between units and categories.
- `combos`: reusable Bakso Combo offers with duration, controller count, price, and active state.
- `bookings`: a member's reservation of one unit for a date range.
- `rentals`: the central rental record for one member and one unit, optionally originating from a booking or combo.
- `rental_extensions`: extension requests and their approval state.
- `fines`: late or damage charges associated with a rental.
- `deliveries`: outbound delivery or return pickup activity for a rental.
- `transactions`: the financial summary and manual payment state for a rental.

Laravel's standard password reset, session, cache, and job tables remain unchanged.

## Relationships and Data Flow

A user has one profile and many bookings and rentals. A unit belongs to many categories and can appear in many bookings and rentals over time. A booking may create one rental; a rental may reference one combo. A rental owns its extension requests, fines, delivery activities, and transaction.

The primary flow is:

```text
User -> Booking -> Rental -> Return
                    |  |  |  |
                    |  |  |  +-> Fine
                    |  |  +----> Delivery / return pickup
                    |  +-------> Extension
                    +----------> Transaction
```

One rental represents one physical unit. The maximum of two active units is therefore enforced by counting active rentals for the user. Availability and date-overlap rules are domain validations that query bookings and rentals; they cannot be represented by a simple unique database constraint.

## Status Contract

Roles and workflow states use PHP string-backed enums. Database columns remain strings for compatibility across SQLite and MySQL. Models cast these columns to their corresponding enum.

The shared status groups cover:

- user role;
- unit availability;
- booking lifecycle;
- rental lifecycle;
- extension approval;
- fine payment;
- transaction payment;
- delivery type, method, and lifecycle.

Feature code must use these enums rather than introducing raw status strings.

## Derived Features

No separate persistence is needed for Smart Timer, Rental Warning, Live Availability, Bakso Rank, Rental History, Analytics, or Heatmap. These are derived from rental dates, statuses, transactions, fines, units, and delivery records. Avoiding snapshot tables prevents duplicated or stale data.

Bakso Combo has its own table because it is admin-managed reusable data. For v1, controller quantity is stored directly on the combo; a complete accessory inventory system is outside the PRD and is intentionally not introduced.

## Integrity and Deletion Rules

Unique constraints protect user email, unit code, category name/slug, combo slug, booking code, rental code, and transaction invoice number. Foreign keys and targeted indexes support relationship integrity and common date/status queries.

Historical rental and financial records must not disappear through cascading deletion of users or units. Dependent operational records that have no meaning without their rental may cascade when the rental itself is intentionally deleted during development or test reset.

Conditional rules such as a required address for delivery, maximum rental duration, two active rentals per member, and non-overlapping booking dates remain application validations because they depend on multiple columns or records.

## Models

Every application table receives an Eloquent model with:

- explicit mass-assignable attributes;
- date, decimal, boolean, and enum casts;
- typed relationship methods;
- factories only where test data requires them.

The existing `User` model is extended with role and relationships while preserving password hashing and hidden authentication fields.

## Verification

Implementation begins with tests that define the schema and model contract. Verification includes:

- migration from an empty SQLite test database;
- required tables, columns, indexes, and foreign keys;
- Eloquent relationship types and keys;
- enum, date, decimal, and boolean casts;
- full `migrate:fresh` and project test suite;
- Laravel Pint formatting.

## Team Ownership

The initial data foundation is created and reviewed before feature work is split. Afterwards, both developers consume the shared models and enums. Any schema change must be coordinated and added as a new migration; existing shared migrations must not be edited independently after both branches begin feature development.

`Pembagian_Tugas_Bakso_Console.md` will explicitly record this shared-foundation rule and remove migration/model ownership from either individual developer.
