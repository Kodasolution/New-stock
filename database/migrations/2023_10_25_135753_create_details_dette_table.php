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
        Schema::create('details_dette', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dette_id');
            $table->string('montant');
            $table->string('motif');
            $table->date('date_creation');
            $table->foreign('dette_id')->references('id')->on('dettes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details_dette');
    }
};
