<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->string('source', 20)->default('manual')->after('jumlah');
        });

        $autoIds = DB::table('cabang_distribusi_items')
            ->whereNotNull('barang_masuk_id')
            ->pluck('barang_masuk_id')
            ->unique()
            ->values();

        DB::table('barang_masuk')->update(['source' => 'manual']);

        if ($autoIds->isNotEmpty()) {
            DB::table('barang_masuk')
                ->whereIn('id', $autoIds)
                ->update(['source' => 'return']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
