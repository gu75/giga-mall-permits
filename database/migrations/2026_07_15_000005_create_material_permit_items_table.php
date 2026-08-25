<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_permit_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_permit_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->string('quantity');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_permit_items');
    }
};
