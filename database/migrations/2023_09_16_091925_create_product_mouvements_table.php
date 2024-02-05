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
        Schema::create('product_mouvements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mouvement_id');
            $table->unsignedBigInteger('product_id');
            $table->double('price_un');
            $table->double('quantity');
            $table->double('price_tot');
            $table->date('date_flux');
            $table->foreign('mouvement_id')->references('id')->on('mouvements')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_mouvements');
    }
};