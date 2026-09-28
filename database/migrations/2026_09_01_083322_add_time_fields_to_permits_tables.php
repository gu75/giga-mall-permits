<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_permits', function (Blueprint $table) {
            $table->time('valid_from_time')->nullable()->after('valid_from');
            $table->time('valid_to_time')->nullable()->after('valid_to');
        });

        Schema::table('material_permits', function (Blueprint $table) {
            $table->time('time')->nullable()->after('dated');
        });
    }

    public function down(): void
    {
        Schema::table('work_permits', function (Blueprint $table) {
            $table->dropColumn(['valid_from_time', 'valid_to_time']);
        });

        Schema::table('material_permits', function (Blueprint $table) {
            $table->dropColumn('time');
        });
    }
};
