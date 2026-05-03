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
        'cabang_id',
        'lokasi_default_id',
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
     * Get the cabang this barang belongs to
     */
    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class);
    }

    /**
     * Get the default storage location for this barang
     */
    public function lokasiDefault(): BelongsTo
    {
        return $this->belongsTo(LokasiPenyimpanan::class, 'lokasi_default_id');
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
