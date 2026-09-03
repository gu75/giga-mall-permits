<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_permit_department_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_permit_id')->constrained()->cascadeOnDelete();
            $table->string('department'); // 'hse', 'security', future: 'technical', 'it', etc.
            $table->enum('status', ['approved', 'rejected'])->default('approved');
            $table->foreignId('actioned_by')->constrained('users');
            $table->timestamp('actioned_at');
            $table->text('remarks')->nullable();
            $table->timestamps();
           $table->unique(['work_permit_id', 'department'], 'wpda_permit_dept_unique');
        });

        // Preserve existing HSE/Security approval history from the old columns.
        $permits = DB::table('work_permits')->get();
        foreach ($permits as $permit) {
            if (! is_null($permit->hse_approved_at)) {
                DB::table('work_permit_department_approvals')->insert([
                    'work_permit_id' => $permit->id,
                    'department' => 'hse',
                    'status' => 'approved',
                    'actioned_by' => $permit->hse_approved_by,
                    'actioned_at' => $permit->hse_approved_at,
                    'remarks' => $permit->hse_remarks,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            if (! is_null($permit->security_approved_at)) {
                DB::table('work_permit_department_approvals')->insert([
                    'work_permit_id' => $permit->id,
                    'department' => 'security',
                    'status' => 'approved',
                    'actioned_by' => $permit->security_approved_by,
                    'actioned_at' => $permit->security_approved_at,
                    'remarks' => $permit->security_remarks,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Any permit currently mid-sequence moves to the new 'in_review' status.
        DB::table('work_permits')
            ->whereIn('status', ['pending_hse', 'pending_security'])
            ->update(['status' => 'in_review']);

        DB::statement("ALTER TABLE work_permits MODIFY status ENUM('pending_operations', 'in_review', 'approved', 'rejected') NOT NULL DEFAULT 'pending_operations'");

        // Widen role so future departments never need a migration to add a user.
        DB::statement("ALTER TABLE users MODIFY role VARCHAR(50) NOT NULL DEFAULT 'tenant'");
    }

    public function down(): void
    {
        Schema::dropIfExists('work_permit_department_approvals');
        DB::statement("ALTER TABLE work_permits MODIFY status ENUM('pending_operations', 'pending_hse', 'pending_security', 'approved', 'rejected') NOT NULL DEFAULT 'pending_operations'");
        DB::statement("ALTER TABLE users MODIFY role ENUM('tenant', 'operations', 'hse', 'security', 'admin') NOT NULL DEFAULT 'tenant'");
    }
};