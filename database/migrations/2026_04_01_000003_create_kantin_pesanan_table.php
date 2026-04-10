<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->increments('idpesanan');
            $table->string('nama', 255);                     // Guest_0000001
            $table->timestamp('timestamp')->useCurrent();
            $table->integer('total');
            $table->integer('metode_bayar')->default(0);      // 1=VA, 2=QRIS
            $table->smallInteger('status_bayar')->default(0); // 0=Belum, 1=Lunas
            $table->string('snap_token', 255)->nullable();
            $table->string('midtrans_order_id', 255)->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
