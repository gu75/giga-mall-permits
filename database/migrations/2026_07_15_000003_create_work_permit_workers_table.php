<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_permit_workers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_permit_id')->constrained()->cascadeOnDelete();
            $table->string('worker_name');
            $table->string('job_description');
            $table->string('cnic_number');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_permit_workers');
    }
};
