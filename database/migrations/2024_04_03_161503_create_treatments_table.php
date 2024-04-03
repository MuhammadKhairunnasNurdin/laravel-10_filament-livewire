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
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->text('notes')->nullable();

            /**
             * cascadeOnDelete() function is used to set up cascading
             * deletes on foreign key relationships. This means that when
             * a record in the parent table is deleted, the corresponding
             * records in the child table with a matching foreign key will
             * also be deleted automatically.
             */
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();

            $table->unsignedInteger('price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
