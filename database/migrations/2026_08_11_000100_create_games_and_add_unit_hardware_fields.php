<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->string('genre')->nullable()->index();
            $table->string('icon')->nullable()->default('🎮');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('game_unit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['game_id', 'unit_id']);
        });

        Schema::table('units', function (Blueprint $table) {
            $table->string('serial_number')->nullable()->after('code')->index();
            $table->string('model_number')->nullable()->after('serial_number')->index();
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn(['serial_number', 'model_number']);
        });

        Schema::dropIfExists('game_unit');
        Schema::dropIfExists('games');
    }
};
