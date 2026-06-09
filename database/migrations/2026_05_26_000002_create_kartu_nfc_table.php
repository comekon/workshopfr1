<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kartu_nfc', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->foreignId('mahasiswa_id')->unique()->constrained('mahasiswa')->cascadeOnDelete();
            $table->string('label')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kartu_nfc');
    }
};
