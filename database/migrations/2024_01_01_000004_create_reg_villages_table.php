<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reg_villages', function (Blueprint $table) {
            $table->char('id', 10)->primary();
            $table->char('district_id', 6);
            $table->string('name', 255);

            $table->foreign('district_id')
                  ->references('id')
                  ->on('reg_districts')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reg_villages');
    }
};
