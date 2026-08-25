<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_permits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();

            $table->enum('shop_type', ['retail', 'food_court']);
            $table->string('shop_details');
            $table->string('manager_sup_name');
            $table->string('manager_sup_cell_no');
            $table->string('cnic_no');
            $table->date('dated');

            $table->enum('direction', ['in', 'out']);

            // Approval by Operations Dept
            $table->enum('status', [
                'pending_operations',
                'approved',
                'rejected',
                'gate_cleared', // security has logged the actual in/out
            ])->default('pending_operations');

            $table->foreignId('operations_approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('operations_approved_at')->nullable();
            $table->text('operations_remarks')->nullable();

            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();

            // Security gate log
            $table->foreignId('gate_logged_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('gate_logged_at')->nullable();
            $table->text('gate_remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_permits');
    }
};
