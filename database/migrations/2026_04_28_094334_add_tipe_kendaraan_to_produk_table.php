<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom tipe_kendaraan ke tabel produk.
     *
     * Menggunakan enum untuk membatasi nilai yang boleh dimasukkan.
     * Nilai 'universal' = sparepart yang cocok untuk semua jenis kendaraan.
     * nullable() agar data produk lama tidak error (default null).
     */
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            // Tambah kolom setelah kolom 'stok'
            $table->enum('tipe_kendaraan', ['motor', 'mobil', 'truk', 'universal'])
                  ->nullable()
                  ->after('stok');
        });
    }

    /**
     * Hapus kolom tipe_kendaraan (rollback).
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn('tipe_kendaraan');
        });
    }
};
