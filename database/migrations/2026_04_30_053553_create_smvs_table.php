<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('smvs', function (Blueprint $table) {
            $table->id();
            $table->string('po_no');
            $table->string('style_model_no');
            $table->string('item')->nullable();
            $table->decimal('smv', 8, 2)->nullable(); 
            $table->decimal('CM_Dz', 8, 2)->nullable();
            $table->decimal('CM_pcs', 8, 2)->nullable();
            $table->decimal('fob_pcs', 10, 2)->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smvs');
    }
};
