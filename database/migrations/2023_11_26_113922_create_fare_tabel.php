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
        Schema::create('fare', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->integer('fare');
            $table->timestamps();
        });

        Schema::table('fare', function (Blueprint $table) {
            $table->unsignedBigInteger('destination_id');
         
            $table->foreign('destination_id')->references('id')->on('destination');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fare_tabel');
    }
};
