<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Roles used across the system:
     *  - tenant     : shop/outlet owner or staff, submits permit requests
     *  - operations : Operations Dept, first approval stage
     *  - hse        : HOD HSE, second approval stage (work permits only)
     *  - security   : HOD Security, final approval stage + gate IN/OUT logging
     *  - admin      : full access, manages users
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['tenant', 'operations', 'hse', 'security', 'admin'])
                ->default('tenant')
                ->after('email');

            // Tenant-specific fields (null for staff roles)
            $table->string('shop_name')->nullable()->after('role');
            $table->string('floor_location')->nullable()->after('shop_name');
            $table->string('cell_no')->nullable()->after('floor_location');
            $table->string('cnic')->nullable()->after('cell_no');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'shop_name', 'floor_location', 'cell_no', 'cnic']);
        });
    }
};
