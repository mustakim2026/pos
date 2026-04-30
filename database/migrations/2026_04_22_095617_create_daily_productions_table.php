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
    Schema::create('daily_productions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('efficiency_id')->constrained('efficiencies')->onDelete('cascade');
        $table->string('line_no');
        $table->string('po_no')->nullable(); 
        $table->string('style_model_no');
        $table->string('item');
        $table->integer('target')->default(0); // Added default to prevent errors
        $table->integer('output')->default(0); 
        $table->decimal('smv', 8, 2);
        $table->decimal('produce_minutes', 8, 2); // Added default to prevent errors
        $table->string('remarks')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_productions');
    }
};
