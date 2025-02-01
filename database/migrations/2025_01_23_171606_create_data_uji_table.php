<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_uji', function (Blueprint $table) {
            $table->id();
            $table->text('content'); // Kolom untuk teks konten
            $table->enum('sentimen', ['positif', 'negatif', 'netral'])->nullable(); // Kolom untuk label sentimen (nullable karena data uji)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_uji');
    }
};
