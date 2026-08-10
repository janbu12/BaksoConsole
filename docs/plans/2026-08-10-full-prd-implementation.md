# Full PRD Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Deliver the complete Bakso Console PRD as a tested, responsive Laravel Blade application for members and administrators.

**Architecture:** Build vertical slices on top of the shared data foundation using pragmatic clean architecture. HTTP controllers delegate to application actions, domain services own calculations and state rules, Eloquent query services support read models, and Blade/Tailwind own presentation.

**Tech Stack:** PHP 8.5, Laravel 13, Eloquent, Pest 5, Blade, Tailwind CSS 4, Vite 8, SQLite for tests, MySQL-compatible migrations.

---

## Global TDD and Structure Rules

For every task below:

1. Write the smallest failing unit or feature test first.
2. Run only that test and confirm it fails for the missing behavior.
3. Implement the minimal domain/action/HTTP/view behavior.
4. Run the focused test, then the related module tests.
5. Run `vendor/bin/pint` on touched PHP files.
6. Commit only the task's intentional files.

Production code must follow these boundaries:

- `app/Http`: middleware, requests, controllers.
- `app/Application`: state-changing use cases.
- `app/Domain`: calculations, rules, value objects, domain exceptions.
- `app/Queries`: Eloquent read/query services.
- `resources/views`: Blade presentation and components.

Do not introduce generic repositories around every model. Actions may use Eloquent directly when no substitutable boundary is needed. Controllers must not contain price calculations, availability logic, or multi-model state transitions.

### Task 1: Prepare the full-implementation branch and baseline

**Files:**
- Consume: shared foundation files from `feature/shared-data-foundation`
- Consume: `docs/plans/2026-08-10-full-prd-implementation-design.md`
- Test: existing complete test suite

**Step 1: Create an isolated worktree**

Create `feature/full-prd-implementation` from `origin/feature/shared-data-foundation`, merge the latest `main`, and resolve only documentation ancestry if needed.

**Step 2: Install dependencies and create local environment**

Install Composer/NPM dependencies, create `.env` from `.env.example`, generate an application key, and ensure SQLite is available for tests.

**Step 3: Verify the baseline**

Run the full Pest suite and `php artisan migrate:fresh --env=testing --force`.

Expected: the shared-foundation tests pass and every migration completes.

**Step 4: Commit only if merge resolution created tracked changes**

Commit message: `chore: prepare full PRD implementation`

### Task 2: Build the application shell and reusable Blade components

**Files:**
- Modify: `resources/css/app.css`
- Modify: `resources/js/app.js`
- Create: `resources/views/layouts/app.blade.php`
- Create: `resources/views/layouts/guest.blade.php`
- Create: `resources/views/layouts/admin.blade.php`
- Create: `resources/views/components/alert.blade.php`
- Create: `resources/views/components/badge.blade.php`
- Create: `resources/views/components/button.blade.php`
- Create: `resources/views/components/input.blade.php`
- Create: `resources/views/components/select.blade.php`
- Create: `resources/views/components/empty-state.blade.php`
- Create: `resources/views/components/stat-card.blade.php`
- Create: `tests/Feature/Views/ApplicationShellTest.php`

**Step 1: Write failing shell render tests**

Render guest, member, and admin layouts through test routes. Assert application name, navigation landmark, alert region, mobile navigation control, and Vite assets.

**Step 2: Verify RED**

Run: `php artisan test tests/Feature/Views/ApplicationShellTest.php`

Expected: FAIL because layouts/components do not exist.

**Step 3: Implement the shell**

Create dark responsive layouts with Bakso orange/red accent tokens, accessible focus styles, member top navigation, admin sidebar, flash alerts, validation summary, and consistent controls. JavaScript only toggles responsive navigation and confirmation elements.

**Step 4: Verify and commit**

Run the shell test and `npm run build`.

Commit: `feat: add responsive application shell`

### Task 3: Implement authentication and role authorization

**Files:**
- Create: `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- Create: `app/Http/Controllers/Auth/RegisteredUserController.php`
- Create: `app/Http/Requests/Auth/LoginRequest.php`
- Create: `app/Http/Requests/Auth/RegisterRequest.php`
- Create: `app/Http/Middleware/EnsureUserHasRole.php`
- Modify: `bootstrap/app.php`
- Modify: `routes/web.php`
- Create: `resources/views/auth/login.blade.php`
- Create: `resources/views/auth/register.blade.php`
- Create: `tests/Feature/Auth/AuthenticationTest.php`
- Create: `tests/Feature/Auth/RegistrationTest.php`
- Create: `tests/Feature/Auth/RoleAuthorizationTest.php`

**Step 1: Write failing auth tests**

Cover register (always role `user` plus profile), login, invalid credentials, logout, guest redirects, admin-only route 403 for members, and role-aware post-login destinations.

**Step 2: Verify RED**

Run: `php artisan test tests/Feature/Auth`

Expected: FAIL because auth routes/controllers are missing.

**Step 3: Implement auth actions**

Use Laravel authentication APIs, regenerate sessions on login, invalidate sessions and CSRF tokens on logout, validate unique email/password confirmation, and never accept a role from public registration input.

**Step 4: Register role middleware and routes**

Alias middleware as `role`; protect `/admin/*` with `auth` and `role:admin`; protect member workflows with `auth` and ownership checks.

**Step 5: Verify and commit**

Run auth tests and related shell tests.

Commit: `feat: add authentication and role authorization`

### Task 4: Implement profiles and admin member management

**Files:**
- Create: `app/Application/Profile/UpdateOwnProfile.php`
- Create: `app/Http/Controllers/ProfileController.php`
- Create: `app/Http/Controllers/Admin/MemberController.php`
- Create: `app/Http/Requests/UpdateProfileRequest.php`
- Create: `app/Http/Requests/Admin/StoreMemberRequest.php`
- Create: `app/Http/Requests/Admin/UpdateMemberRequest.php`
- Create: `resources/views/profile/edit.blade.php`
- Create: `resources/views/admin/members/index.blade.php`
- Create: `resources/views/admin/members/create.blade.php`
- Create: `resources/views/admin/members/edit.blade.php`
- Create: `resources/views/admin/members/show.blade.php`
- Create: `tests/Feature/Profile/ProfileManagementTest.php`
- Create: `tests/Feature/Admin/MemberManagementTest.php`

**Step 1: Write failing ownership and CRUD tests**

Members can update only their own name/contact/address/date of birth. Admin can search, create, view, update, and safely delete members without rental history; deletion with historical data is rejected.

**Step 2: Implement request validation and actions**

Keep user/profile updates atomic. Admin creation hashes passwords and creates one profile. Protect admin accounts from member-only bulk operations.

**Step 3: Implement Blade pages and pagination**

Use responsive tables/cards, search persistence, validation states, and history links.

**Step 4: Verify and commit**

Commit: `feat: add profile and member management`

### Task 5: Implement unit, category, and combo administration

**Files:**
- Create: `app/Application/Units/CreateUnit.php`
- Create: `app/Application/Units/UpdateUnit.php`
- Create: `app/Http/Controllers/Admin/UnitController.php`
- Create: `app/Http/Controllers/Admin/CategoryController.php`
- Create: `app/Http/Controllers/Admin/ComboController.php`
- Create: `app/Http/Requests/Admin/StoreUnitRequest.php`
- Create: `app/Http/Requests/Admin/UpdateUnitRequest.php`
- Create: corresponding category/combo requests
- Create: `resources/views/admin/units/*.blade.php`
- Create: `resources/views/admin/categories/*.blade.php`
- Create: `resources/views/admin/combos/*.blade.php`
- Create: `tests/Feature/Admin/UnitManagementTest.php`
- Create: `tests/Feature/Admin/CategoryManagementTest.php`
- Create: `tests/Feature/Admin/ComboManagementTest.php`

**Step 1: Write failing CRUD and integrity tests**

Assert unique unit code, many-category sync, valid prices/capacity/duration/controller counts, maintenance state, active combo filtering, and blocked deletion when history exists.

**Step 2: Implement actions and controllers**

Sync category IDs inside unit create/update transactions. Controllers delegate and redirect with flash messages.

**Step 3: Implement admin views**

Include filters, status badges, checkbox category selection, combo activation, pagination, and empty states.

**Step 4: Verify and commit**

Commit: `feat: add inventory category and combo management`

### Task 6: Implement reusable domain rules

**Files:**
- Create: `app/Domain/Rentals/RentalDuration.php`
- Create: `app/Domain/Rentals/RentalWarning.php`
- Create: `app/Domain/Rentals/FineCalculator.php`
- Create: `app/Domain/Rentals/TransactionCalculator.php`
- Create: `app/Domain/Rentals/RentalStatusTransitions.php`
- Create: `app/Domain/Bookings/BookingStatusTransitions.php`
- Create: `app/Domain/Delivery/DeliveryStatusTransitions.php`
- Create: `app/Domain/Exceptions/BusinessRuleViolation.php`
- Create: `tests/Unit/Domain/Rentals/*Test.php`
- Create: `tests/Unit/Domain/Bookings/BookingStatusTransitionsTest.php`
- Create: `tests/Unit/Domain/Delivery/DeliveryStatusTransitionsTest.php`

**Step 1: Write failing pure unit tests**

Cover inclusive calendar duration, invalid ranges, five-day policy, remaining days, safe/ending-soon/overdue warnings, late-day fine totals, monetary total formula, and allowed/forbidden state transitions.

**Step 2: Implement immutable result values and rules**

Use Carbon immutable dates at boundaries but return simple typed results. Throw `BusinessRuleViolation` with a safe Indonesian message for expected conflicts.

**Step 3: Verify and commit**

Run all domain tests with no database.

Commit: `feat: add rental workflow domain rules`

### Task 7: Implement availability, catalogue, capacity, and SmartPick

**Files:**
- Create: `app/Queries/AvailableUnitQuery.php`
- Create: `app/Queries/UnitCatalogueQuery.php`
- Create: `app/Domain/Recommendations/RecommendationCriteria.php`
- Create: `app/Domain/Recommendations/RecommendationScore.php`
- Create: `app/Application/Recommendations/RecommendUnits.php`
- Create: `app/Http/Controllers/CatalogueController.php`
- Create: `app/Http/Requests/RecommendationRequest.php`
- Create: `resources/views/catalogue/index.blade.php`
- Create: `resources/views/catalogue/show.blade.php`
- Create: `resources/views/catalogue/recommendations.blade.php`
- Modify: `routes/web.php`
- Create: `tests/Feature/Catalogue/CatalogueTest.php`
- Create: `tests/Feature/Catalogue/AvailabilityTest.php`
- Create: `tests/Unit/Domain/Recommendations/RecommendationScoreTest.php`
- Create: `tests/Feature/Catalogue/SmartPickTest.php`

**Step 1: Write failing query tests**

Cover name/category/capacity/budget filters, pagination, maintenance exclusion, overlapping confirmed bookings, active rental overlap, and available future periods.

**Step 2: Write failing recommendation tests**

Score capacity fit, category match, availability, and budget fit deterministically. Assert unavailable units are excluded and ties use price/code ordering.

**Step 3: Implement queries and use case**

Build composable Eloquent scopes/query objects and return recommendation explanations to Blade.

**Step 4: Implement responsive catalogue views**

Show capacity, price, categories, operational/schedule availability, combo suggestions, filters, and SmartPick form.

**Step 5: Verify and commit**

Commit: `feat: add catalogue availability and SmartPick`

### Task 8: Implement booking workflow

**Files:**
- Create: `app/Application/Bookings/CreateBooking.php`
- Create: `app/Application/Bookings/CancelBooking.php`
- Create: `app/Application/Bookings/ConfirmBooking.php`
- Create: `app/Http/Controllers/BookingController.php`
- Create: `app/Http/Controllers/Admin/BookingController.php`
- Create: `app/Http/Requests/StoreBookingRequest.php`
- Create: `resources/views/bookings/index.blade.php`
- Create: `resources/views/bookings/create.blade.php`
- Create: `resources/views/bookings/show.blade.php`
- Create: `resources/views/admin/bookings/index.blade.php`
- Create: `resources/views/admin/bookings/show.blade.php`
- Create: `tests/Feature/Bookings/BookingWorkflowTest.php`
- Create: `tests/Feature/Bookings/BookingAuthorizationTest.php`

**Step 1: Write failing workflow tests**

Cover creation, generated unique code, duration snapshot, invalid dates, overlap rejection, cancellation ownership, admin confirmation recheck, expiry/completion transition restrictions, and concurrent confirmation intent.

**Step 2: Implement actions with transactions and locking**

Lock the selected unit before final availability checks. Never accept duration or status from member input.

**Step 3: Implement member/admin pages**

Show date/status/filter/action affordances according to ownership and valid transitions.

**Step 4: Verify and commit**

Commit: `feat: add booking workflow`

### Task 9: Implement rental creation and active-rental dashboard

**Files:**
- Create: `app/Application/Rentals/StartRental.php`
- Create: `app/Http/Controllers/Admin/RentalController.php`
- Create: `app/Http/Controllers/RentalController.php`
- Create: `app/Http/Requests/Admin/StartRentalRequest.php`
- Create: `app/Queries/MemberRentalQuery.php`
- Create: `resources/views/rentals/index.blade.php`
- Create: `resources/views/rentals/show.blade.php`
- Create: `resources/views/admin/rentals/index.blade.php`
- Create: `resources/views/admin/rentals/create.blade.php`
- Create: `resources/views/admin/rentals/show.blade.php`
- Create: `tests/Feature/Rentals/StartRentalTest.php`
- Create: `tests/Feature/Rentals/ActiveRentalTest.php`

**Step 1: Write failing rental tests**

Cover rental from booking/direct rental, maximum two active units, availability, five-day policy, combo price snapshot, normal price snapshot, booking completion, unique rental code, and unit status update.

**Step 2: Implement atomic rental start**

Lock member/unit/booking as appropriate, recheck rules, calculate amounts in domain services, create rental, update booking/unit, and create an initial transaction summary.

**Step 3: Build dashboards**

Member page shows active rental cards and warning badges. Admin pages provide filters and valid workflow actions.

**Step 4: Verify and commit**

Commit: `feat: add rental workflow and dashboard`

### Task 10: Implement timer, warning, and extension workflow

**Files:**
- Create: `app/Application/Rentals/RequestExtension.php`
- Create: `app/Application/Rentals/ReviewExtension.php`
- Create: `app/Http/Controllers/RentalExtensionController.php`
- Create: `app/Http/Controllers/Admin/RentalExtensionController.php`
- Create: `app/Http/Requests/StoreRentalExtensionRequest.php`
- Create: `resources/views/rental-extensions/create.blade.php`
- Create: `resources/views/admin/rental-extensions/index.blade.php`
- Create: `resources/views/admin/rental-extensions/show.blade.php`
- Create: `tests/Feature/Rentals/RentalTimerTest.php`
- Create: `tests/Feature/Rentals/RentalExtensionTest.php`

**Step 1: Write failing timer and extension tests**

Assert calendar-day display, warning levels, member ownership, one pending request policy, requested due date, approval availability recheck, review metadata, cost calculation, due-date update, and rejection behavior.

**Step 2: Implement extension actions**

Approval locks rental/unit, validates current state and schedule, updates due date and transaction total, and records the reviewer atomically.

**Step 3: Implement views and commit**

Commit: `feat: add rental timer warning and extensions`

### Task 11: Implement return, fines, and transaction management

**Files:**
- Create: `app/Application/Rentals/ProcessReturn.php`
- Create: `app/Application/Fines/CreateFine.php`
- Create: `app/Application/Transactions/RecalculateTransaction.php`
- Create: `app/Application/Transactions/UpdatePaymentStatus.php`
- Create: `app/Http/Controllers/Admin/ReturnController.php`
- Create: `app/Http/Controllers/Admin/FineController.php`
- Create: `app/Http/Controllers/Admin/TransactionController.php`
- Create: relevant admin Form Requests
- Create: `resources/views/admin/returns/create.blade.php`
- Create: `resources/views/admin/fines/*.blade.php`
- Create: `resources/views/admin/transactions/*.blade.php`
- Create: `resources/views/transactions/show.blade.php`
- Create: `tests/Feature/Rentals/ReturnWorkflowTest.php`
- Create: `tests/Feature/Admin/FineManagementTest.php`
- Create: `tests/Feature/Transactions/TransactionManagementTest.php`

**Step 1: Write failing end-of-rental tests**

Cover admin-only returns, actual return time, late-day calculation, optional damage/other fine, unit availability only when at outlet, amount aggregation, discounts, delivery fees, manual paid status, and member transaction ownership.

**Step 2: Implement transactional actions**

Lock rental/unit/transaction; create or update fines; recalculate components from persisted sources; update rental/unit states; prevent duplicate return processing.

**Step 3: Implement pages and commit**

Commit: `feat: add returns fines and transactions`

### Task 12: Implement pickup and delivery service

**Files:**
- Create: `app/Application/Deliveries/CreateDelivery.php`
- Create: `app/Application/Deliveries/UpdateDelivery.php`
- Create: `app/Application/Deliveries/AdvanceDeliveryStatus.php`
- Create: `app/Http/Controllers/DeliveryController.php`
- Create: `app/Http/Controllers/Admin/DeliveryController.php`
- Create: `app/Http/Requests/StoreDeliveryRequest.php`
- Create: `app/Http/Requests/Admin/UpdateDeliveryRequest.php`
- Create: `resources/views/deliveries/*.blade.php`
- Create: `resources/views/admin/deliveries/*.blade.php`
- Create: `tests/Feature/Deliveries/DeliveryWorkflowTest.php`
- Create: `tests/Feature/Deliveries/DeliveryAuthorizationTest.php`

**Step 1: Write failing delivery tests**

Cover outbound/return uniqueness by rental/type, pickup vs delivery, required address/contact for delivery, manual non-negative flat fee, courier assignment, allowed transitions, cancellation restrictions, transaction fee recalculation, and return-to-outlet unit availability.

**Step 2: Implement delivery actions**

Use transition rules and database transactions. Treat return pickup as still rented until the unit reaches the outlet.

**Step 3: Implement member/admin tracking views**

Show readable status timeline, schedule, fee, contact, and courier data without GPS claims.

**Step 4: Verify and commit**

Commit: `feat: add pickup and delivery workflow`

### Task 13: Implement rental history, print view, and Bakso Rank

**Files:**
- Create: `app/Domain/Loyalty/BaksoRank.php`
- Create: `app/Queries/RentalHistoryQuery.php`
- Create: `app/Application/Loyalty/GetMemberRank.php`
- Create: `app/Http/Controllers/RentalHistoryController.php`
- Create: `app/Http/Controllers/Admin/RentalHistoryController.php`
- Create: `resources/views/history/index.blade.php`
- Create: `resources/views/history/show.blade.php`
- Create: `resources/views/history/print.blade.php`
- Create: `resources/views/admin/history/index.blade.php`
- Create: `resources/views/admin/history/print.blade.php`
- Create: `tests/Unit/Domain/Loyalty/BaksoRankTest.php`
- Create: `tests/Feature/History/RentalHistoryTest.php`
- Create: `tests/Feature/History/RentalHistoryPrintTest.php`

**Step 1: Write failing rank boundary tests**

Assert Rookie 0–5, Player 6–15, Pro 16–30, Legend >30 using completed rental duration only.

**Step 2: Write failing history scope tests**

Members see only their history; admins see/filter all; print routes obey the same policy and include rental/transaction/fine/delivery summary.

**Step 3: Implement queries, rank, and print CSS**

Use eager loading and paginated screen queries; print views use a dedicated non-navigation layout.

**Step 4: Verify and commit**

Commit: `feat: add rental history and Bakso Rank`

### Task 14: Implement admin analytics and rental heatmap

**Files:**
- Create: `app/Queries/AdminDashboardQuery.php`
- Create: `app/Queries/RentalAnalyticsQuery.php`
- Create: `app/Queries/RentalHeatmapQuery.php`
- Create: `app/Http/Controllers/Admin/DashboardController.php`
- Create: `app/Http/Controllers/Admin/AnalyticsController.php`
- Create: `resources/views/admin/dashboard.blade.php`
- Create: `resources/views/admin/analytics/index.blade.php`
- Create: `resources/views/components/heatmap.blade.php`
- Create: `tests/Feature/Admin/AdminDashboardTest.php`
- Create: `tests/Feature/Admin/RentalAnalyticsTest.php`
- Create: `tests/Feature/Admin/RentalHeatmapTest.php`

**Step 1: Write failing aggregate tests**

Seed known records and assert total rentals, active rentals, available units, members, completed days, revenue, fines, popular unit, active member, and pickup/delivery proportions.

**Step 2: Write failing heatmap tests**

Assert date-period validation, zero-filled date series, per-day rental counts, and no hourly grouping.

**Step 3: Implement query services and dashboard**

Use aggregate SQL/Eloquent queries, not loading every record into PHP. Render stat cards, ranked tables, delivery proportion, and accessible heatmap cells/tooltips.

**Step 4: Verify and commit**

Commit: `feat: add rental analytics and heatmap`

### Task 15: Add demo seeders and factories

**Files:**
- Modify: `database/seeders/DatabaseSeeder.php`
- Create: `database/seeders/AdminUserSeeder.php`
- Create: `database/seeders/CategorySeeder.php`
- Create: `database/seeders/UnitSeeder.php`
- Create: `database/seeders/ComboSeeder.php`
- Create/modify: factories under `database/factories/`
- Create: `tests/Feature/Database/DemoSeederTest.php`

**Step 1: Write failing seed contract test**

Assert one admin, known demo member, profiles, PRD categories, PS4/PS5 units, category assignments, and Bakso Mabar/Family combos. Passwords must be documented demo credentials, not production secrets.

**Step 2: Implement idempotent seeders and realistic factories**

Use `updateOrCreate` for stable master/demo identifiers. Keep random transactional history in a separate optional demo path if it could make assertions unstable.

**Step 3: Verify fresh seed and commit**

Run `php artisan migrate:fresh --seed` and the seeder test.

Commit: `feat: add Bakso Console demo data`

### Task 16: Complete route coverage, end-to-end flow, and UI hardening

**Files:**
- Modify: `routes/web.php`
- Modify: Blade views/components as failures require
- Create: `tests/Feature/Workflows/CompleteRentalJourneyTest.php`
- Create: `tests/Feature/Views/AuthenticatedRouteSmokeTest.php`
- Create: `docs/demo-guide.md`

**Step 1: Write the complete journey test**

Execute register/profile -> catalogue/SmartPick -> booking -> admin confirmation -> rental -> outbound delivery -> extension -> return pickup -> return/fine -> transaction paid -> history/rank/analytics. Assert database and authorization state at every boundary.

**Step 2: Write route smoke tests**

Visit every named member/admin GET route using appropriate fixtures and assert successful Blade responses, no N+1-sensitive missing relationships, and role protection.

**Step 3: Harden UI states**

Ensure pagination filters persist, destructive actions have confirmation, statuses always include text, forms preserve input, empty states are helpful, and mobile tables degrade into scroll/cards.

**Step 4: Write demo guide**

Document setup, seed command, credentials, core demo flow, role routes, and known v1 constraints (manual payment, manual delivery fee/status, no GPS/multi-outlet).

**Step 5: Verify and commit**

Commit: `test: verify complete rental journey`

### Task 17: Final verification and publication

**Files:**
- Modify only files required by verification failures.

**Step 1: Format all application PHP**

Run: `vendor/bin/pint app database routes tests`

Expected: PASS.

**Step 2: Verify database lifecycle**

Run: `php artisan migrate:fresh --seed --env=testing --force`

Expected: all migrations and seeders complete.

**Step 3: Run the complete test suite**

Run: `php artisan test`

Expected: zero failures.

**Step 4: Build production assets**

Run: `npm run build`

Expected: Vite reports a successful production build.

**Step 5: Inspect requirements and repository state**

Compare implementation against every PRD section 6.1–6.24 and the design document. Run `git diff --check`, `git status --short`, and inspect the full branch diff.

**Step 6: Commit verification-only fixes if needed**

Commit: `chore: finalize full PRD implementation`

**Step 7: Publish through a draft PR**

Push `feature/full-prd-implementation` and open a draft PR targeting the branch that contains the shared foundation, or target `main` after PR #1 is merged. Include full test/build evidence and demo credentials in the PR description.
