<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reg_districts', function (Blueprint $table) {
            $table->char('id', 6)->primary();
            $table->char('regency_id', 4);
            $table->string('name', 255);

            $table->foreign('regency_id')
                  ->references('id')
                  ->on('reg_regencies')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reg_districts');
    }
};
