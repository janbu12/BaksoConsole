# Full PRD Implementation Design

**Date:** 2026-08-10  
**Project:** Bakso Console

## Objective

Implement the complete Bakso Console PRD as a demo-ready Laravel application for administrators and members. The application uses the shared data foundation from PR #1, Laravel Blade, and Tailwind CSS. It must cover the full booking-to-return workflow, supporting customer experience features, delivery, loyalty, and business insight without duplicating transactional data.

## Chosen Approach

Use a pragmatic clean-architecture modular monolith. Laravel remains the composition framework, while business decisions are kept outside controllers and views.

This approach is preferred over controller-heavy CRUD because availability, extensions, returns, fines, transactions, and delivery have cross-module invariants. A fully event-driven or framework-independent domain architecture was rejected as unnecessary complexity for the project's size and delivery constraints.

## Architecture

The code is organized into five responsibilities:

- **Presentation:** Blade pages, Blade components, Tailwind styles, and purpose-built view data.
- **HTTP:** Routes, middleware, controllers, and Form Requests.
- **Application:** Single-purpose actions/use cases that coordinate a workflow.
- **Domain:** Business rules, calculations, result values, and domain exceptions.
- **Infrastructure:** Eloquent-backed queries and persistence used by actions and dashboards.

The normal request flow is:

```text
Request -> Form Request -> Controller -> Application Action
                                      |-> Domain Rules
                                      |-> Eloquent Queries
                                      `-> Database Transaction
                                                |
                                                v
                                 Redirect or Blade View + Message
```

Controllers do not contain rental calculations or manipulate multiple aggregates directly. Models provide relationships and casts, while workflow transitions are owned by actions. Interfaces are introduced only where they create a real test or substitution boundary; the application will not wrap every Eloquent call in a generic repository.

## Authentication and Authorization

The application provides server-rendered register, login, and logout flows. New registrations receive the `user` role and a profile. Admin accounts come from seed data. Role middleware protects the admin area, while policies or ownership checks protect user-specific bookings, rentals, extensions, deliveries, history, and profile data.

Authenticated users land on the member dashboard. Admins land on the admin dashboard. Unauthorized access returns 403; guest access redirects to login.

## Member Experience

Members can:

- manage their profile;
- browse and search console units;
- filter by category, capacity, price, and availability;
- receive SmartPick recommendations from player count, duration, category, and budget;
- view Bakso Combo offers;
- create and cancel eligible bookings;
- view active rentals, remaining days, warning state, and due date;
- request rental extensions;
- choose pickup or delivery and provide delivery details;
- view rental history and transaction details;
- see total completed rental days and Bakso Rank.

The catalogue does not trust the stored unit status alone for future dates. Availability queries consider overlapping confirmed bookings and active rentals. Unit status remains the operational current-state indicator.

## Admin Experience

Administrators can:

- manage members and profiles;
- manage units, categories, category assignments, and maintenance status;
- manage Bakso Combo offers;
- review and update bookings;
- start rentals from bookings or create direct rentals;
- approve or reject extensions;
- process returns, late days, damage notes, and fines;
- view and reconcile transaction amounts and mark manual payment status;
- manage pickup/delivery records, assign courier names, enter flat/manual delivery fees, and advance delivery status;
- view and print member rental history;
- view dashboard analytics and a rental heatmap.

Destructive operations are restricted when historical records depend on master data. The UI guides the admin toward deactivation or maintenance status where deletion would violate history.

## Core Workflow

### Booking

The booking use case validates the date range, calculates duration in days, checks the unit schedule, and stores a pending booking. Admin confirmation rechecks availability to prevent a stale request from being approved over another booking.

### Rental

Starting a rental verifies that the member has fewer than two active rentals, the requested period is permitted, and the unit is available. A rental may originate from a booking and may use a combo. The action snapshots daily price/subtotal, marks related booking state, and updates operational unit status inside one database transaction.

### Timer and Warning

Remaining rental time is derived from `due_date` using calendar days. The warning states are safe, ending soon, and overdue. Overdue state and fine suggestions are calculations; changes to persisted workflow state occur through an explicit action or scheduled command, not during a read-only page render.

### Extension

Members submit a requested due date and reason. Approval rechecks future availability and maximum-duration rules, snapshots the additional cost, updates the rental due date, and records reviewer data in one transaction.

### Return and Fine

Admin return processing records actual return time and inspection notes. Late days and late fine are calculated from the due date; damage or other fines can be added explicitly. The unit only becomes available after it has returned to the outlet, including delivery-return workflows.

### Transaction

One transaction summarizes rental amount, fine amount, delivery fee, discount, and total. Payment remains manual. Recalculation is explicit and traceable; controllers do not independently assemble financial totals.

### Pickup and Delivery

Each rental can have an outbound activity and a return activity. Method is pickup or delivery. Delivery address and contact are required for delivery. Admin manually enters a flat fee, assigns a courier name, and changes status. GPS tracking, zone calculation, courier accounts, and per-day courier capacity remain outside v1.

## Smart and Derived Features

- **Live Availability:** operational status plus schedule overlap query.
- **Mabar Capacity:** units where `max_players` satisfies requested player count.
- **SmartPick:** deterministic weighted ranking over capacity fit, matching category, budget fit, availability, and price; results explain why they match.
- **Bakso Rank:** completed rental days map to Rookie (0–5), Player (6–15), Pro (16–30), or Legend (>30).
- **Analytics:** aggregate rentals, active/available units, members, revenue, fines, popular units, active members, and pickup/delivery proportions.
- **Heatmap:** completed/active rental counts grouped by calendar date or start date for a selected period.
- **History:** policy-scoped rental query with printable Blade layout.

These features do not receive snapshot tables in v1. They are query/calculation layers over the shared foundation.

## Error Handling and Consistency

Form validation errors return to the form with field-level messages and preserved input. Expected business conflicts throw dedicated domain exceptions that are converted to readable flash errors. Missing data returns 404 and authorization failures return 403.

Booking confirmation, rental creation, extension approval, delivery transitions that affect rental state, return processing, fine updates, and transaction recalculation use database transactions. Relevant unit/rental rows are locked during competing state changes. Status transitions are explicitly validated; arbitrary status strings from requests are never written directly.

Unexpected exceptions continue through Laravel's normal logging and exception handling. The UI does not expose stack traces in production.

## UI Direction

Use Blade components and Tailwind CSS with a dark console-rental visual identity and orange/red Bakso accents. The application includes:

- a public landing/catalogue entry;
- member top navigation and dashboard cards;
- admin sidebar and responsive content layout;
- consistent form controls, tables, status badges, alerts, pagination, empty states, and confirmation dialogs;
- responsive mobile layouts;
- print-friendly rental history and transaction views;
- accessible labels, focus states, validation summaries, and colour-independent status text.

JavaScript remains small and progressive, used for navigation toggles, confirmations, filters, and chart rendering where needed. Core workflows must remain server-driven.

## Testing Strategy

Development follows TDD:

- unit tests for date overlap, duration, rank, warning, fine, transaction totals, recommendation scoring, and status transitions;
- feature tests for registration/login/logout, roles, policies, CRUD, validation, and every application action;
- integration tests for booking -> confirmation -> rental -> delivery/pickup -> extension -> return -> fine -> transaction;
- query tests for catalogue filters, history scope, analytics, heatmap, and SmartPick;
- route/view smoke tests for admin and member Blade pages;
- seed verification for demo accounts and catalogue data.

Completion requires a fresh migration, complete Pest suite, Laravel Pint, and production Vite build. The final test handoff includes demo credentials and a concise workflow checklist.

## Delivery Sequence

Implementation proceeds in vertical slices:

1. application shell, auth, role security, and seed data;
2. member/profile, unit/category, and combo administration;
3. catalogue, filters, availability, capacity, and SmartPick;
4. booking and rental core;
5. timer, warning, extension, return, fine, and transaction;
6. pickup and delivery;
7. history, rank, analytics, heatmap, and print views;
8. UI consistency, end-to-end verification, and demo hardening.

Each slice includes domain tests, action tests, HTTP tests, Blade views, and a focused commit. Full PRD implementation branches from the shared data foundation so no feature recreates its models, enums, or initial migrations.
