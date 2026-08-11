<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('delivery_method')->default('pickup')->after('status');
            $table->string('delivery_address')->nullable()->after('delivery_method');
            $table->string('contact_number')->nullable()->after('delivery_address');
            $table->decimal('delivery_fee', 12, 2)->default(0)->after('contact_number');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['delivery_method', 'delivery_address', 'contact_number', 'delivery_fee']);
        });
    }
};
