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
        if (!Schema::hasColumn('barang', 'kode_barang') || !Schema::hasColumn('barang', 'satuan') || !Schema::hasColumn('barang', 'stok_min') || !Schema::hasColumn('barang', 'status')) {
            Schema::table('barang', function (Blueprint $table) {
                if (!Schema::hasColumn('barang', 'kode_barang')) {
                    $table->string('kode_barang')->nullable()->unique()->after('id');
                }
                if (!Schema::hasColumn('barang', 'satuan')) {
                    $table->string('satuan')->nullable()->after('nama_barang');
                }
                if (!Schema::hasColumn('barang', 'stok_min')) {
                    $table->integer('stok_min')->default(5)->after('satuan');
                }
                if (!Schema::hasColumn('barang', 'status')) {
                    $table->string('status')->nullable()->after('stok_min');
                }
            });
        }

        // Populate existing records with defaults
        DB::table('barang')->get()->each(function ($row) {
            $nama = strtolower($row->nama_barang ?? '');

            // determine satuan
            $satuan = null;
            if (str_contains($nama, 'tusuk') || str_contains($nama, 'sedotan') || str_contains($nama, 'cup') || str_contains($nama, 'sumpit')) {
                $satuan = 'pack';
            }
            if (str_contains($nama, 'piring') || str_contains($nama, 'gelas')) {
                $satuan = 'pcs';
            }

            // build kode_barang: first 2 letters of name (letters only) + id
            $letters = preg_replace('/[^a-z]/', '', $nama);
            $prefix = strtoupper(substr($letters, 0, 2));
            if (strlen($prefix) < 2) {
                $prefix = str_pad($prefix, 2, 'X');
            }
            $kode = $prefix . $row->id;

            // determine status based on stok and stok_min (default 5)
            $stok = (int) ($row->stok ?? 0);
            $status = $stok >= 5 ? 'normal' : 'low';

            $update = [];
            $update['kode_barang'] = $kode;
            $update['satuan'] = $satuan;
            $update['stok_min'] = 5;
            $update['status'] = $status;

            DB::table('barang')->where('id', $row->id)->update($update);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn(['kode_barang', 'satuan', 'stok_min', 'status']);
        });
    }
};
