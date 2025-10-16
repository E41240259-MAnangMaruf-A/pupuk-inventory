<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add CHECK constraint for data consistency
        DB::statement("
            ALTER TABLE subsidy_allocations
            ADD CONSTRAINT chk_quota_valid
            CHECK (
                used_quota <= maximum_quota
                AND remaining_quota = maximum_quota - used_quota
            )
        ");
    }

    public function down()
    {
        // Drop constraint when rolling back (MySQL 8+ syntax)
        DB::statement("
            ALTER TABLE subsidy_allocations
            DROP CONSTRAINT IF EXISTS chk_quota_valid
        ");
    }
};
