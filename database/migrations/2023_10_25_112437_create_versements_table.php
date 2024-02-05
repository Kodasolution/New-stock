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
        Schema::create('versements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('salaire_id');
            $table->string('montant_verse');
            $table->date('date_verse')->nullable();
            $table->boolean('status')->default(0);
            $table->integer('mois');
            $table->boolean('has_dette')->default(0);
            $table->double('dette_montant')->nullable();
            $table->foreign('salaire_id')->references('id')->on('salaires')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('versement');
    }
};
