<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('daily_price', 12, 2);
            $table->unsignedSmallInteger('max_players')->default(1)->index();
            $table->string('status')->default('available')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
