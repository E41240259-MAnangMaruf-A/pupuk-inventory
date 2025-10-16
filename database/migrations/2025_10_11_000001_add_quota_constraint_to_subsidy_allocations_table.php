<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Constraint untuk validasi kuota
        DB::statement("
            ALTER TABLE subsidy_allocations
            ADD CONSTRAINT chk_quota_valid
            CHECK (
                used_quota <= maximum_quota
                AND remaining_quota = maximum_quota - used_quota
            )
        ");

        // Trigger: ubah status ke 'inactive' jika used_quota = 0
        DB::unprepared("
            CREATE TRIGGER trg_subsidy_allocation_status_update
            BEFORE UPDATE ON subsidy_allocations
            FOR EACH ROW
            BEGIN
                IF NEW.used_quota = 0 THEN
                    SET NEW.status = 'inactive';
                ELSEIF NEW.status = 'inactive' AND NEW.used_quota > 0 THEN
                    SET NEW.status = 'active';
                END IF;
            END
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE subsidy_allocations
            DROP CONSTRAINT IF EXISTS chk_quota_valid
        ");

        DB::unprepared("DROP TRIGGER IF EXISTS trg_subsidy_allocation_status_update");
    }
};
