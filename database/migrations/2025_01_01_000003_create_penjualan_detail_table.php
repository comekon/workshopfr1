<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan_detail', function (Blueprint $table) {
            $table->increments('idpenjualan_detail');
            $table->unsignedInteger('id_penjualan');
            
            // varchar(10) menyesuaikan dengan tabel `barang` milik kamu (berdasarkan screenshot)
            $table->string('id_barang', 10);
            
            $table->smallInteger('jumlah');
            $table->integer('subtotal');

            // Foreign Key constraint
            $table->foreign('id_penjualan')
                  ->references('id_penjualan')
                  ->on('penjualan')
                  ->onDelete('cascade');

            $table->foreign('id_barang')
                  ->references('id_barang')
                  ->on('barang')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan_detail');
    }
};
