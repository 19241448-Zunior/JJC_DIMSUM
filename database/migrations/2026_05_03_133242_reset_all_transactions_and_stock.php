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
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Delete/Truncate all transaction data
        DB::table('barang_keluar')->truncate();
        DB::table('barang_masuk')->truncate();
        DB::table('stok_opnames')->truncate();
        DB::table('cabang_distribusi_items')->truncate();
        DB::table('cabang_distribusis')->truncate();

        // Reset all barang stok to 0 and status to 'low'
        DB::table('barang')->update([
            'stok' => 0,
            'status' => 'low',
            'updated_at' => now(),
        ]);

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is destructive and cannot be safely reversed
        // It is meant to be a manual reset operation
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Optionally, you can at least log what was reset
        // but restoring the original data would require backups
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
