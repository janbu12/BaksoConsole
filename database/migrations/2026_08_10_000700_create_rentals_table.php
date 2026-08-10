<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->string('rental_code')->unique();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('unit_id')->constrained()->restrictOnDelete();
            $table->foreignId('booking_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->foreignId('combo_id')->nullable()->constrained()->nullOnDelete();
            $table->date('start_date');
            $table->date('due_date');
            $table->unsignedSmallInteger('duration_days');
            $table->decimal('daily_price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->string('status')->default('pending');
            $table->timestamp('returned_at')->nullable();
            $table->text('return_notes')->nullable();
            $table->timestamps();

            $table->index(['unit_id', 'status', 'start_date', 'due_date']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
