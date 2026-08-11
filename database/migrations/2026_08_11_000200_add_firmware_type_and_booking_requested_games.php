<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->string('firmware_type')->default('original')->after('model_number')->index();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->json('requested_games')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn('firmware_type');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('requested_games');
        });
    }
};
