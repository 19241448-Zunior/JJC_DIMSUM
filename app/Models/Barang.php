<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'satuan',
        'stok_min',
        'status',
        'stok',
    ];

    protected static function booted()
    {
        static::creating(function ($barang) {
            // ensure stok_min default
            if (empty($barang->stok_min)) {
                $barang->stok_min = 5;
            }

            // determine satuan if not provided
            if (empty($barang->satuan) && !empty($barang->nama_barang)) {
                $nama = strtolower($barang->nama_barang);
                if (str_contains($nama, 'tusuk') || str_contains($nama, 'sedotan') || str_contains($nama, 'cup') || str_contains($nama, 'sumpit')) {
                    $barang->satuan = 'pack';
                }
                if (str_contains($nama, 'piring') || str_contains($nama, 'gelas')) {
                    $barang->satuan = 'pcs';
                }
            }

            // generate kode_barang: 2 letters + digits
            if (empty($barang->kode_barang) && !empty($barang->nama_barang)) {
                $letters = preg_replace('/[^a-zA-Z]/', '', $barang->nama_barang);
                $prefix = strtoupper(substr($letters, 0, 2));
                if (strlen($prefix) < 2) {
                    $prefix = str_pad($prefix, 2, 'X');
                }
                // append timestamp-based numbers to reduce collisions
                $barang->kode_barang = $prefix . rand(100, 999);
            }

            // determine status based on stok vs stok_min
            $stok = (int) ($barang->stok ?? 0);
            $stokMin = (int) ($barang->stok_min ?? 5);
            $barang->status = $stok >= $stokMin ? 'normal' : 'low';
        });
    }

    /**
     * Get all barang masuk for this barang
     */
    public function barangMasuk(): HasMany
    {
        return $this->hasMany(BarangMasuk::class);
    }

    /**
     * Get all barang keluar for this barang
     */
    public function barangKeluar(): HasMany
    {
        return $this->hasMany(BarangKeluar::class);
    }

    /**
     * Get cabang distribution items related to this barang.
     */
    public function cabangDistribusiItems(): HasMany
    {
        return $this->hasMany(CabangDistribusiItem::class);
    }

    /**
     * Get total barang masuk
     */
    public function getTotalMasuk()
    {
        return $this->barangMasuk()->sum('jumlah');
    }

    /**
     * Get total barang keluar
     */
    public function getTotalKeluar()
    {
        return $this->barangKeluar()->sum('jumlah');
    }

    /**
     * Hitung stok aktual dari jejak transaksi masuk/keluar.
     *
     * Stok yang tersimpan di kolom `stok` bisa tertinggal ketika ada alur
     * transaksi cabang, jadi saldo yang ditampilkan dihitung ulang dari ledger.
     */
    public function getStokAttribute($value): int
    {
        return $this->getSaldoStokTransaksi();
    }

    /**
     * Status stok selalu mengikuti saldo aktual, bukan nilai snapshot lama.
     */
    public function getStatusAttribute($value): string
    {
        $stokMin = (int) ($this->stok_min ?? 5);

        return $this->getSaldoStokTransaksi() >= $stokMin ? 'normal' : 'low';
    }

    /**
     * Saldo stok dari transaksi valid yang masih aktif.
     */
    public function getSaldoStokTransaksi(): int
    {
        $totalMasuk = (int) $this->barangMasuk()
            ->whereNull('deleted_at')
            ->sum('jumlah');

        $totalKeluar = (int) $this->barangKeluar()
            ->whereNull('deleted_at')
            ->sum('jumlah');

        return max(0, $totalMasuk - $totalKeluar);
    }

    /**
     * Scope untuk mendapatkan barang dengan stok rendah (≤ 20)
     */
    public function scopeLowStock($query, $threshold = 20)
    {
        return $query->where('stok', '<=', $threshold);
    }

    /**
     * Mendapatkan array barang dengan stok rendah untuk notifikasi
     */
    public static function getLowStockNotifications($threshold = 20)
    {
        return self::lowStock($threshold)->get()->map(function ($barang) {
            return [
                'id' => $barang->id,
                'nama_barang' => $barang->nama_barang,
                'stok' => $barang->stok,
                'status' => $barang->stok == 0 ? 'habis' : 'hampir_habis',
                'pesan' => $barang->stok == 0 ? 'Stok habis' : 'Stok hampir habis'
            ];
        })->toArray();
    }
}
