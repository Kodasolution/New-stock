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
        Schema::create('remboursements_dettes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dette_id');
            $table->string('montant_rembourse');
            $table->date('date_rembourse');
            $table->foreign('dette_id')->references('id')->on('dettes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remboursements_dettes');
    }
};
