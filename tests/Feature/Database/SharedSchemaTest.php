<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

it('provides every shared application table', function () {
    $tables = [
        'profiles',
        'units',
        'categories',
        'category_unit',
        'combos',
        'bookings',
        'rentals',
        'rental_extensions',
        'fines',
        'deliveries',
        'transactions',
    ];

    foreach ($tables as $table) {
        expect(Schema::hasTable($table))->toBeTrue("Expected the [$table] table to exist.");
    }
});

it('provides the agreed columns for every shared table', function () {
    $columns = [
        'users' => ['role'],
        'profiles' => ['user_id', 'phone', 'address', 'date_of_birth'],
        'units' => ['name', 'code', 'description', 'daily_price', 'max_players', 'status'],
        'categories' => ['name', 'slug', 'description'],
        'combos' => ['name', 'slug', 'description', 'duration_days', 'controller_count', 'price', 'is_active'],
        'bookings' => ['booking_code', 'user_id', 'unit_id', 'start_date', 'end_date', 'duration_days', 'status', 'notes'],
        'rentals' => ['rental_code', 'user_id', 'unit_id', 'booking_id', 'combo_id', 'start_date', 'due_date', 'duration_days', 'daily_price', 'subtotal', 'status', 'returned_at', 'return_notes'],
        'rental_extensions' => ['rental_id', 'requested_due_date', 'additional_days', 'additional_cost', 'reason', 'status', 'reviewed_by', 'reviewed_at', 'review_notes'],
        'fines' => ['rental_id', 'type', 'late_days', 'amount', 'reason', 'status', 'paid_at'],
        'deliveries' => ['rental_id', 'type', 'method', 'address', 'contact_number', 'delivery_fee', 'courier_name', 'status', 'scheduled_at', 'completed_at'],
        'transactions' => ['invoice_number', 'rental_id', 'user_id', 'rental_amount', 'fine_amount', 'delivery_fee', 'discount_amount', 'total_amount', 'payment_method', 'status', 'paid_at', 'notes'],
    ];

    foreach ($columns as $table => $expectedColumns) {
        expect(Schema::hasColumns($table, $expectedColumns))
            ->toBeTrue("Expected the [$table] table to contain its shared columns.");
    }
});
