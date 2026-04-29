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
        Schema::create('daily_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('efficiency_id')->constrained('efficiencies')->onDelete('cascade');
            $table->string('line_no');
            $table->string('po_no');
            $table->string('style_model_no');
            $table->string('item');
            $table->integer('allocated_Hrs')->default(0);
            $table->integer('OP1')->default(0);   // Before Lunch
            $table->integer('HP1')->default(0);  // Before Lunch
            $table->integer('OP2')->default(0);   // After Lunch
            $table->integer('HP2')->default(0); 
            $table->integer('OP3')->default(0);   // After Lunch
            $table->integer('HP3')->default(0); 
            $table->integer('OP4')->default(0);   // After Lunch
            $table->integer('HP4')->default(0); 
            $table->integer('OP5')->default(0);   // After Lunch
            $table->integer('HP5')->default(0);  
            $table->integer('OP6')->default(0);   // After Lunch
            $table->integer('HP6')->default(0);
            $table->integer('OP7')->default(0);   // After Lunch
            $table->integer('HP7')->default(0); 
             $table->integer('OP8')->default(0);   // After Lunch
            $table->integer('HP8')->default(0);
            $table->integer('w_hrs')->default(0);  // After Lunch
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_attendances');
    }
};
