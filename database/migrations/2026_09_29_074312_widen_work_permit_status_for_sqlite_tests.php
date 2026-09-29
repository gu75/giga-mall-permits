<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL already got this via an earlier ALTER TABLE ... MODIFY.
        // SQLite (used only by the test suite) can't run MODIFY, so its
        // status column is still stuck on the old, narrower list of
        // values. This rebuilds the column there so 'in_review' is valid.
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('work_permits', function (Blueprint $table) {
                $table->string('status')->default('pending_operations')->change();
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('work_permits', function (Blueprint $table) {
                $table->string('status')->default('pending_operations')->change();
            });
        }
    }
};
