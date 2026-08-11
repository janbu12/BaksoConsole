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
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::unprepared("
                CREATE TRIGGER trg_update_unit_status 
                AFTER UPDATE ON rentals 
                FOR EACH ROW
                BEGIN
                    IF NEW.status != OLD.status THEN
                        UPDATE units SET status = CASE
                            WHEN NEW.status = 'active' THEN 'rented'
                            WHEN NEW.status = 'completed' THEN 'available'
                            WHEN NEW.status = 'cancelled' THEN 'available'
                            ELSE status
                        END
                        WHERE id = NEW.unit_id;
                    END IF;
                END;
            ");
        } elseif ($driver === 'sqlite') {
            DB::unprepared("
                CREATE TRIGGER trg_update_unit_status 
                AFTER UPDATE OF status ON rentals 
                BEGIN
                    UPDATE units SET status = CASE
                        WHEN NEW.status = 'active' THEN 'rented'
                        WHEN NEW.status = 'completed' THEN 'available'
                        WHEN NEW.status = 'cancelled' THEN 'available'
                        ELSE status
                    END
                    WHERE id = NEW.unit_id;
                END;
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_update_unit_status");
    }
};
