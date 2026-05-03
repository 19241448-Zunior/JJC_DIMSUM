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
        Schema::table('barang', function (Blueprint $table) {
            // Drop foreign key constraints first
            DB::statement('ALTER TABLE barang DROP FOREIGN KEY barang_cabang_id_foreign');
            DB::statement('ALTER TABLE barang DROP FOREIGN KEY barang_lokasi_default_id_foreign');
        });

        Schema::table('barang', function (Blueprint $table) {
            // Drop duplicate stok_minimal column
            if (Schema::hasColumn('barang', 'stok_minimal')) {
                $table->dropColumn('stok_minimal');
            }
            
            // Drop cabang_id and lokasi_default_id columns
            if (Schema::hasColumn('barang', 'cabang_id')) {
                $table->dropColumn('cabang_id');
            }
            if (Schema::hasColumn('barang', 'lokasi_default_id')) {
                $table->dropColumn('lokasi_default_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            // Re-add the columns if needed
            $table->integer('stok_minimal')->nullable()->after('stok');
            $table->unsignedBigInteger('cabang_id')->nullable()->after('stok_minimal');
            $table->unsignedBigInteger('lokasi_default_id')->nullable()->after('cabang_id');
            
            // Re-add foreign keys
            $table->foreign('cabang_id')->references('id')->on('cabangs')->onDelete('set null');
            $table->foreign('lokasi_default_id')->references('id')->on('lokasi_penyimpanans')->onDelete('set null');
        });
    }
};
