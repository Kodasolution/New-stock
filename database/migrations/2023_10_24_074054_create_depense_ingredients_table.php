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
        Schema::create('depense_ingredients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('depense_id');
            $table->unsignedBigInteger('ingredient_id');
            $table->double('prix_unitaire');
            $table->date('date_creation');
            $table->foreign('depense_id')->references('id')->on('depenses')->onDelete('cascade');
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depense_ingredients');
    }
};
