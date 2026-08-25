<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_permits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();

            // Header fields from the paper form
            $table->string('outlet_name');
            $table->string('floor_location');
            $table->string('site_incharge_name');
            $table->string('site_incharge_cell_no');
            $table->string('site_incharge_cnic');
            $table->text('nature_of_work');
            $table->string('requested_by');
            $table->string('requested_by_cell_no');

            $table->date('valid_from');
            $table->date('valid_to');

            // Duration of work is fixed policy (Mon-Sun 09:00PM-08:00AM)
            // but we allow a flag + reason for day-time work requiring special permission
            $table->boolean('daytime_work_requested')->default(false);
            $table->text('daytime_work_reason')->nullable();

            // Sequential approval trail
            $table->enum('status', [
                'pending_operations',
                'pending_hse',
                'pending_security',
                'approved',
                'rejected',
            ])->default('pending_operations');

            $table->foreignId('operations_approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('operations_approved_at')->nullable();
            $table->text('operations_remarks')->nullable();

            $table->foreignId('hse_approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('hse_approved_at')->nullable();
            $table->text('hse_remarks')->nullable();

            $table->foreignId('security_approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('security_approved_at')->nullable();
            $table->text('security_remarks')->nullable();

            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_permits');
    }
};
